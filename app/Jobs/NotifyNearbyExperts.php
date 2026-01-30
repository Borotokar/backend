<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\expert;
use App\Services\ExpertFirebaseNotificationService;
use Kavenegar\KavenegarApi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Kavenegar;


class NotifyNearbyExperts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected  $lat;
    protected  $log;
    protected  $blockedExpertIds;
    protected  $serviceId;

    public function __construct( $lat,  $log,  $blockedExpertIds,  $serviceId)
    {
        $this->lat = $lat;
        $this->log = $log;
        $this->blockedExpertIds = $blockedExpertIds;
        $this->serviceId = $serviceId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $experts = expert::whereNotIn('id', $this->blockedExpertIds)->whereHas('services', function($query) {
                $query->where('service_id', $this->serviceId);
            })->get();

        foreach ($experts as $expert) {
            $distance = $this->haversineGreatCircleDistance($this->lat, $this->log, $expert->lat, $expert->log);
            
            if ($distance <= 50 && $expert->sms_notification) {
                // اضافه کردن job به صف
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

            try {
                if($distance <= 50 && $expert->notification){
                    if (!is_null($expert->fcm_token)) {
                            $token = $expert->fcm_token;
                            $fcm = new ExpertFirebaseNotificationService();
                            $fcm->send(
                                $token,
                                'سفارش جدید',
                                'یک سفارش جدید در اپلیکیشن بروتوکار دارید . 👍',
                                ['customData' => '123']
                            );
                    }
                }
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {

            }
        }
    }

    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371; // شعاع زمین به کیلومتر

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // فاصله به کیلومتر
    }
}
