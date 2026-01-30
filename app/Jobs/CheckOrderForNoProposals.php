<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\FirebaseNotificationService;
use App\Models\Order;
use Kavenegar;



class CheckOrderForNoProposals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $threshold = now()->subMinutes(30);

        $orders = Order::where('created_at', '<=', $threshold)
            ->whereDoesntHave('bids') 
            ->whereNull('notified_at') 
            ->get();

        $fcm = new FirebaseNotificationService();

        foreach ($orders as $order) {
            // send notif
            if (!is_null($order->user->fcm_token)) {
            $fcm->send(
                $order->user->fcm_token,
                '💡 هنوز پیشنهادی دریافت نشده؟',
                'برای دریافت سریع‌تر پیشنهاد، لطفاً کمی صبوری کنید یا شرایط سفارش مثل زمان یا قیمت را تغییر دهید تا متخصصین بیشتری جذب شوند.',
                ['customData' => '123']
            );
            }

            // send sms
	        $res = array($order->user->phone_number);
            $message = "💡 هنوز پیشنهادی دریافت نشده؟ \n"."برای دریافت سریع‌تر پیشنهاد، لطفاً کمی صبوری کنید یا شرایط سفارش مثل زمان یا قیمت را تغییر دهید تا متخصصین بیشتری جذب شوند.". "\n borotokar.com | بروتوکار";
            $result = Kavenegar::Send("9982001368", $res, $message);

            // ثبت زمان نوتیف‌دهی تا دوباره ارسال نشه
            $order->update(['notified_at' => now()]);
        }
    }
}
