<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;


class ExpertFirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/service-account2.json'));

        $this->messaging = $factory->createMessaging();
    }

    public function send(string $token, string $title, string $body, array $data = [])
    {
        $androidConfig = AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'sound' => 'customsound',
                'channel_id' => 'high_importance_channel',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'visibility' => 'public',
            ],
        ]);


        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withAndroidConfig($androidConfig)
            ->withData($data);

        return $this->messaging->send($message);
    }
}
