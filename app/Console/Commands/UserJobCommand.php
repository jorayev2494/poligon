<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UserCreated;
use App\Models\User;
use Illuminate\Console\Command;

class UserJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $users = User::inRandomOrder()
                    ->take(4)
                    ->get();

        foreach ($users as $user) {
            dump($user->id);
            UserCreated::dispatch($user)->onQueue('email');
        }

        return 0;
    }
}
