<?php

namespace App\Http\Controllers;
use App\Models\ExpenseMember;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class ExpenseMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Expense Member');
    }

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = ExpenseMember::orderBy('id', 'DESC');

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
                    $readCheck = Permission::checkCRUDPermissionToUser("Expense Member", "read");
                    $updateCheck = Permission::checkCRUDPermissionToUser("Expense Member", "update");
                    $isSuperAdmin = Permission::isSuperAdmin();

                    if ($updateCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="expense-member/' . $row->id . '/edit">Edit</a></li>';
                    }
                    if (!$isSuperAdmin && !$updateCheck) {
                        return '';
                    }
                    if ($isSuperAdmin) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" onclick="deleteExpenseMember(' . $row->id . ')">Delete</a></li>';
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

        return view('expense_member.index');
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense_member.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => ['required', 'string', 'max:50'],
            'mobile'=> ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        $params['created_by'] = Auth::user()->id;
        ExpenseMember::create($params);

        Session::flash('success', 'Expense Member Added Successfully..!');
        return redirect('expense-member')->with([
            'message' => 'Expense Member added successfully!',
            'status' => 'success'
        ]);
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense_member = ExpenseMember::whereId($id)->first();
        return view('expense_member.update', compact('expense_member'));
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
            'name' => ['required', 'string', 'max:50'],
            'mobile'=> ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $house = ExpenseMember::find($id);
        $house->update($payLoad);

        return redirect()->route('expense-member.index')->with([
            'message' => 'Expense Member updated successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isExpenseCategoryExists = Expense::where('expense_category_id', $id)->exists();
        $isExpanseMemberExists = Expense::where('expense_member_id', $id)->exists();

        if ($isExpenseCategoryExists || $isExpanseMemberExists) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        $expense_member = ExpenseMember::find($id);
        $expense_member->delete();
        $message = 'Expense Member Deleted Successfully..!';
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

}
