<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TreatmentChart extends ApexChartWidget
{

    protected int | string | array $columnSpan = '2';


    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'treatmentChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'No. of Treatments recorded (Daily)';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $chartdaily = DB::table('treatments')
            ->selectRaw('
                COUNT(*) AS ttlCount,
                DATE_FORMAT(created_at, "%b %d") AS dailyLabel
            ')
            ->groupBy('dailyLabel')
            ->orderByDesc('dailyLabel')
            ->limit(15)
            ->get();

        $dailyLabel = null;
        $dataVal = null;

        foreach ($chartdaily as $daily){ $dailyLabel[] = $daily->dailyLabel; }
        foreach ($chartdaily as $data){ $dataVal[] =  $data->ttlCount; }
        //dd($dataVal);


        return [

            'chart' => [
                'type' => 'area',
                'height' => 300,
                'toolbar' => [
                    'show' => true,
                    'offsetX' => 0,
                    'offsetY' => 0,
                    'tools' => [
                        'download' => true,
                        'selection' => true,
                        'zoom' => true,
                        'zoomin' => true,
                        'zoomout' => true,
                        'pan' => true,
                        'reset' =>  '<img alt="reset" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAD7ElEQVR4nO1ZW4scVRA+knhJBMWoiIqoD4loUNB4xQ2zVT277EMEfRjwQSOaiwYSiK6Qma5q2kdF8yZCwAvx8jJodqaqd00QzA9wNQhRH3xJJPECmiisMRo1clo39J7u7PbsTLc7MB/0w8B0dZ061V9952tjBhhggK4RHqwsJ6kOsWBIChOs+BULniDBMywww4JHSHCaBPey4GZuwS1mKYDaIzezwm5W+J4Vz3Z0CR4KBJ8Jp8YuKz3xRhuvIcE3WPCPjhNPLQR+9hV32l0sJXlS7wlSPNl14s5FioeDaPi+whIPm7WLSOGteVrC9vxeu0Bf8K76Pu/KWrO2bPzAyKUUwY2B4nqOcBcpTJ135wROk8CTPU/eJsGKB85XOY7wsR1TYxfnjed/uP5aFiRW+DEzpsCLPa18ZvICM4HidlvlRcfeP7qKBV8jhb8ydpR6soD4ZU1VCb7hlndnTx5gjPEFHyKBXzKe89Sc/yneywqvdpA8PJ4R9FPbAqbHaLTwNlL4YW57wq+s1dUcVW9lwSYL/s2KL+cKaF/CVI8KfusrXt/r5GfRaOMdLsNRPGPgz9nfgQxXTB6Q4B6n+qf8VuVuUzBY8OH5mC7XvKCJ0RtI4XeHGepFJx/uH11FCi+wZtMsKb6XKxALvOQwzrFQNqwsKvFxS9PxjFhwQD66YDBLizZhpw+3mAIRKG4nhePzTmrBM/Vo6IqFg4n3gKtVnm3ev8IUjFqztowir0oK+h/buMPt41yBSNF3FvCuKRms1dV2GidZkMR7Lt/NAvvmrLyNm8z/hFA2rLTDjAU/qU96a3LdFGubxAIC8e4x/QQS+Cm5gFAqV5l+Ajn8b8Wc6SdQ3y9A+r6FcM5LbCWs6SeQ4AeOgNps+gnkDLLcAmqpIHCkhD0pFSnkeo5MMSf4tCkZFEsJfD9o44g5ay7o6GZXTlulWOYuNKxxFh8nz7Xx19yGtbkDZB5oFH1TEljxTYdIjnQ8j9wjJQn+VoYu8hUfcaV0EMHWjgM1poaudodafDJrVa8rrPKT1dtTz1T4fNFqwDpu6XMpfFbEIuqT3hoS/M4p2GnrVHQVmBVeT7sDcCyIYF2vkqfIG0tXPlYBO7sOvnV63YVZ1qJliW5t8V0fVS8ngVeSvk+idXabMszd+GuMwsbwYOWSDt+v510nLkEYezrm/lz2eqZPeo5mT5LAO9YetwaYVbF295L2uk2aFcSlaE44D6w4boqErbZ1yOazQBZzkcJRagOaPvzENMMKQRnWTQrhROUm+7Kl6C9PxQW+tFbJkjgwWQEYtIcftJX815aBL5KfWeP2EJxmxbdJYVvX3D7AAAOYWfwDYkzaeCRTX3IAAAAASUVORK5CYII=">',


                    ],
                ],
            ],
            'series' => [
                [
                    'name' => 'TreatmentChart',
                    //'data' => [7, 4, 6, 10, 14, 7, 5, 9, 10, 15, 13, 18],
                    'data' => $dataVal,
                ],
            ],
            'xaxis' => [
                //'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'categories' => $dailyLabel,
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
            'colors' => ['#38E54D'],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
        ];
    }
}
