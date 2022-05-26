<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $guarded = [];
    
    public function get_taxes()
    {
        $result = Tax::where(['status' => 1, 'admin_tax' => 0])->get();
        return to_array($result);
    }

    public function get_admin_taxes()
    {
        return Tax::where(['status' => 1, 'admin_tax' => 1])->get();
        
    }
}
