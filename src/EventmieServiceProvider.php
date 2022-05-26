<?php

namespace Classiebit\Eventmie;

use Illuminate\Foundation\AliasLoader;
use Classiebit\Eventmie\Facades\Eventmie as EventmieFacade;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

/* Laravel packages start*/ 
use TCG\Voyager\VoyagerServiceProvider as VoyagerServiceProvider;
use TCG\Voyager\Facades\Voyager;
use Classiebit\Eventmie\FormFields\OrganiserDropdown;
use TCG\Voyager\Models\Setting;

use Tightenco\Ziggy\ZiggyServiceProvider as ZiggyServiceProvider;
use View;
use Config;

use Laravel\Socialite\SocialiteServiceProvider as SocialiteServiceProvider;

use Barryvdh\DomPDF\ServiceProvider as DomPDFServiceProvider;
use ConsoleTVs\Charts\ChartsServiceProvider as ChartsServiceProvider;
use Yajra\DataTables\DataTablesServiceProvider as DataTablesServiceProvider;

use Spatie\Honeypot\HoneypotServiceProvider as HoneypotServiceProvider;


/* Console commands */
use  Classiebit\Eventmie\Commands\InstallCommand;
use  Classiebit\Eventmie\Commands\UpdateCommand;
use  Classiebit\Eventmie\Commands\ControllersCommand;
use  Classiebit\Eventmie\Commands\DataRowsSeedCommand;
use  Classiebit\Eventmie\Commands\MenuItemsSeedCommand;
use  Classiebit\Eventmie\Commands\SettingsSeedCommand;
use  Classiebit\Eventmie\Commands\TranslateCommand;

/* Bootstrap Pagination as default */
use Illuminate\Pagination\Paginator;

class EventmieServiceProvider extends ServiceProvider
{
    protected $is_facebook = false;
    protected $is_google   = false;

    
    /**
     * Register the application services.
     */
    public function register()
    {
        // register external packages
        $this->registerPackages();

        // register Eventmie facade
        $loader = AliasLoader::getInstance();
        $loader->alias('Eventmie', EventmieFacade::class);

        
        $this->app->singleton('eventmie', function () {
            return new Eventmie();
        });

        // boot up all helpers
        $this->loadHelpers();
        
        // boot up config file
        $this->registerConfigs();

        // customise voyager theme
        $this->customVoyagerTheme();

        // custom voyager formfields
        $this->voyagerFormFields();

        // initialise console commands 
        if ($this->app->runningInConsole()) 
        {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        // load middleware
        $this->loadMiddlewares();

        //exception handler
        \App::singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Classiebit\Eventmie\Exceptions\MyHandler::class
        );
        
        if (\Schema::hasTable('settings')) 
        {
            if (Setting::find(1)) 
            {
                // setup mail configs
                $this->mailConfiguration(setting('mail'));
                
                // setup regional settings
                $this->setRegional(setting('regional'));
                
                // Setup oauth 
                $this->socialite(setting('apps'));
            }
        }
        
        // load eventmie resources.views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eventmie');

        // load eventmie language files publishable.lang
        $this->loadTranslationsFrom(realpath(__DIR__.'/../publishable/lang/eventmie-pro'), 'eventmie-pro');
        
        // load eventmie database migrations
        if (config('eventmie.database.autoload_migrations', true)) 
            $this->loadMigrationsFrom(realpath(__DIR__.'/../publishable/database/migrations'));

        /* Pagination default: Bootstrap (for Laravel 8+) */
        if($this->app->version() >= 8) {
            Paginator::useBootstrap();
        }
    }

    /**
     * Register external package serviceproviders
    */
    private function registerPackages()
    {
        // voyager serviceProvider
        $this->app->register(VoyagerServiceProvider::class);
        
        // socialite serviceProvider
        $this->app->register(SocialiteServiceProvider::class);
        
        // ziggy serviceProvider
        $this->app->register(ZiggyServiceProvider::class);

        // DomPDFServiceProvider service provider
        $this->app->register(DomPDFServiceProvider::class);

        // chart service provider
        $this->app->register(ChartsServiceProvider::class);

        // data table service provider
        $this->app->register(DataTablesServiceProvider::class);

        // spam prevention
        $this->app->register(HoneypotServiceProvider::class);
        
    }

