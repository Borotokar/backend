<?php

namespace App\Jobs;

use App\Models\AdminLog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogAdminAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $adminId;
    protected $action;
    protected $description;
    protected $ip;

    public function __construct($adminId, $action, $description = null, $ip = null)
    {
        $this->adminId    = $adminId;
        $this->action     = $action;
        $this->description= $description;
        $this->ip         = $ip;
    }

    public function handle()
    {
        AdminLog::create([
            'admin_id'   => $this->adminId,
            'action'     => $this->action,
            'description'=> $this->description,
            'ip'         => $this->ip,
        ]);
    }
}
