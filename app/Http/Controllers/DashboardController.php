<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\House;
use App\Models\Income;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data['total_house'] = House::where('id', '!=', 0)->count();
        $data['total_member'] = Member::where('id', '!=', 0)->count();
        $data['total_income'] = Income::where('id', '!=', 0)->count();
        // $data['total_income_amount'] = Income::get()->sum("amount");
        $data['total_income_amount'] = Income::select('house_id', DB::raw('DATE(paid_date) as date'), 'income_category_id', DB::raw('MAX(amount) as amount'))
            ->groupBy('house_id', 'date', 'income_category_id')
            ->get()
            ->sum('amount');
        $data['total_expense_amount'] = Expense::get()->sum("amount");

        return view('dashboard', ['data' => $data]);
    }

    public function getAllGraphData(Request $request)
    {
        $data = [
            'monthlyData' => [
                'expense' => $this->getExpenseData(),
                'income' => $this->getIncomeData()
            ]
        ];
        return $data;
    }

    private function getIncomeData()
    {
        $distinctIncomeData = Income::select('house_id', DB::raw('DATE(paid_date) as date'), 'income_category_id', DB::raw('MAX(amount) as amount'))
            ->whereYear('paid_date', Carbon::now()->format('Y'))
            ->groupBy('house_id', 'date', 'income_category_id')
            ->get();

        $monthlyIncome = $distinctIncomeData->groupBy(function ($entry) {
            return Carbon::parse($entry->date)->format('F'); // Group by month name
        });

        $result = $monthlyIncome->map(function ($monthData, $month) {
            return [
                'month' => $month,
                'amount' => $monthData->sum('amount')
            ];
        });

        return $result->values();
    }


    private function getExpenseData()
    {
        $ExpenseData = Expense::select(DB::raw('MONTHNAME(date) AS month, SUM(amount) as amount'))->whereYear('date', Carbon::now()->format('Y'))->orderBy('date')->groupBy('month')->get();

        return $ExpenseData;
    }
}
