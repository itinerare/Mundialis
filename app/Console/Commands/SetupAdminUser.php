<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App;
use Carbon\Carbon;
use App\Services\UserService;
use App\Models\User\User;
use App\Models\User\Rank;

class SetupAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the admin user account if no users exist, or resets the password if it does.';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('********************');
        $this->info('* ADMIN USER SETUP *');
        $this->info('********************'."\n");

        // First things first, check if user ranks exist...
        if(!Rank::count()) {

            // Create ranks if not already present.
            // A light-weight rank system is used here since the site is intended
            // only for individuals or small groups/granular permissions are not
            // necessary.
            $adminRank = Rank::create([
                'name' => 'Admin',
                'description' => 'The site admin. Has the ability to view/edit any data on the site.',
                'sort' => 2
            ]);

            Rank::create([
                'name' => 'Editor',
                'description' => 'A member of the site with write permissions.',
                'sort' => 1
            ]);

            Rank::create([
                'name' => 'Member',
                'description' => 'A regular member of the site.',
                'sort' => 0
            ]);

            $this->line("User ranks not found. User ranks created.");
        }
        // Otherwise, grab the rank with the highest "sort" value. (This is the admin rank.)
        else {
            $adminRank = Rank::orderBy('sort', 'DESC')->first();
        }

        // Check if the admin user exists...
        $user = User::where('rank_id', $adminRank->id)->first();
        if(!$user) {

            $this->line('Setting up admin account. This account will have access to all site data and recovery features for any other user accounts. Please make sure to keep the email and password secret!');
            $name = $this->anticipate('Username', ['Admin', 'System']);
            $email = $this->ask('Email Address');
            $password = $this->secret('Password (hidden)');

            $this->line("\nUsername: ".$name);
            $this->line("Email: ".$email);
            $confirm = $this->confirm("Proceed to create account with this information?");

            // If env variables indicate a local instance, double-check
            if(App::environment('local')) {
                if(!$this->confirm('Are you on a local or testing instance and not a live site?')) {
                    $this->info('Please adjust your APP_ENV to Production and APP_DEBUG to false in your .env file before continuing set-up!');
                    return;
                }
            }

            if($confirm) {
                $service = new UserService;
                $user = $service->createUser([
                    'name' => $name,
                    'email' => $email,
                    'rank_id' => $adminRank->id,
                    'password' => $password
                ]);
                $user->email_verified_at = Carbon::now();
                $user->save();

                $this->line('Admin account created. You can now log in with the registered email and password.');
                $this->line('If necessary, you can run this command again to change the email address and password of the admin account.');
                return;
            }
        }
        else {
            // Change the admin email/password.
            $this->line('Admin account [' . $user->name . '] already exists.');
            if($this->confirm("Reset email address and password for this account?")) {
                $email = $this->ask('Email Address');
                $password = $this->secret('Password (hidden)');

                $this->line("\nEmail: ".$email);
                if($this->confirm("Proceed to change email address and password?")) {
                    $service = new UserService;
                    $service->updateUser([
                        'id' => $user->id,
                        'email' => $email,
                        'password' => $password
                    ]);

                    $this->line('Admin account email and password changed.');

                }
            }

            // If env variables indicate a local instance, double-check
            if(App::environment('local')) {
                if(!$this->confirm('Are you on a local or testing instance and not a live site?')) {
                    $this->info('Please adjust your APP_ENV to Production and APP_DEBUG to false in your .env file before continuing set-up!');
                    return;
                }

                $this->line('Marking email address as verified...');
                $user->email_verified_at = Carbon::now();
                $user->save();

                $this->line('Updates complete.');
            }
            return;
        }
        $this->line('Action cancelled.');

    }
}
