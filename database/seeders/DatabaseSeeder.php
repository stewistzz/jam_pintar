<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\RecommendationSeeder;
use Database\Seeders\TestAttemptSeeder;
use Database\Seeders\FeedbackQuestionSeeder;
use Database\Seeders\FeedbackAnswerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            CitiesTableSeeder::class,
            UserSeeder::class,
            ContentSeeder::class,
            QuestionSeeder::class,
            RecommendationSeeder::class,
            TestAttemptSeeder::class,
            FeedbackQuestionSeeder::class,
            FeedbackAnswerSeeder::class,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
