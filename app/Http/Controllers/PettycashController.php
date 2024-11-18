<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PettyCash;
use App\Models\PettyCashLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PettycashController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Petty Cash');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {

            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = PettyCash::orderBy('id', 'DESC');

            if ($from != null && $to != null) {
                $data->whereBetween('date', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('date', '>=', "$from");
                }
            }

            $allData = $data->get();

            return datatables()->of($allData)->addIndexColumn()
                ->editColumn('date', function ($request) {
                    return $request->date ? \DateTime::createFromFormat('Y-m-d', $request->date)->format('d/m/Y') : "";
                })
                ->editColumn('credited_balance', function ($request) {
                    $credited_balance = PettyCashLog::select('amount')->where([['petty_cash_id', $request->id], ['type', 'credit']])->sum('amount');
                    return $credited_balance ?? 0;
                })
                ->editColumn('debited_balance', function ($request) {
                    $debited_balance = PettyCashLog::select('amount')->where([['petty_cash_id', $request->id], ['type', 'debit']])->sum('amount');
                    return $debited_balance ?? 0;
                })
                ->editColumn('created_by', function ($request) {
                    return $request->user->full_name ?? '';
                })
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d/m/Y');
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = "";
                    $readCheck = Permission::checkCRUDPermissionToUser("Petty Cash", "read");
                    $createCheck = Permission::checkCRUDPermissionToUser("Petty Cash", "create");
                    $updateCheck = Permission::checkCRUDPermissionToUser("Petty Cash", "update");
                    $isSuperAdmin = Permission::isSuperAdmin();
                    if ($readCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="petty-cash/' . $row->id . '">View</a></li>';
                    }
                    if ($createCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="petty-cash/log-store/' . $row->id . '">Add Item</a></li>';
                    }
                    if (!$isSuperAdmin && !$updateCheck && !$readCheck && !$createCheck) {
                        return '';
                    }
                    if ($isSuperAdmin) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" onclick="deletePettyCash(' . $row->id . ')">Delete</a></li>';
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
        $pettyCash = PettyCash::all();

        return view('petty_cash.index', compact('pettyCash'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('petty_cash.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'date' => ['required'],
            'name' => ['required'],
            'opening_balance' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        $params['date'] = $request->date ? DateTime::createFromFormat('d/m/Y', $params['date'])->format('Y-m-d') : null;
        $params['created_by'] = Auth::user()->id;
        $params['cash_in_hand'] = $params['opening_balance'];
        $data = PettyCash::create($params);

        return redirect('petty-cash')->with([
            'message' => 'Petty Cash Added Successfully!!!',
            'status' => 'success'
        ]);

        return view('petty_cash.petty-cash-log', compact('data'));
    }

    public function pettyCashLogCreate(Request $request, $id)
    {
        $data = PettyCash::where('id', $id)->first();
        return view('petty_cash.petty-cash-log', compact('data'));
    }

    public function pettyCashLogStore(Request $request, $id)
    {
        $param = $request->all();
        // dd($param);

        $validator = Validator::make($param, [
            'addmore.*.name' => ['required'],
            'addmore.*.amount' => ['required']
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!empty($param['petty_cash_id'])) {

            if (isset($param['addmore'])) {
                foreach ($param['addmore'] as $values) {
                    $values['id'] = $values['id'] ?? NULL;
                    $id = $param['petty_cash_id'];
                    PettyCashLog::updateOrCreate(
                        ['id' => $values['id']],
                        [
                            'petty_cash_id' =>  $id,
                            'name' => $values['name'],
                            'description' => $values['description'],
                            'amount' => $values['amount'],
                            'type' => $values['type'],
                            'created_by' => Auth::user()->id,
                        ]
                    );
                }
            }

            $data = PettyCash::where('id', $id)->first();
            $data->cash_in_hand = $param['cash_in_hand'];
            $data->save();

            return Redirect::route('petty-cash.index')->with('success', 'Created Successfully.!!');
        }
        $pettyCash = new PettyCash();
        if (isset($request->date) && !empty($request->date) && $request->close != '1') {
            $validator = Validator::make($param, [
                'date' => ['required', 'string', 'max:200'],
                'name' => ['required', 'string', 'max:200'],
                'amount' => ['required', 'string', 'max:200']
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $pettyCash->date = $request->date;
            $pettyCash->name = $request->name;
            $pettyCash->description = $request->description;
            $pettyCash->amount = $request->amount;
            $pettyCash->cash_in_hand = $request->cash_in_hand != '' ? $request->cash_in_hand : $request->amount;
            $pettyCash->created_by = Auth::user()->id;
            $ispettyCashCreated = $pettyCash->save();
            if ($ispettyCashCreated) {

                $pettyCash = PettyCash::find($pettyCash->id);
                $pettyCash->save();

                $data = PettyCash::Where('deleted_at', NULL)->where('date', Carbon::today()->format('d/m/Y'))->first();

                return view('petty_cash.create', compact('data'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($id) {
            $data = PettyCash::where('id', $id)->first();
            return view('petty_cash.show', compact('data'));
        } else {
            return Redirect::back()->with('error', 'Petty Cash ID not selected or not found.!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = PettyCash::where('id', $id)->first();
        return view('petty_cash.update', compact('data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->id) {
            $param = $request->all();

            $data = PettyCash::where('id', $request->id)->first();

            $data->name = $request->name;
            $data->description = $request->description;
            $data->amount = $request->amount;
            $data->cash_in_hand = $request->cash_in_hand != '' ? $request->cash_in_hand : $request->amount;
            $ispettyCashCreated = $data->save();

            if (isset($param['addmore'])) {
                // dd($param['addmore']);
                foreach ($param['addmore'] as $values) {
                    $values['id'] = $values['id'] ?? NULL;
                    $id = $data->id;
                    PettyCashLog::updateOrCreate(
                        ['id' => $values['id']],
                        [
                            'petty_cash_id' =>  $id,
                            'name' => $values['name'],
                            'description' => $values['description'],
                            'amount' => $values['amount'],
                            'type' => $values['type'],
                            'created_by' => Auth::user()->id,
                        ]
                    );
                }
            }

            return Redirect::route('petty-cash.index')->with('success', 'Updated Successfully.!!');
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($id) {

            $pettyCashExist = PettyCashLog::where('petty_cash_id', $id)->exists();

            if ($pettyCashExist) {
                return response()->json([
                    'status' => false,
                    'message' => 'This is already in Use'
                ]);
            }

            $pettyCash = PettyCash::find($id);
            $pettyCash->delete();

            return response()->json([
                'status' => true,
                'message' => 'Petty Cash Deleted Successfully!!!'
            ]);
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    public function deleteExtraFields(Request $request, $id)
    {
        $model = $request->model;
        $message = "";
        if ($model == "petty") {
            PettyCashLog::where('id', $id)->delete();
            $message = "Removed successfully";
        }
        return response()->json(["status" => "success", "message" => $message]);
    }
}
