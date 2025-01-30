<?php

namespace App\Jobs;

use App\Mail\SendClientMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendClientDetails implements ShouldQueue
{
    use Queueable;
    
    protected $user;
    protected $password;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user,$password)
    {
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new SendClientMail($this->user, $this->password));
    }
}
