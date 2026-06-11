<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\View\View;

class BackofficeController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $newUsersThisMonth = User::where('role', '!=', 'admin')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $charts = [
            [
                'key' => 'kecocokan_hasil_tes',
                'title' => 'Persentase Kesesuaian Hasil Tes',
                'question_id' => 11,
            ],
            [
                'key' => 'kepuasan_rekomendasi',
                'title' => 'Tingkat Kepuasan Rekomendasi',
                'question_id' => 12,
            ],
            [
                'key' => 'kejelasan_instruksi',
                'title' => 'Kejelasan Instruksi',
                'question_id' => 13,
            ],
            [
                'key' => 'pengalaman_tampilan',
                'title' => 'Pengalaman Tampilan Aplikasi',
                'question_id' => 14,
            ],
            [
                'key' => 'tingkat_rekomendasi',
                'title' => 'Tingkat Rekomendasi',
                'question_id' => 15,
            ],
        ];

        $dashboardCharts = collect($charts)->map(function (array $chart) {
            return [
                'key' => $chart['key'],
                'title' => $chart['title'],
                'data' => $this->buildPieChartData($chart['question_id']),
            ];
        })->values();

        return view('pages.backoffice.index', compact('totalUsers', 'newUsersThisMonth', 'dashboardCharts'));
    }

    public function question(): View
    {
        return view('pages.backoffice.question');
    }

    private function buildPieChartData(int $questionId): array
    {
        $question = Question::where('question_type', 'feedback')
            ->whereKey($questionId)
            ->first();

        if (! $question) {
            return [
                'labels' => ['Belum ada data'],
                'values' => [1],
                'colors' => ['#dee2e6'],
            ];
        }

        $optionLabels = is_array($question->option) ? $question->option : [];
        $answerCounts = $question->answers()
            ->whereNotNull('answer')
            ->selectRaw('answer, COUNT(*) as total')
            ->groupBy('answer')
            ->pluck('total', 'answer')
            ->toArray();

        $labels = [];
        $values = [];

        foreach ($optionLabels as $label) {
            $labels[] = $label;
            $values[] = (int) ($answerCounts[$label] ?? 0);
            unset($answerCounts[$label]);
        }

        foreach ($answerCounts as $label => $count) {
            $labels[] = $label;
            $values[] = (int) $count;
        }

        if (array_sum($values) === 0) {
            return [
                'labels' => ['Belum ada data'],
                'values' => [1],
                'colors' => ['#dee2e6'],
            ];
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => $this->buildColorPalette(count($labels)),
        ];
    }

    private function buildColorPalette(int $count): array
    {
        $palette = [
            '#fa5b19',
            '#ff8a5b',
            '#ffb38f',
            '#ffd1be',
            '#3b82f6',
            '#60a5fa',
            '#93c5fd',
            '#38bdf8',
            '#14b8a6',
            '#22c55e',
            '#eab308',
            '#f97316',
        ];

        if ($count <= count($palette)) {
            return array_slice($palette, 0, $count);
        }

        $colors = [];

        for ($i = 0; $i < $count; $i++) {
            $colors[] = $palette[$i % count($palette)];
        }

        return $colors;
    }
}