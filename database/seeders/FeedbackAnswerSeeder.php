<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $feedbackQuestions = Question::where('question_type', 'feedback')
            ->orderBy('id')
            ->get()
            ->keyBy('question_text');

        $sampleAnswers = [
            1 => [
                'Seberapa cocok hasil tes kepribadian dengan Anda?' => 'Cocok',
                'Seberapa puas Anda dengan rekomendasi yang diberikan?' => 'Puas',
                'Apakah instruksi dan penjelasan hasil mudah dipahami?' => 'Mudah',
                'Bagaimana penilaian Anda terhadap pengalaman/tampilan aplikasi saat mengikuti tes?' => 'Baik',
                'Apakah Anda akan merekomendasikan fitur tes ini kepada teman atau kerabat?' => 'Mungkin',
                'Apa saran, kritik, atau fitur baru yang ingin Anda sampaikan untuk pengembangan Jam Pintar ke depannya?' => 'Tampilan sudah baik, tetapi akan lebih bagus jika ada ringkasan hasil yang lebih detail.',
            ],
            2 => [
                'Seberapa cocok hasil tes kepribadian dengan Anda?' => 'Sangat Cocok',
                'Seberapa puas Anda dengan rekomendasi yang diberikan?' => 'Sangat Puas',
                'Apakah instruksi dan penjelasan hasil mudah dipahami?' => 'Sangat Mudah',
                'Bagaimana penilaian Anda terhadap pengalaman/tampilan aplikasi saat mengikuti tes?' => 'Sangat Baik',
                'Apakah Anda akan merekomendasikan fitur tes ini kepada teman atau kerabat?' => 'Pasti Merekomendasikan',
                'Apa saran, kritik, atau fitur baru yang ingin Anda sampaikan untuk pengembangan Jam Pintar ke depannya?' => 'Sudah bagus, semoga ada fitur notifikasi pengingat belajar.',
            ],
        ];

        foreach ($sampleAnswers as $testAttemptId => $answers) {
            foreach ($answers as $questionText => $answer) {
                $question = $feedbackQuestions->get($questionText);

                if (! $question) {
                    continue;
                }

                DB::table('answers')->updateOrInsert(
                    [
                        'question_id' => $question->id,
                        'test_attempt_id' => $testAttemptId,
                    ],
                    [
                        'answer' => $answer,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}