<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class EnrollmentsChart extends ApexChartWidget
{
    protected static ?string $heading = 'Inscrições';
    protected static ?string $subheading = 'Filtre por Período';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $contentHeight = 200; //px
    protected bool $showResetButton = false;

    // Acessa os dados do formulário de filtro
    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(Carbon::now()->subDays(6)), // Define o padrão para o início do ano atual
            DatePicker::make('date_end')
                ->default(Carbon::now()->endOfDay()), // Define o padrão para o final do dia atual
        ];
    }

    protected function getOptions(): array
    {
        // Pega os dados dos filtros
        $startDate = $this->filterFormData['date_start'] ?? Carbon::now()->startOfYear();
        $endDate = $this->filterFormData['date_end'] ?? Carbon::now()->endOfDay();

        $enrollmentsData = $this->getEnrollmentsData($startDate, $endDate);

        return [
            'chart' => [
                'type' => 'line',
                'height' => 200,
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Inscrições',
                    'data' => $enrollmentsData['data'],
                ],
            ],
            'xaxis' => [
                'categories' => $enrollmentsData['categories'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    private function getEnrollmentsData($startDate, $endDate): array
    {
        // Se as datas forem nulas, define um período padrão
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfYear();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        // Garante que o período de tempo é positivo e as datas estão na ordem correta
        if ($end->lessThan($start)) {
            [$start, $end] = [$end, $start];
        }

        $daysDiff = $start->diffInDays($end);
        $data = array_fill(0, $daysDiff + 1, 0);
        $categories = [];

        $currentDate = $start->copy();
        for ($i = 0; $i <= $daysDiff; $i++) {
            $categories[] = $currentDate->format('d/m');
            $currentDate->addDay();
        }

        $enrollments = Enrollment::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        foreach ($enrollments as $enrollment) {
            $date = Carbon::parse($enrollment->date);
            $index = $start->diffInDays($date);
            if ($index >= 0 && $index <= $daysDiff) {
                $data[$index] = $enrollment->total;
            }
        }

        return [
            'data' => $data,
            'categories' => $categories,
        ];
    }
}
