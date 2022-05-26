<?php

namespace Classiebit\Eventmie\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Classiebit\Eventmie\Models\Booking;

class Commission extends Model
{
    protected $guarded = [];

    // add commission in commission table
    public function add_commission($params = [])
    {
        return Commission::insert($params);
    }

    // edit commision table status when change booking table status change by organiser
    public function edit_commission($params)
    {
        $commission = Commission::
                        where([
                            'organiser_id'  => $params['organiser_id'], 
                            'booking_id'    => $params['booking_id'], 
                        ])->first();
        
        // commission is optional so some bookings have no commission so no update then return true                
        // if have no commission in commision in commission tabel, will return true because commission is optional 
        if(empty($commission))
            return true;

        $booking = Booking::where(['id' => $params['booking_id'] ])->first();

        if($booking->is_paid == 0 || $booking->booking_cancel == 2 || $booking->booking_cancel == 3 )
        {
                
            return Commission::
            where([
                'organiser_id'  => $params['organiser_id'], 
                'booking_id'    => $params['booking_id'], 
            ])
            ->update(['status' => 0]);
        }

        else
        {
            return Commission::
            where([
                'organiser_id'  => $params['organiser_id'], 
                'booking_id'    => $params['booking_id'], 
            ])
            ->update(['status' => 1]);
        }

    }

    // show all commisssion of organisers for admin
    public function admin_commission()
    {
        $mode           = config('database.connections.mysql.strict');

        $table          = 'commissions'; 
        $query          = DB::table($table);

        if(!$mode)
        {
            // safe mode is off
            $select = array(
                           
                            "$table.organiser_id as org_id",
                            DB::raw("(SELECT U.name FROM users U WHERE U.id = org_id) as organiser_name"),
                            DB::raw("SUM($table.customer_paid) as customer_paid_total"),
                            DB::raw("SUM($table.admin_commission) as admin_commission_total"),
                            DB::raw("SUM($table.admin_tax) as admin_tax_total"),
                            DB::raw("SUM($table.organiser_earning) as organiser_earning_total"),
                            "$table.updated_at",
                        );
        }
        else
        {
            // safe mode is on
            $select = array(
                            DB::raw("ANY_VALUE($table.organiser_id) as org_id"),
                            DB::raw("(SELECT U.name FROM users U WHERE U.id = org_id) as organiser_name"),
                            DB::raw("SUM($table.customer_paid) as customer_paid_total"),
                            DB::raw("SUM($table.admin_commission) as admin_commission_total"),
                            DB::raw("SUM($table.admin_tax) as admin_tax_total"),
                            DB::raw("SUM($table.organiser_earning) as organiser_earning_total"),
                            DB::raw("ANY_VALUE($table.updated_at) as updated_at"),
                        );
        }
        
        $result = $query->select($select)
                        ->where(['status'=>1])
                        ->groupBy("org_id")
                        ->get();
                        
        return to_array($result);
    }

