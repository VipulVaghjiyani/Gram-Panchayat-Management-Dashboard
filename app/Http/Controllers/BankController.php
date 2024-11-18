<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Bank');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = Bank::with('bankTransaction')->orderBy('id', 'DESC');

            if ($from != null && $to != null) {
                $data->whereBetween('created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('created_at', '>=', "$from");
                }
            }

            $allData = $data->get();

            return datatables()->of($allData)->addIndexColumn()
                /* ->editColumn('credited', function ($request) {
                    return $request->bankTransaction->sum('amt_deposite') ?? ''; // human readable format
                })
                ->editColumn('debited', function ($request) {
                    return $request->bankTransaction->sum('amt_withdrawn') ?? ''; // human readable format
                })
                ->editColumn('balance', function ($request) {
                    return ($request->opening_balance + $request->bankTransaction->sum('amt_deposite'))-($request->bankTransaction->sum('amt_withdrawn')) ?? ''; // human readable format
                }) */
                ->editColumn('created_by', function ($request) {
                    return $request->user->full_name ?? ''; // human readable format
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d/m/Y');
                })
                ->addColumn('action', function ($row) {
                    $html = "";
                    $readCheck = Permission::checkCRUDPermissionToUser("Bank", "read");
                    $updateCheck = Permission::checkCRUDPermissionToUser("Bank", "update");
                    $isSuperAdmin = Permission::isSuperAdmin();

                    if ($readCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="bank/' . $row->id . '">View</a></li>';
                    }
                    if ($updateCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="bank/' . $row->id . '/edit">Edit</a></li>';
                    }
                    if (!$isSuperAdmin && !$updateCheck && !$readCheck) {
                        return '';
                    }
                    if ($isSuperAdmin) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" onclick="deleteBank(' . $row->id . ')">Delete</a></li>';
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

        return view('masters.bank.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.bank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'name' => ['required', 'regex:/^\d*[a-zA-Z]{1,}\d*/', 'max:50'],
            'account_name' => ['required'],
            'account_number' => ['required', 'unique:banks'],
            'ifcs_code' => ['required'],
            'branch' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        $params['created_by'] = Auth::user()->id;
        $bank = Bank::create($params);

        $bank_transaction = BankTransaction::create([
            'bank_id' => $bank->id,
            'amt_remaining' => $bank->opening_balance,
            'type' => 'credit',
            'payment_type' => 'None',
            'created_by' => Auth::user()->id,
        ]);

        Session::flash('success', 'Bank Added Successfully..!');
        return redirect('bank')->with([
            'message' => 'Bank added successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank = Bank::whereId($id)->first();
        return view('masters.bank.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bank = Bank::whereId($id)->first();
        return view('masters.bank.update', compact('bank'));
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
            'account_name' => ['required'],
            'account_number' => ['required'],
            'ifcs_code' => ['required'],
            'branch' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $bank = Bank::find($id);
        $bank->update($payLoad);

        return redirect()->route('bank.index')->with([
            'message' => 'Bank updated successfully!',
            'status' => 'success'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank = Bank::find($id);
        $bank->delete();
        $message = 'Bank Deleted Successfully..!';
        return redirect()->back()->with('success', $message);
    }
}
