<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Individual>
 */
class IndividualFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $civilStatus =  ['Single', 'Married', 'Widowed', 'Other'];
        $gender = ['Male', 'Female'];

        return [
            'firstname' => $this->faker->firstName,
            'middlename' => $this->faker->lastName,
            'lastname' => $this->faker->lastName,
            'address' => $this->faker->address,
            'civilstatus' => $this->faker->randomElement($civilStatus),
            'birthdate' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'gender' =>  $this->faker->randomElement($gender),

        ];
    }
}
