<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\ExpenseMember;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\House;
use App\Models\HouseOwner;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Member;
use App\Models\PettyCash;
use App\Models\PettyCashLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function donationReport(Request $request)
    {
        return view('report.donation-report');
    }

    public function expenseReport(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            /* $data = Expense::leftJoin('members', 'members.id', 'expenses.member_id')
                ->leftJoin('expense_categories', 'expense_categories.id', 'expenses.expense_category_id')
                ->orderBy('expenses.id', 'DESC'); */
            $data = Expense::select(DB::raw('expenses.id, expenses.financial_year, expense_members.name as expense_member, accounts.name as account, expense_categories.name as expense_category, expenses.date, expenses.amount, expenses.payment_type, expenses.bank_name, expenses.cheque_number, expenses.transaction_number, expenses.transaction_date, expenses.created_at, expenses.created_by'))
                ->leftJoin('expense_members', 'expense_members.id', 'expenses.expense_member_id')
                ->leftJoin('accounts', 'accounts.id', 'expenses.account_id')
                ->leftJoin('expense_categories', 'expense_categories.id', 'expenses.expense_category_id')
                ->leftJoin('users', 'users.id', 'expenses.created_by')
                ->orderBy('expenses.id', 'DESC');

            if ($request->expenseMemberId) {
                $data->where('expenses.expense_member_id', $request->expenseMemberId);
            }

            if ($request->expenseCategoryId) {
                $data->where('expenses.expense_category_id', $request->expenseCategoryId);
            }

            if ($request->accountId) {
                $data->where('expenses.account_id', $request->accountId);
            }

            if ($request->paymentType) {
                $data->where('expenses.payment_type', $request->paymentType);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('expenses.created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('expenses.created_at', '>=', "$from");
                }
            }

            if ($request->search) {
                $data->where(function ($w) use ($request) {
                    $search = $request->get('search');
                    $w->orWhere('expenses.financial_year', 'LIKE', "%$search%")
                        ->orWhere('expense_members.name', 'LIKE', "%$search%")
                        ->orWhere('accounts.name', 'LIKE', "%$search%")
                        ->orWhere('expense_categories.name', 'LIKE', "%$search%")
                        ->orWhereRaw("DATE_FORMAT(expenses.date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                        ->orWhere('expenses.amount', 'LIKE', "%$search%")
                        ->orWhere('expenses.payment_type', 'LIKE', "%$search%")
                        ->orWhere('expenses.bank_name', 'LIKE', "%$search%")
                        ->orWhere('expenses.cheque_number', 'LIKE', "%$search%")
                        ->orWhere('expenses.transaction_number', 'LIKE', "%$search%")
                        ->orWhereRaw("DATE_FORMAT(expenses.transaction_date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                        ->orWhereRaw("DATE_FORMAT(expenses.created_at, '%d/%m/%Y') LIKE ?", ["%$search%"])
                        ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name)"), 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name)"), 'LIKE', "%$search%")
                        ->orWhere('users.first_name', 'LIKE', "%$search%")
                        ->orWhere('users.middle_name', 'LIKE', "%$search%")
                        ->orWhere('users.last_name', 'LIKE', "%$search%");
                });
            }

            $allData = $data->orderBy('expenses.id', 'DESC')->paginate($request->page_length ?? 10);

            $members = Member::all();
            $expense_members = ExpenseMember::all();
            $expense_categories = ExpenseCategory::all();
            $accounts = Account::all();

            return view('report.expense-report', compact('allData', 'request', 'members', 'expense_members', 'expense_categories', 'accounts'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function incomeReport(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            /* $data = Income::leftJoin('members', 'members.id', 'incomes.member_id')
                ->leftJoin('income_categories', 'income_categories.id', 'incomes.income_category_id')
                ->orderBy('incomes.id', 'DESC'); */

            $data = Income::select(DB::raw('incomes.id, incomes.financial_year, incomes.paid_date, incomes.amount, incomes.payment_type, incomes.no_of_year, incomes.from_date, incomes.to_date, incomes.created_at, incomes.created_by, incomes.to_date, houses.house_no, income_categories.name as income_category, members.first_name, members.middle_name, members.last_name, members.customer_no'))
            ->leftJoin('members', 'members.id', 'incomes.member_id')
            ->leftJoin('income_categories', 'income_categories.id', 'incomes.income_category_id')
            ->leftJoin('houses', 'houses.id', 'members.house_id')
            ->leftJoin('users', 'users.id', 'members.created_by')
            ->orderBy('incomes.id', 'DESC');

            if ($request->memberId) {
                $data->where('incomes.member_id', $request->memberId);
            }

            if ($request->incomeCategoryId) {
                $data->where('incomes.income_category_id', $request->incomeCategoryId);
            }

            if ($request->paymentType) {
                $data->where('incomes.payment_type', $request->paymentType);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('incomes.created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('incomes.created_at', '>=', "$from");
                }
            }

            if ($request->search) {
                $data->where(function ($w) use ($request) {
                    $search = $request->get('search');
                    $w->orWhere(DB::raw("CONCAT(members.first_name, ' ', members.middle_name, ' ', members.last_name)"), 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(members.first_name, ' ', members.last_name)"), 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(members.first_name, ' ', members.middle_name)"), 'LIKE', "%$search%")
                    ->orWhere('members.first_name', 'LIKE', "%$search%")
                    ->orWhere('members.middle_name', 'LIKE', "%$search%")
                    ->orWhere('members.last_name', 'LIKE', "%$search%")
                    ->orWhere('financial_year', 'LIKE', "%$search%")
                    ->orWhere('paid_date', 'LIKE', "%$search%")
                    // ->orWhere('income_categories.name', 'LIKE', "%$search%")
                    ->orWhere('amount', 'LIKE', "%$search%")
                    ->orWhere('payment_type', 'LIKE', "%$search%")
                    ->orWhereRaw("DATE_FORMAT(incomes.created_at, '%d/%m/%Y') LIKE ?", ["%$search%"])
                    ->orWhereRaw("DATE_FORMAT(incomes.paid_date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                    ->orWhereRaw("DATE_FORMAT(incomes.from_date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                    ->orWhereRaw("DATE_FORMAT(incomes.to_date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                    ->orWhere('houses.house_no', 'LIKE', "%$search%")
                    ->orWhere('income_categories.name', 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name)"), 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'LIKE', "%$search%")
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name)"), 'LIKE', "%$search%")
                    ->orWhere('users.first_name', 'LIKE', "%$search%")
                    ->orWhere('users.middle_name', 'LIKE', "%$search%")
                    ->orWhere('users.last_name', 'LIKE', "%$search%");
                });
            }

            $allData = $data->orderBy('incomes.id', 'DESC')->paginate($request->page_length ?? 10);

            $members = Member::all();
            $income_categories = IncomeCategory::all();

            return view('report.income-report', compact('allData', 'request', 'members', 'income_categories'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changeHouseOwner(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = HouseOwner::where('is_owner', 1);

            if ($request->memberId) {
                $data->where('member_id', $request->memberId);
            }

            if ($request->house_hold_type) {
                $data->where('house_hold_type', $request->house_hold_type);
            }

            if ($request->houseId) {
                $data->where('house_id', $request->houseId);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('incomes.created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('incomes.created_at', '>=', "$from");
                }
            }

            if ($request->search) {
                $data->where(function ($query) use ($request) {
                    $search = $request->search;
                    $query->whereHas('member', function ($q) use ($search) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'LIKE', "%$search%")
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%")
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', middle_name)"), 'LIKE', "%$search%")
                            ->orWhere('first_name', 'LIKE', "%$search%")
                            ->orWhere('middle_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('house', function ($q) use ($search) {
                        $q->where('house_no', 'LIKE', "%$search%");
                    })
                    ->orWhereRaw("DATE_FORMAT(house_owners.created_at, '%d/%m/%Y') LIKE ?", ["%$search%"]);
                });
            }

            $allData = $data->orderBy('id', 'DESC')->paginate($request->page_length ?? 10);

            $members = Member::all();
            $houses = House::all();
            $uniqueHouseIds = DB::table('house_owners')->distinct()->pluck('house_id');

            $oldOwners = [];
            foreach ($uniqueHouseIds as $houseId) {
                $newOwner = HouseOwner::where('house_id', $houseId)
                    ->where('is_owner', 1)
                    ->first();
                if ($newOwner) {
                    $oldOwner = HouseOwner::where('house_id', $houseId)
                        ->where('id', '<', $newOwner->id)
                        ->orderBy('id', 'DESC')
                        ->first();
                    if ($oldOwner && $oldOwner->member) {
                        $oldOwners[$houseId] = $oldOwner->member->full_name;
                    } else {
                        $oldOwners[$houseId] = '';
                    }
                } else {
                    $oldOwners[$houseId] = '';
                }
            }

            return view('report.change-house-owner', compact('allData', 'request', 'members', 'houses', 'uniqueHouseIds', 'oldOwners'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function balanceSheetReport(Request $request)
    {
        if (request()->ajax()) {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = Bank::orderBy('id', 'DESC');

            if ($from != null && $to != null) {
                $data->whereBetween('created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('created_at', '>=', "$from");
                }
            }

            $allData = $data->get();

            return datatables()->of($allData)->addIndexColumn()
                ->editColumn('credited', function ($request) {
                    return $request->bankTransaction->sum('amt_deposite') ?? ''; // human readable format
                })
                ->editColumn('debited', function ($request) {
                    return $request->bankTransaction->sum('amt_withdrawn') ?? ''; // human readable format
                })
                ->editColumn('balance', function ($request) {
                    return ($request->opening_balance + $request->bankTransaction->sum('amt_deposite'))-($request->bankTransaction->sum('amt_withdrawn')) ?? ''; // human readable format
                })
                ->make(true);
        }

        return view('report.balance-sheet-report');
    }

    public function pettyCashReport(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = PettyCash::select('pcl.amount as log_amount', 'petty_cashes.*', 'pcl.*', 'pcl.name as log_name')
            ->Join('petty_cash_logs as pcl', 'pcl.petty_cash_id', 'petty_cashes.id');

            if ($from != null && $to != null) {
                $data->whereBetween('date', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('date', '>=', "$from");
                }
            }

            if ($request->search) {
                $data->where(function ($w) use ($request) {
                    $search = $request->get('search');
                    $w
                    ->orWhereRaw("DATE_FORMAT(petty_cashes.date, '%d/%m/%Y') LIKE ?", ["%$search%"])
                    ->orWhere('pcl.name', 'LIKE', "%$search%")
                    ->orWhere('pcl.amount', 'LIKE', "%$search%")
                    ->orWhere('pcl.description', 'LIKE', "%$search%")
                    ->orWhereRaw("DATE_FORMAT(pcl.created_at, '%d/%m/%Y') LIKE ?", ["%$search%"]);
                });
            }

            $allData = $data->orderBy('petty_cashes.id', 'DESC')->paginate($request->page_length ?? 10);

            return view('report.petty-cash-report', compact('allData', 'request'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
