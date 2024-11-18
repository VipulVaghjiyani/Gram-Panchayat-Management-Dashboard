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

class BankTrasactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = BankTransaction::orderBy('id', 'DESC');

            if ($request->bank_id != NULL) {
                $data->where('bank_id', $request->bank_id);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('created_at', '>=', "$from");
                }
            }

            $allData = $data->get();

            return datatables()->of($allData)->addIndexColumn()
                ->editColumn('bank_name', function ($request) {
                    return $request->bank->name ?? ''; // human readable format
                })
                ->editColumn('account_number', function ($request) {
                    return $request->bank->account_number ?? ''; // human readable format
                })
                /* ->editColumn('opening_balance', function ($request) {
                    return $request->bank->opening_balance ?? ''; // human readable format
                }) */
                ->editColumn('credited', function ($request) {
                    return $request->amt_deposite ?? '0'; // human readable format
                })
                ->editColumn('debited', function ($request) {
                    return $request->amt_withdrawn ?? '0'; // human readable format
                })
                ->editColumn('balance', function ($request) {
                    return $request->amt_remaining ?? ''; // human readable format
                })
                ->editColumn('created_by', function ($request) {
                    return $request->user->full_name ?? ''; // human readable format
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d/m/Y');
                })
                /* ->addColumn('action', function ($row) {
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
                }) */
                ->rawColumns(['action'])
                ->make(true);
        }

        $banks = Bank::all();

        return view('bank_transaction.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banks = Bank::all();
        return view('bank_transaction.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        // dd($params);
        $validator = Validator::make($params, [
            'bank_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        if ($params['amt_withdrawn'] != NULL) {
            $current_amount = BankTransaction::where('bank_id', $params['bank_id'])->latest()->pluck('amt_remaining')->first();
            $remaining_amount = $current_amount - $params['amt_withdrawn'];
            $params['amt_remaining'] = $remaining_amount;
            // $params['type'] = 'debit';
        }

        if ($params['amt_deposite'] != NULL) {
            $current_amount = BankTransaction::where('bank_id', $params['bank_id'])->latest()->pluck('amt_remaining')->first();
            $remaining_amount = $current_amount + $params['amt_deposite'];
            $params['amt_remaining'] = $remaining_amount;
            // $params['type'] = 'credit';
        }

        $params['created_by'] = Auth::user()->id;
        $bank = BankTransaction::create($params);

        Session::flash('success', 'Bank Trasaction Added Successfully..!');
        return redirect('bank-transaction')->with([
            'message' => 'Bank Trasaction added successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank = BankTransaction::whereId($id)->first();
        return view('bank_transaction.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bank = BankTransaction::whereId($id)->first();
        return view('bank_transaction.update', compact('bank'));
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

        $bank = BankTransaction::find($id);
        $bank->update($payLoad);

        return redirect()->route('bank.index')->with([
            'message' => 'Bank Trasaction updated successfully!',
            'status' => 'success'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank = BankTransaction::find($id);
        $bank->delete();
        $message = 'Bank Trasaction Deleted Successfully..!';
        return redirect()->back()->with('success', $message);
    }
}