    /**
     * Load middlewares for user group specific access
     */
    private function loadMiddlewares()
    {
        $this->app['router']->aliasMiddleware('auth', \Classiebit\Eventmie\Middleware\Authenticate::class);
        $this->app['router']->aliasMiddleware('organiser', \Classiebit\Eventmie\Middleware\OrganiserMiddleware::class);
        $this->app['router']->aliasMiddleware('customer', \Classiebit\Eventmie\Middleware\CustomerMiddleware::class);
        $this->app['router']->aliasMiddleware('admin', \Classiebit\Eventmie\Middleware\AdminMiddleware::class);
        $this->app['router']->aliasMiddleware('guest', \Classiebit\Eventmie\Middleware\RedirectIfAuthenticated::class);
        $this->app['router']->aliasMiddleware('common', \Classiebit\Eventmie\Middleware\CommonMiddleware::class);
        $this->app['router']->aliasMiddleware('everified', \Classiebit\Eventmie\Middleware\EnsureEmailIsVerified::class);
        $this->app['router']->aliasMiddleware('admin.user', \Classiebit\Eventmie\Middleware\VoyagerAdminMiddleware::class);
    }
    
    /**
     * Load helpers.
     */
    private function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishablePath    = dirname(__DIR__).'/publishable';

        $publishable        = [
            'eventmie-pro-config' => [
                "{$publishablePath}/config/eventmie.php" => config_path('eventmie.php')
            ],
            'eventmie-pro-lang' => [
                "{$publishablePath}/lang/eventmie-pro" => resource_path('lang/vendor/eventmie-pro')
            ],
            'eventmie-pro-voyager-lang' => [
                "{$publishablePath}/lang/voyager" => resource_path('lang/vendor/voyager')
            ],
            'eventmie-pro-storage' => [
                "{$publishablePath}/dummy_content/" => storage_path('app/public')
            ],
        ];
        
        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }

    }

    /**
     * Setup Eventmie configs
      */
    private function registerConfigs()
    {
        /* ===== OVERRIDE EXTERNAL CONFIG WITHOUT PUBLISHING TO LARAVEL APP ===== */
        // voyager
        $voyager_config = dirname(__DIR__).'/publishable/config/voyager.php';
        $this->app['config']->set('voyager', require $voyager_config);
        
        // chart js
        $charts_config = dirname(__DIR__).'/publishable/config/charts.php';
        $this->app['config']->set('charts', require $charts_config);
        
        // datatables
        $datatables_config = dirname(__DIR__).'/publishable/config/datatables.php';
        $this->app['config']->set('datatables', require $datatables_config);
        
        /* 
        ================================================================================= */
        
        // **** ONLY MERGE EVENTMIE CONFIG ****
        $this->mergeConfigFrom(
            dirname(__DIR__).'/publishable/config/eventmie.php', 'eventmie'
        );
        
        // twice because of auto-generating locales
        // voyager
        $voyager_config = dirname(__DIR__).'/publishable/config/voyager.php';
        $this->app['config']->set('voyager', require $voyager_config);
    }

    /**
     * Customise voyager theme
      */
    private function customVoyagerTheme()
    {
        $theme_url = url('').'/frontend-assets?path='.urlencode('css/voyager.css');
        $this->app['config']->set('voyager.additional_css.custom_theme', $theme_url);
    }

    /**
     * Voyager custom formFields
    */
    public function voyagerFormFields() 
    {
        Voyager::addFormField(OrganiserDropdown::class);
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
        $this->commands(Commands\UpdateCommand::class);
        $this->commands(Commands\ControllersCommand::class);
        
        $this->commands(Commands\DataRowsSeedCommand::class);
        $this->commands(Commands\MenuItemsSeedCommand::class);
        $this->commands(Commands\SettingsSeedCommand::class);
        $this->commands(Commands\TranslateCommand::class);
        
    }
    
    /**
     * Set socialite configs from admin panel settings
     */
    private function socialite($apis = [])
    {   
        // facebook auth already exists
        if(!empty($this->app['config']['services']['facebook'])) 
        {
            // activate facebook login
            $this->is_facebook = true;
        }
        else
        {
            // facebook auth not exists in app
            // check if facebook app credentials exists in settings

            // setting('apps.facebook_app_id')
            // setting('apps.facebook_app_secret')
            if(!empty($apis['facebook_app_id']) && !empty($apis['facebook_app_secret']))
            {
                $this->app['config']->set('services.facebook', [
                    'client_id' =>  $apis['facebook_app_id'],
                    'client_secret' => $apis['facebook_app_secret'],
                    'redirect' => eventmie_url('login/facebook/callback'),
                    
                ]);

                // activate facebook login
                $this->is_facebook = true;
            }
        }

        // google auth already exists
        if(!empty($this->app['config']['services']['google'])) 
        {
            // activate google login
            $this->is_google = true;
        }
        else
        {
            // google auth not exists in app
            // check if google app credentials exists in settings

            // setting('apps.google_app_id')
            // setting('apps.google_app_secret')
            if(!empty($apis['google_client_id']) && !empty($apis['google_client_secret']))
            {
                $this->app['config']->set('services.google', [
                    'client_id' =>  $apis['google_client_id'],
                    'client_secret' => $apis['google_client_secret'],
                    'redirect' => eventmie_url('login/google/callback'),
                    
                    
                ]);
                
                // activate google login
                $this->is_google = true;
            }
        }

        $data  = [
            'is_google'     => $this->is_google,
            'is_facebook'   => $this->is_facebook,
        ];
        
        View::share('apis', $data);
    }
   
    /**
     *  Set mail configs from admin panel settings
     */
    private function mailConfiguration($mail = [])
    {
        // defaults
        $MAIL_MAILER        = 'smtp';
        $MAIL_HOST          = 'smtp.mailtrap.io';
        $MAIL_PORT          = '2525';
        $MAIL_USERNAME      = '666ec0af4b63db';
        $MAIL_PASSWORD      = '1a2b9dd547ac7f';
        $MAIL_ENCRYPTION    = 'tls';
        $MAIL_FROM_ADDRESS  = 'no-reply@classiebit.com';
        $MAIL_FROM_NAME     = "EventmiePro@classiebit";
        if(
            !empty($mail['mail_host']) && 
            !empty($mail['mail_port']) && 
            !empty($mail['mail_driver']) && 
            !empty($mail['mail_sender_email']) && 
            !empty($mail['mail_sender_name']) && 
            !empty($mail['mail_username']) && 
            !empty($mail['mail_password'])  
        )
        {
            $MAIL_MAILER        = $mail['mail_driver'];
            $MAIL_HOST          = $mail['mail_host'];
            $MAIL_PORT          = $mail['mail_port'];
            $MAIL_USERNAME      = $mail['mail_username'];
            $MAIL_PASSWORD      = $mail['mail_password'];
            $MAIL_ENCRYPTION    = $mail['mail_encryption'];
            $MAIL_FROM_ADDRESS  = $mail['mail_sender_email'];
            $MAIL_FROM_NAME     = $mail['mail_sender_name'];
        }   
        
        Config::set('mail.driver', $MAIL_MAILER);
        Config::set('mail.host', $MAIL_HOST);
        Config::set('mail.port', $MAIL_PORT);
        Config::set('mail.username', $MAIL_USERNAME);
        Config::set('mail.password', $MAIL_PASSWORD);
        Config::set('mail.encryption', $MAIL_ENCRYPTION);
        Config::set('mail.from', ['address' => $MAIL_FROM_ADDRESS, 'name' =>  $MAIL_FROM_NAME]);
    }
     
    /**
     * Regional settings
     * Timezone & Language
     * 
     * Set regional settings from admin panel settings
    */
    private function setRegional($regional = [])
    {
        // set server side timezone
        Config::set('app.timezone', $regional['timezone_default']);

        // change Frontend & Admin Panel language
        $default_lang = config('eventmie.default_lang');
        $this->app->setLocale($default_lang);
    }

}
