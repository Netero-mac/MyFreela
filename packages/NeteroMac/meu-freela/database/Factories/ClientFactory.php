<?php

namespace NeteroMac\MeuFreela\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NeteroMac\MeuFreela\Models\Client;
use App\Models\User;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'user_id' => User::factory(),
        ];
    }
}