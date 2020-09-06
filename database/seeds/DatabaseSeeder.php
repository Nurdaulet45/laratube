<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $user1 = factory(\App\User::class)->create([
            'email' => 'John@doe.com'
        ]);

        $user2 = factory(\App\User::class)->create([
            'email' => 'Jane@doe.com'
        ]);


        $channel1 = factory(\App\Channel::class)->create([
            'user_id' => $user1->id
        ]);

        $channel2 = factory(\App\Channel::class)->create([
            'user_id' => $user2->id
        ]);

        $channel1->subscriptions()->create([
            'user_id' =>  $user2->id
        ]);
        $channel2->subscriptions()->create([
            'user_id' =>  $user1->id
        ]);

        factory(\App\Subscription::class, 100)->create([
           'channel_id' => $channel1->id
        ]);
        factory(\App\Subscription::class, 100)->create([
           'channel_id' => $channel2->id
        ]);
    }
}
