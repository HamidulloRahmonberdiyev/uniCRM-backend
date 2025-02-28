<?php

namespace App\Http\Requests\Stats;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ChartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => 'sometimes|integer|min:2000|max:' . (Carbon::now()->year + 1),
            'month' => 'sometimes|integer|min:1|max:12',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
        ];
    }

    public function getYear()
    {
        return $this->input('year', Carbon::now()->year);
    }

    public function getMonth()
    {
        return $this->input('month', Carbon::now()->month);
    }

    public function getDateRange()
    {
        $startDate = $this->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $this->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        return [$startDate, $endDate];
    }
}
