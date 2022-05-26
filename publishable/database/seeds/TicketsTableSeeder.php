<?php

use Illuminate\Database\Seeder;
use Classiebit\Eventmie\Models\Ticket;

class TicketsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $ticket = $this->ticket('id', 1);
        if (!$ticket->exists) 
        {
            $ticket->fill([
                'title' => 'Free',
                'price' => '0.00',
                'quantity' => 10000,
                'description' => 'Smile spoke total few great had never their too. Amongst moments do in arrived at my replied.',
                'event_id' => 1,
                'status' => 1,
            ])->save();
        }
        
        $ticket = $this->ticket('id', 2);
        if (!$ticket->exists) 
        {
            $ticket->fill([
                'title' => 'Early Bird',
                'price' => '10.00',
                'quantity' => 10000,
                'description' => 'Smile spoke total few great had never their too. Amongst moments do in arrived at my replied.',
                'event_id' => 1,
                'status' => 1,
            ])->save();
        }
        
        $ticket = $this->ticket('id', 3);
        if (!$ticket->exists) 
        {
            $ticket->fill([
                'title' => 'Regular',
                'price' => '20.00',
                'quantity' => 10000,
                'description' => 'Smile spoke total few great had never their too. Amongst moments do in arrived at my replied.',
                'event_id' => 1,
                'status' => 1,
            ])->save();
        }
        
        $ticket = $this->ticket('id', 4);
        if (!$ticket->exists) 
        {
            $ticket->fill([
                'title' => 'VIP',
                'price' => '50.00',
                'quantity' => 10000,
                'description' => 'Smile spoke total few great had never their too. Amongst moments do in arrived at my replied.',
                'event_id' => 1,
                'status' => 1,
            ])->save();
        }
        
    }

    protected function ticket($field, $for)
    {
        return Ticket::firstOrNew([$field => $for]);
    }
}