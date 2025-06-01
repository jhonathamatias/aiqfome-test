<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Model\User::create([
            'email' => 'admin@admin.com',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
        ]);
    }
}
