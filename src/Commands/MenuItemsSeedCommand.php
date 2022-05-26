<?php

namespace Classiebit\Eventmie\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Classiebit\Eventmie\Traits\Seedable;
use Classiebit\Eventmie\EventmieServiceProvider;


class MenuItemsSeedCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eventmie-pro:menuitemsseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Internal use only';

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
        ];
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
        $this->info('starting generating seeds process...');

        $this->genseed($filesystem);
        
    }

    private function genseed(Filesystem $filesystem)
    {
        $this->info('Testing');
        
        $data  = $this->getdata(); 

        $file  = app_path('test.php');
        $write = file_get_contents($file);
        $write = '';
        foreach($data as $val)
        {
            $write .= 
            '$menuItem = MenuItem::firstOrNew(['.
                '"menu_id" => $menu->id,'.
                '"title"   => "'.$val['title'].'",'.
                '"url"     => "'.$val['url'].'",'.
                '"route"   => "'.$val['route'].'",'.
            ']);'.
            'if (!$menuItem->exists) {'.
                '$menuItem->fill(['.
                    '"target"     => "'.$val['target'].'",'.
                    '"icon_class" => "'.$val['icon_class'].'",'.
                    '"color"      => "'.$val['color'].'",'.
                    '"parent_id"  => "'.($val['parent_id'] ? $val['parent_id'] : null).'",'.
                    '"order"      => "'.$val['order'].'",'.
                '])->save();'.
            '}';
        }
        
        file_put_contents($file, $write);
        
    }

    private function getdata()
    {
        return [
            
        ];
    }
    
}
