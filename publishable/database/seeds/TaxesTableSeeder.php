<?php

use Illuminate\Database\Seeder;
use Classiebit\Eventmie\Models\Tax;

class TaxesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
    
        $tax = $this->tax('id', 1);
        if (!$tax->exists) 
        {
            $tax->fill([
                'title' => 'Convenience Fee',
                'rate_type' => 'percent',
                'rate' => '5.00',
                'net_price' => 'excluding',
                'status' => 1,
            ])->save();
        }
    }

    protected function tax($field, $for)
    {
        return Tax::firstOrNew([$field => $for]);
    }
}