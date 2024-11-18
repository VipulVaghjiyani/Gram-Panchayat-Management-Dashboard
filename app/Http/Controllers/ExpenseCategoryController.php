<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Expense Category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = ExpenseCategory::orderBy('id', 'DESC');

            if ($from != null && $to != null) {
                $data->whereBetween('created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('created_at', '>=', "$from");
                }
            }

            $allData = $data->get();

            return datatables()->of($allData)->addIndexColumn()
                ->editColumn('created_by', function ($request) {
                    return $request->user->full_name ?? ''; // human readable format
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d/m/Y');
                })
                ->addColumn('action', function ($row) {
                    $html = "";
                    $readCheck = Permission::checkCRUDPermissionToUser("Expense Category", "read");
                    $updateCheck = Permission::checkCRUDPermissionToUser("Expense Category", "update");
                    $isSuperAdmin = Permission::isSuperAdmin();

                    if ($updateCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="expense-category/' . $row->id . '/edit">Edit</a></li>';
                    }
                    if (!$isSuperAdmin && !$updateCheck) {
                        return '';
                    }
                    if ($isSuperAdmin) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" onclick="deleteExpenseCategory(' . $row->id . ')">Delete</a></li>';
                    }
                    return
                        '<div class="dropdown">
                        <button type="button" class="btn btn-primary p-1 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            Action
                        </button>
                        <div class="dropdown-menu">
                            ' . $html . '
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('masters.expense_category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.expense_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => ['required', 'regex:/^\d*[a-zA-Z]{1,}\d*/', 'max:50'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        $params['created_by'] = Auth::user()->id;
        ExpenseCategory::create($params);

        Session::flash('success', 'Expense Category Added Successfully..!');
        return redirect('expense-category')->with([
            'message' => 'Expense Category added successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense_category = ExpenseCategory::whereId($id)->first();
        return view('masters.expense_category.update', compact('expense_category'));
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
            'name' => ['required', 'regex:/^\d*[a-zA-Z]{1,}\d*/', 'max:50'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $expense_category = ExpenseCategory::find($id);
        $expense_category->update($payLoad);

        return redirect()->route('expense-category.index')->with([
            'message' => 'Expense Category updated successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isExpenseExists = Expense::where('expense_category_id', $id)->exists();

        if ($isExpenseExists) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        $expense_category = ExpenseCategory::find($id);
        $expense_category->delete();
        $message = 'Expense Category Deleted Successfully..!';
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
