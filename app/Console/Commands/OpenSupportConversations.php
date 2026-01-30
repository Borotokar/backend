<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SupportConversations;
use App\Models\User;
use App\Models\expert;

class OpenSupportConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:open-conversations';
    protected $description = 'Open a support conversation for all users and experts';

    public function handle()
    {
        SupportConversations::truncate();

        // دریافت کاربران و متخصصین دارای نام
        $users = User::whereNotNull('name')->where('name', '!=', '')->get();
        $experts = expert::whereNotNull('first_name')->where('first_name', '!=', '')->get();
    
        $count = 0;
    
        foreach ($users as $user) {
            SupportConversations::create(['user_id' => $user->id]);
            $count++;
        }
    
        foreach ($experts as $expert) {
            SupportConversations::create(['expert_id' => $expert->id]);
            $count++;
        }
    
        $this->info("✅ $count conversation(s) created for users and experts with valid names.");
    }
}
