<?php

namespace App\Console\Commands;
use App\Models\Order;
use Morilog\Jalali\Jalalian;
use Illuminate\Console\Command;

class UpdateExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-expired';

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
        $today = Jalalian::now()->format('Y-m-d');
    
    Order::where('completion_date', '<', $today)
        ->whereNotIn('status', [3, 4, 5])
        ->update(['status' => 5]);
    
    // Order::where('completion_date', '<', $today)
    //     ->where('status', 3)
    //     ->update(['status' => 4]);
    
    $this->info('Orders with expired completion dates have been updated.' . $today);
    } 
    
    
}
