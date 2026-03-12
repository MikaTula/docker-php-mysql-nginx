<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CmCustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cm:custom-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $test = "11\"11";

        $users = DB::table('users')
            ->where('id','>=', 10 )
            ->whereRaw('price > IF(state = "TX", ?, 100)', [200])
            ->join('contacts', 'users.id', '=', DB::raw($test))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();

        // print_r($user);



    }
}

