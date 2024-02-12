<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('Name');
        $user['email'] = $this->ask('Email');
        $user['password'] = $this->secret('Password');

        $roleName = $this->choice('Role', Role::get()->pluck('name')->toArray());
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error('Role not found');

            return -1;
        }

        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return -1;
        }

        $token = DB::transaction(function () use ($user, $role) {
            $user['password'] = bcrypt($user['password']);
            $newUser = User::create($user);
            $newUser->role()->associate($role->id);
            $newUser->save();
            return $newUser->createToken(config('app.name'));
        });

        $this->info('User created successfully. Bear token: '. $token->plainTextToken);
    }
}
