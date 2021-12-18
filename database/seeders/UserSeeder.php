<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /**
    * @var int $count
    */
    private int $count;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(int $count = 5, array $attributes = ['password' => 'secret123_!']): void
    {
        // $this->count = $this->command->ask("Please get count", $count);

	$randomCount = random_int(10, 100);
	User::factory()->count($randomCount)->create($attributes);
	echo $randomCount . PHP_EOL;
    }
}
