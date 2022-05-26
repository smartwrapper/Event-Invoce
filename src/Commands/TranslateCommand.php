<?php

namespace Classiebit\Eventmie\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Classiebit\Eventmie\Traits\Seedable;
use Classiebit\Eventmie\EventmieServiceProvider;
use Illuminate\Support\Carbon;

use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Translation;
use TCG\Voyager\Models\MenuItem;


class TranslateCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eventmie-pro:translate-admin-panel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eventmie Pro Admin Panel Translator';

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
        $this->info('Initializing translation process...');

        $this->translate($filesystem);
        
    }

    private function translate(Filesystem $filesystem)
    {
        /* ---- Check if everything good so far ---- */
        $this->info('---- Dumping the autoloaded files and reloading all new files ----');
        $composer = $this->findComposer();
        $process = new Process([$composer.' dump-autoload']);
        // Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setTimeout(null); 
        $process->setWorkingDirectory(base_path())->run();

        // 1. Run admin panel translator
        $this->adminPanelTranslation();
        
        // Finish
        $this->info('Congrats! Eventmie Pro Admin Panel translated successfully!');
    }

    private function adminPanelTranslation()
    {   
        // loop datatype
            // loop langs
                // loop datarows

        // Truncate Translation table
        \DB::table('translations')->truncate();
        $now = Carbon::now();
        
        // 1. Get dataType
        $DataTypes       = DataType::get();
        foreach($DataTypes as $key => $DataType)
        {
            // 2. Get dataRows
            $DataRows       = DataRow::where('data_type_id', $DataType->id)->get();

            // 3. Get Langs
            $langs      = lang_selector();
            foreach($langs as $key_2 => $lang)
            {
                // 4. Prepare data for DataType tranlations 
                $translations   = [];
                $translations[] = [
                    'table_name'    => 'data_types',
                    'column_name'   => 'display_name_singular',
                    'foreign_key'   => $DataType->id,
                    'locale'        => $lang,
                    'value'         => \Lang::get("voyager::generic.$DataType->display_name_singular", [], $lang),
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
                $translations[] = [
                    'table_name'    => 'data_types',
                    'column_name'   => 'display_name_plural',
                    'foreign_key'   => $DataType->id,
                    'locale'        => $lang,
                    'value'         => \Lang::get("voyager::generic.$DataType->display_name_plural", [], $lang),
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];

                foreach($DataRows as $key_3 => $DataRow)
                {
                    // 4. Prepare data for DataRows tranlations
                    $translations[] = [
                        'table_name'    => 'data_rows',
                        'column_name'   => 'display_name',
                        'foreign_key'   => $DataRow->id,
                        'locale'        => $lang,
                        'value'         => \Lang::get("voyager::generic.$DataRow->display_name", [], $lang),
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];    
                }

                // 5. Update translations
                Translation::insert($translations);
            }
        }

        // For MenuItems
        // 1. Get menuItems
        $MenuItems       = MenuItem::get();
        foreach($MenuItems as $key => $MenuItem)
        {
            // 2. Get Langs
            $langs      = lang_selector();
            foreach($langs as $key_2 => $lang)
            {
                // 3. Prepare data for MenuItems tranlations 
                $translations   = [];
                $translations[] = [
                    'table_name'    => 'menu_items',
                    'column_name'   => 'title',
                    'foreign_key'   => $MenuItem->id,
                    'locale'        => $lang,
                    'value'         => \Lang::get("voyager::generic.$MenuItem->title", [], $lang),
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
                
                // 4. Update translations
                Translation::insert($translations);
            }
        }
    }

    
    
}
