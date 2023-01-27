<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserChat;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserChatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserChat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $employees = User::allEmployees()->pluck('id')->toArray();
        
        $from = $this->faker->randomElement($employees);
        $to = $this->faker->randomElement($employees);
        
        return [
            'message' => $this->faker->realText(200),
            'user_one' => $from,
            'user_id' => $to,
            'from' => $from,
            'to' => $to,
        ];
    }

}
