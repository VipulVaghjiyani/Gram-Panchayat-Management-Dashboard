<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\ExpenseMember;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Expense');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = Expense::select(DB::raw('expenses.id, expenses.financial_year, expense_members.name as expense_member, accounts.name as account, expense_categories.name as expense_category, expenses.date, expenses.amount, expenses.payment_type, expenses.created_at, expenses.created_by'))
            ->leftJoin('expense_members', 'expense_members.id', 'expenses.expense_member_id')
            ->leftJoin('accounts', 'accounts.id', 'expenses.account_id')
            ->leftJoin('expense_categories', 'expense_categories.id', 'expenses.expense_category_id')
            ->leftJoin('users', 'users.id', 'expenses.created_by')
            ->orderBy('expenses.id', 'DESC');

            if ($request->memberId) {
                $data->where('expenses.expense_member_id', $request->memberId);
            }

            if ($request->expenseCategoryId) {
                $data->where('expenses.expense_category_id', $request->expenseCategoryId);
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

            $members = ExpenseMember::all();
            $expense_categories = ExpenseCategory::all();

            return view('expense.index', compact('allData', 'request', 'members', 'expense_categories'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $expense_categories = ExpenseCategory::all();
        $accounts = Account::all();
        $expense_members = ExpenseMember::all();
        $banks = Bank::all();
        return view('expense.create', compact('members', 'expense_categories', 'accounts', 'expense_members', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'expense_member_id' => ['required'],
            'expense_category_id' => ['required'],
            'amount' => ['required'],
            'payment_type' => ['required'],
            'date' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        $start_year = date('Y') . '-04-01';

        if (now() > $start_year) {
            $financialYear = date('y') . '-' . date('y', strtotime('+1 year', strtotime($start_year)));
        } else {
            $financialYear = date('y', strtotime('-1 year', strtotime($start_year))) . '-' . date('y');
        }

        $params['financial_year'] = $financialYear;

        $params['transaction_date'] = ($request->transaction_date) ? \DateTime::createFromFormat('d/m/Y', $params['transaction_date'])->format('Y-m-d') : null;
        $params['date'] = ($request->date) ? \DateTime::createFromFormat('d/m/Y', $params['date'])->format('Y-m-d') : null;

        $params['created_by'] = Auth::user()->id;
        Expense::create($params);

        Session::flash('success', 'Expense Added Successfully..!');
        return redirect('expense')->with([
            'message' => 'Expense added successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = Expense::whereId($id)->first();
        return view('expense.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::whereId($id)->first();
        // return $expense;
        $members = Member::all();
        // dd($members);
        $expense_categories = ExpenseCategory::all();
        $accounts = Account::all();
        $expense_members = ExpenseMember::all();
        return view('expense.update', compact('expense', 'members', 'expense_categories', 'accounts', 'expense_members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payLoad = $request->all();
        unset($payLoad['_token']);
        unset($payLoad['_method']);

        $validator = Validator::make($payLoad, [
            // 'expense_member_id' => ['required'],
            'expense_category_id' => ['required'],
            // 'amount' => ['required'],
            'payment_type' => ['required'],
            'date' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $payLoad['transaction_date'] = ($request->transaction_date) ? \DateTime::createFromFormat('d/m/Y', $payLoad['transaction_date'])->format('Y-m-d') : null;
        $payLoad['date'] = ($request->date) ? \DateTime::createFromFormat('d/m/Y', $payLoad['date'])->format('Y-m-d') : null;

        $expense = Expense::find($id);
        $expense->update($payLoad);

        return redirect()->route('expense.index')->with([
            'message' => 'Expense updated successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        $message = 'Expense deleted Successfully..!';
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
