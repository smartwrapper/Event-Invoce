<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use DB;


class Transaction extends Model
{
    protected $guarded = [];
    
    // insert data of paypal transaction_id into transaction table
    public function add_transaction($params = [])
    {
        return DB::table('transactions')->insertGetId($params);
    }

    // payment information by organiser for this booking
    public function organiser_payment_info($params = [])
    {
        return Transaction::
            where([
                'id'              => $params['transaction_id'], 
                'order_number'    => $params['order_number'], 
            ])
            ->first();  
    }
}
