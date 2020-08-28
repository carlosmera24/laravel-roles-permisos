<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
                            'name'      => 'Usuario Demo',
                            'email'     => 'user@demo.com',
                            'password'     => bcrypt('1234abcd'),

                        ]);

        factory(App\User::class, 7)->create();
    }
}
