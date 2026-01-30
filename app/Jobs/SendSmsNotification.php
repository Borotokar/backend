<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kavenegar\KavenegarApi;
use Illuminate\Support\Facades\DB;
use App\Models\expert;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Kavenegar;


class SendSmsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $expert;

    public function __construct($expert)
    {
        $this->expert = $expert;
    }

    public function handle()
{
    $this->sendSms($this->expert);
}

private function sendSms($expert)
{
    Log::info('New orders query started');

    try {
        try {
                $res = [$expert->phone_number];
                $message = "سفارشات جدید در اپلیکیشن بروتوکار دارید . 👍";
                $result = Kavenegar::Send("9982001368", $res, $message);  
            } catch (\Kavenegar\Exceptions\ApiException $e) {
                $error = $e->errorMessage();
            } catch (\Kavenegar\Exceptions\HttpException $e) {
                $error = $e->errorMessage();
            }
        
    } catch (\Throwable $th) {
        Log::error('Job error in SendSmsNotification: ' . $th->getMessage(), [
        'line' => $th->getLine(),
        'file' => $th->getFile(),
        'trace' => $th->getTraceAsString(),
    ]);
    }
}    	

}