    // show  commission organisers and month_year wise for admin
    public function show_commission_organisers_wise($organiser_id = null, $params = [], $refunds = false)
    {
        $mode           = config('database.connections.mysql.strict');
        $table          = 'commissions'; 
        $query          = DB::table($table);
        
        
        if(!$mode)
        {
            // safe mode is off
            $select = array(
                           
                            "$table.organiser_id as org_id",
                            "$table.transferred",
                            "$table.settled",
                            "$table.status",
                            "$table.month_year",
                            "$table.event_id",
                            DB::raw("(SELECT U.name FROM users U WHERE U.id = org_id) as organiser_name"),
                            DB::raw("(SELECT E.title FROM events E WHERE E.id = $table.event_id) as event_name"),
                            DB::raw("(SELECT E.slug FROM events E WHERE E.id = $table.event_id) as event_slug"),
                            DB::raw("SUM($table.customer_paid) as customer_paid_total"),
                            DB::raw("SUM($table.admin_commission) as admin_commission_total"),
                            DB::raw("SUM($table.admin_tax) as admin_tax_total"),
                            DB::raw("SUM($table.organiser_earning) as organiser_earning_total"),
                            "$table.updated_at",
                        );
        }
        else
        {
            
            // safe mode is on
            $select = array(
                            DB::raw("ANY_VALUE($table.organiser_id) as org_id"),
                            DB::raw("ANY_VALUE($table.transferred) as transferred"),
                            DB::raw("ANY_VALUE($table.settled) as settled"),
                            DB::raw("ANY_VALUE($table.status) as status"),
                            DB::raw("ANY_VALUE($table.month_year) as month_year"),
                            DB::raw("ANY_VALUE($table.event_id) as event_id"),
                            DB::raw("(SELECT U.name FROM users U WHERE U.id = org_id) as organiser_name"),
                            DB::raw("(SELECT E.title FROM events E WHERE E.id = $table.event_id) as event_name"),
                            DB::raw("(SELECT E.slug FROM events E WHERE E.id = $table.event_id) as event_slug"),
                            DB::raw("SUM($table.customer_paid) as customer_paid_total"),
                            DB::raw("SUM($table.admin_commission) as admin_commission_total"),
                            DB::raw("SUM($table.admin_tax) as admin_tax_total"),
                            DB::raw("SUM($table.organiser_earning) as organiser_earning_total"),
                            DB::raw("ANY_VALUE($table.updated_at) as updated_at"),
                        );
        }
        
        $query->select($select)->where("$table.organiser_id", $organiser_id);

        // in case of refunds, show commissions with status = 0
        if($refunds) 
        {
            $query->where("$table.status", 0);
            $query->where("$table.transferred", 1);
        }
        else 
        {
            $query->where("$table.status", 1);
        }

        $query->groupBy(["$table.event_id", "$table.month_year", "$table.transferred"])
        ->orderBy("$table.month_year", 'DESC')
        ->orderBy("updated_at", 'DESC');

        // in case of searching by between two dates
        if(!empty($params['start_date']) && !empty($params['end_date']))
        {
            $query ->where("$table.created_at", '>=' , $params['start_date']);
            $query ->where("$table.created_at", '<=' , $params['end_date']);
        }
        
        // in case of searching by start_date
        if(!empty($params['start_date']) && empty($params['end_date']))
            $query ->where("$table.created_at", $params['start_date']);

        // in case of searching by event_id
        if($params['event_id'] > 0)
            $query->where('event_id', $params['event_id']);

        if($params['is_paginate'])
            return $query->paginate(10);
        
        $result = $query->get();
        return to_array($result);
    }

    public function admin_edit_commission($params)
    {
        return Commission::
        where([
            'organiser_id'  => $params['organiser_id'], 
            'month_year'    => $params['month_year'], 
            'event_id'      => $params['event_id'],
            // change transferred status of only enabled commissions
            'status'        => 1,
            
        ])
        ->update(['transferred' => $params['transferred']]);
    }
    
    public function admin_edit_settlement($params)
    {
        return Commission::where([
            'organiser_id'  => $params['organiser_id'], 
            'month_year'    => $params['month_year'], 
            'event_id'      => $params['event_id']
        ])
        ->where('status', 0)
        ->update(['settled' => $params['settled']]);
    }

    
    // organiser_total_earning
    public function organiser_total_earning($organiser_id = null)
    {
            
        return Commission::select([
                        DB::raw("SUM(customer_paid) as customer_paid_total"),
                        DB::raw("SUM(admin_commission) as admin_commission_total"),
                        DB::raw("SUM(admin_tax) as admin_tax_total"),
                        DB::raw("SUM(organiser_earning) as organiser_earning_total"),
                    ])->where([
                        'organiser_id' => $organiser_id,
                        "status" => 1,
                    ])->first();
        
    }

    // update event_id only when upgrading to v1.3.x
    public static function update_event_id()
    {
        $commission = Commission::where(['event_id' => null])->get();
        if($commission->isNotEmpty())
        {
            foreach($commission as $key => $value)
            {   
                if(empty($value->event_id))
                {
                    $booking = Booking::where(['id' => $value->booking_id])->first();
                    if(!empty($booking))
                    {
                        Commission::where(['booking_id' => $booking->id])->update(['event_id' => $booking->event_id]);
                    }
                    else
                    {
                        // delete commission if no booking found
                        Commission::where(['booking_id' => $booking->id])->delete();
                    }
                }
            }
        }    
    }

    // update admin_commission only when upgrading to v1.3.x
    public static function update_commission_calculation()
    {
        $commission = Commission::get();
        if($commission->isNotEmpty())
        {
            foreach($commission as $key => $value)
            {   
                // if customer_paid > 0 then update admin_commission
                if($value->customer_paid > 0)
                {
                    Commission::where(['id' => $value->id])->update(['admin_commission' => $value->customer_paid - $value->organiser_earning]);
                }
                else
                {
                    // delete commission if in case of free ticket
                    Commission::where(['id' => $value->id])->delete();
                }
            }
        }    
    }

    
}
