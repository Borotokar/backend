<?php

namespace App\Traits;
use App\Models\Admin;
use App\Models\Answer;
use App\Models\Bid;
use App\Models\expert;
use App\Models\ExpertAppSetting;
use App\Models\ExpertDocuments;
use App\Models\ExpertGallery;
use App\Models\Order;
use App\Models\Question;
use App\Models\Review;
use App\Models\Service;
use App\Models\Servicecategory;
use App\Models\ServiceType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAppSetting;
use App\Models\Wallet;
use App\Models\Notification;

trait Notifiable
{   
    public static function bootNotifiable()
    {
        static::created(function ($model) {
	if (get_class($model) === User::class) {
    $msg = "کاربر {$model->name} ایجاد شد";
} elseif (get_class($model) === Order::class) {
    $msg = "سفارش {$model->service->title} توسط {$model->user->name} ایجاد شد.";
} elseif (get_class($model) === expert::class) {
    $msg = "متخصص با نام و نام خانوادگی {$model->first_name} {$model->last_name} ایجاد شد.";
} elseif (get_class($model) === ExpertGallery::class) {
    $msg = "متخصص با نام و نام خانوادگی " . $model->expert->first_name . " " . $model->expert->last_name . " در گالری خود یک عکس اضافه کرد.";
} elseif (get_class($model) === ExpertDocuments::class) {
    $msg = "متخصص با نام و نام خانوادگی {$model->expert->first_name} {$model->expert->last_name} یک مدرک به مستندات خود اضافه کرد.";
} elseif (get_class($model) === Transaction::class) {
    $msg = "متخصص با نام و نام خانوادگی {$model->expert->first_name} {$model->expert->last_name} یک تراکنش انجام داد.";
} else {
    $msg = "یک تغییری ایجاد شد.";
}
    
        Notification::create([
                'type' => 'created',
                'model' => get_class($model),
                'model_id' => $model->id,
                'message' => $msg,
            ]);
        });

        static::updated(function ($model) {
	            if (get_class($model) === User::class) {
            $msg = "کاربر {$model->name} بروز شد";
        } elseif (get_class($model) === Order::class) {
            $msg = "سفارش {$model->service->title} توسط {$model->user->name} بروز شد.";
        } elseif (get_class($model) === expert::class) {
            $msg = "متخصص با نام و نام خانوادگی {$model->first_name} {$model->last_name} بروز شد.";
        } elseif (get_class($model) === ExpertGallery::class) {
            $msg = "متخصص با نام و نام خانوادگی " . $model->expert->first_name . " " . $model->expert->last_name . " در گالری خود یک عکس اضافه کرد.";
        } elseif (get_class($model) === ExpertDocuments::class) {
            $msg = "متخصص با نام و نام خانوادگی {$model->expert->first_name} {$model->expert->last_name} یک مدرک به مستندات خود اضافه کرد.";
        } elseif (get_class($model) === Transaction::class) {
            $msg = "متخصص با نام و نام خانوادگی {$model->expert->first_name} {$model->expert->last_name} یک تراکنش انجام داد.";
        } else {
            $msg = "یک تغییری ایجاد شد.";
        }
            Notification::create([
                'type' => 'updated',
                'model' => get_class($model),
                'model_id' => $model->id,
                'message' => $msg,
            ]);
        });

        static::deleted(function ($model) {
	            if (get_class($model) === User::class) {
            $msg = "کاربر {$model->name} حذف شد";
        } elseif (get_class($model) === Order::class) {
            $msg = "سفارش {$model->service->title} توسط {$model->user->name} حذف شد.";
        } elseif (get_class($model) === expert::class) {
            $msg = "متخصص با نام و نام خانوادگی {$model->first_name} {$model->last_name} حذف شد.";
        } elseif (get_class($model) === ExpertGallery::class) {
            $msg = "متخصص با نام و نام خانوادگی " . $model->expert->first_name . " " . $model->expert->last_name . " در گالری خود یک عکس حذف کرد.";
        } elseif (get_class($model) === ExpertDocuments::class) {
            $msg = "متخصص با نام و نام خانوادگی {$model->expert->first_name} {$model->expert->last_name} یک مدرک به مستندات خود حذف کرد.";
        } elseif (get_class($model) === Transaction::class) {
            $msg = "متخصص با نام و نام خانوادگی {$model->expert->first_name} {$model->expert->last_name} یک تراکنش انجام داد.";
        } else {
            $msg = "یک تغییری ایجاد شد.";
        }
            Notification::create([
                'type' => 'deleted',
                'model' => get_class($model),
                'model_id' => $model->id,
                'message' => $msg,
            ]);
        });
    }
}

