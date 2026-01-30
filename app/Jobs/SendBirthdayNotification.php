<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Morilog\Jalali\Jalalian;
use App\Models\expert;
use Illuminate\Support\Facades\Log;
use Kavenegar;

class SendBirthdayNotification implements ShouldQueue
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
        $today = Jalalian::now();
        $month = str_pad($today->getMonth(), 2, '0', STR_PAD_LEFT);
        $day = str_pad($today->getDay(), 2, '0', STR_PAD_LEFT);

        $users = expert::whereRaw("SUBSTRING(birth_date, 6, 5) = ?", ["$month-$day"])->get();

        foreach ($users as $user) {
            // Notification::create([
            //     'title' => 'تولدت مبارک!',
            //     'message' => "{$user->name} عزیز، تولدت مبارک 🎉🌸",
            //     'user_id' => $user->id,
            // ]);
            try {
                $res = array($user->phone_number);
                $message = "{$user->first_name} {$user->last_name} عزیز، \n امروز روز توست! از صمیم قلب تولدت رو تبریک می‌گیم و بابت تمام زحماتی که در کنار ما در بروتوکار کشیدی، ازت سپاسگزاریم. \n امیدواریم سال پیش رو برات پر از موفقیت، سلامتی و لحظاتی پر از لبخند باشه. \n ما همیشه به همراهی با متخصصینی مثل تو افتخار می‌کنیم ❤️ \n با احترام \n تیم بروتوکار";
                $result = Kavenegar::Send("9982001368",$res , $message);
            } catch(\Kavenegar\Exceptions\ApiException $e){
                // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
                $erore = $e->errorMessage();
                Log::error($erore);
            }
        
           catch(\Kavenegar\Exceptions\HttpException $e){
                // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
                $erore = $e->errorMessage();
                Log::error($erore);
            }


        }
    }
}
