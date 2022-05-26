<?php

namespace Classiebit\Eventmie\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Classiebit\Eventmie\Traits\Seedable;
use Classiebit\Eventmie\EventmieServiceProvider;


class DataRowsSeedCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eventmie-pro:datarowsseed';

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
            '$dataRow            = $this->dataRow($DataType, "'.$val['field'].'");'.
            'if (!$dataRow->exists) {'.
                '$dataRow->fill(['.
                    '"type"         => "'.$val['type'].'",'.
                    '"display_name" => "'.$val['display_name'].'",'.
                    '"required"     => '.$val['required'].','.
                    '"browse"       => '.$val['browse'].','.
                    '"read"       => '.$val['read'].','.
                    '"edit"       => '.$val['edit'].','.
                    '"add"       => '.$val['add'].','.
                    '"delete"       => '.$val['delete'].','.
                    '"details"       => '.(($val['details'] == '{}' || $val['details'] == '[]') ? '"{}"' : $val['details']).','.
                    '"order"       => '.$val['order'].','.
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
