<?php

namespace Classiebit\Eventmie\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Classiebit\Eventmie\Traits\Seedable;

use Classiebit\Eventmie\EventmieServiceProvider;
use Facades\Classiebit\Eventmie\Eventmie;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eventmie-pro:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eventmie Pro Installer';

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Initializing installation process...');

        // verify installation
        // get domain name
        $domain      = parse_url(request()->root())['host']; 
        $s_host      = \Request::ip(); 
        $license_key = $this->ask('Enter Your license_key');
        if($this->confirm('Do you wish to continue?'))
        {
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $response = $client->request('POST', 'https://cblicense.classiebit.com/verifyl', [
                'form_params' => [
                    'domain'        => $domain,
                    's_host'        => $s_host,
                    'code'          => "CBEVMPRO01",
                    'license_key'   => $license_key
                ]
            ]);
            $response = json_decode($response->getBody()->getContents());
            if(!empty($response))
            {   
                if($response->status)
                {
                    $this->info('License verified, installing...');
                    $this->install($filesystem);
                }
                else
                {
                    $this->info('License verification failed.');
                }    
            }
            else
            {
                $this->info('License verification failed.');
            }    
        }
        else
        {
            $this->info('Installation abort.');
        }
        
    }

    private function install(Filesystem $filesystem)
    {
        // 1. Publish the core assets defined in the EventmieServiceProvider
        $this->info('1. Publishing Eventmie Pro core assets: config, languages & dummy content');
        $this->call('vendor:publish', ['--provider' => EventmieServiceProvider::class]);

        // 2. Run Eventmie migrations
        $this->info('2. Migrating the Eventmie Pro database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);
        
        // 3. Extend App\User to Eventmie user model
        $this->info('3. Attempting to set Eventmie Pro User model as parent to App\User');
        if (file_exists(app_path('User.php')) || file_exists(app_path('Models/User.php'))) {
            $userPath = file_exists(app_path('User.php')) ? app_path('User.php') : app_path('Models/User.php');

            $str = file_get_contents($userPath);

            if ($str !== false) {
                $str = str_replace('extends Authenticatable', "extends \Classiebit\Eventmie\Models\User", $str);

                file_put_contents($userPath, $str);
            }
        } else {
            $this->warn('Unable to locate "User.php" in app or app/Models.  Did you move this file?');
            $this->warn('You will need to update this manually.  Change "extends Authenticatable" to "extends \Classiebit\Eventmie\Models\User" in your User model');
        }

        // ---- Check if everything good so far ----
        $this->info('---- Dumping the autoloaded files and reloading all new files ----');
        $composer = $this->findComposer();
        $process = new Process([$composer.' dump-autoload']);
        // Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setTimeout(null); 
        $process->setWorkingDirectory(base_path())->run();

        // 4. Add Eventmie Route
        $this->info('4. Adding Eventmie Pro routes to your application routes/web.php');
        $routes_contents = $filesystem->get(base_path('routes/web.php'));
        if (false === strpos($routes_contents, 'Eventmie::routes()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nEventmie::routes();\n"
            );
        }

        // 5. Run database seeder
        $this->info('5. Running Eventmie Pro database seeders');
        $this->seed('EventmieDatabaseSeeder');

        // 6. Add storage symlink
        $this->info('6. Adding the storage symlink to your public folder');
        $this->call('storage:link');
        
        $version = Eventmie::getVersion();
        $this->info("Congrats! Eventmie Pro version $version installed successfully! Best of Luck!");
    }
    
}
