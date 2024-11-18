<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Role::where('id', '!=', 1)->orderBy('id', 'desc');

            $allData = $data->get();

            return datatables()->of($allData)
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d/m/Y'); // human readable format
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = "";
                    $readCheck = Permission::checkCRUDPermissionToUser("roles", "read");
                    $updateCheck = Permission::checkCRUDPermissionToUser("roles", "update");
                    $isSuperAdmin = Permission::isSuperAdmin();
                    if ($readCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="roles/' . $row->id . '">View</a></li>';
                    }
                    if ($updateCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="roles/' . $row->id . '/edit">Edit</a></li>';
                    }
                    if (!$isSuperAdmin && !$updateCheck && !$readCheck) {
                        return '';
                    }
                    if ($isSuperAdmin) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" onclick="deleteRole(' . $row->id . ')">Delete</a></li>';
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

        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:20', 'unique:roles,name'],
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'admin',
        ]);
        // $role->syncPermissions($request->input('permission'));

        return redirect('roles/' . $role->id . '/edit')->with('success', 'Role Created Successfully.!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $tablesArr = [];
        $breadcrumbs = [];
        $pageConfigs = ['pageHeader' => true];
        if ($id) {
            $role = Role::find($id);

            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $host = $request->getHttpHost();
                if ($host == 'localhost') {
                    $tablesArr[$table->Tables_in_mineology_server] = $table->Tables_in_mineology_server;
                } else {
                    $tablesArr[$table->{'Tables_in_' . env('DB_DATABASE')}] = $table->{'Tables_in_' . env('DB_DATABASE')};
                }
            }

            $filterArr = [];

            if ($tablesArr['accounts']) {
                $filterArr['Account'] = 'Account';
            }

            if ($tablesArr['activity_log']) {
                $filterArr['Activity Log'] = 'Activity Log';
            }

            if ($tablesArr['banks']) {
                $filterArr['Bank'] = 'Bank';
            }

            if ($tablesArr['expense_categories']) {
                $filterArr['Expense Category'] = 'Expense Category';
            }

            if ($tablesArr['expense_members']) {
                $filterArr['Expense Member'] = 'Expense Member';
            }

            if ($tablesArr['expenses']) {
                $filterArr['Expense'] = 'Expense';
            }

            if ($tablesArr['houses']) {
                $filterArr['House'] = 'House';
            }

            if ($tablesArr['income_categories']) {
                $filterArr['Income Category'] = 'Income Category';
            }

            if ($tablesArr['incomes']) {
                $filterArr['Income'] = 'Income';
            }

            if ($tablesArr['members']) {
                $filterArr['Member'] = 'Member';
            }

            if ($tablesArr['petty_cash_logs'] && $tablesArr['petty_cashes']) {
                $filterArr['Petty Cash'] = 'Petty Cash';
            }

            if ($tablesArr['users']) {
                $filterArr['User'] = 'User';
            }

            $permissionData = new Permission();
            return view('role.show', ['pageConfigs' => $pageConfigs, 'role' => $role, 'accessData' => $filterArr, 'permissionData' => $permissionData]);
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $tablesArr = [];
        $breadcrumbs = [];
        $pageConfigs = ['pageHeader' => true];
        if ($id) {
            $role = Role::find($id);

            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $host = $request->getHttpHost();
                if ($host == 'localhost') {
                    $tablesArr[$table->Tables_in_mineology_server] = $table->Tables_in_mineology_server;
                } else {
                    $tablesArr[$table->{'Tables_in_' . env('DB_DATABASE')}] = $table->{'Tables_in_' . env('DB_DATABASE')};
                }
            }

            // dd($tablesArr);

            $filterArr = [];

            if ($tablesArr['accounts']) {
                $filterArr['Account'] = 'Account';
            }

            if ($tablesArr['activity_log']) {
                $filterArr['Activity Log'] = 'Activity Log';
            }

            if ($tablesArr['banks']) {
                $filterArr['Bank'] = 'Bank';
            }

            if ($tablesArr['expense_categories']) {
                $filterArr['Expense Category'] = 'Expense Category';
            }

            if ($tablesArr['expenses']) {
                $filterArr['Expense'] = 'Expense';
            }

            if ($tablesArr['expense_members']) {
                $filterArr['Expense Member'] = 'Expense Member';
            }

            if ($tablesArr['houses']) {
                $filterArr['House'] = 'House';
            }

            if ($tablesArr['income_categories']) {
                $filterArr['Income Category'] = 'Income Category';
            }

            if ($tablesArr['incomes']) {
                $filterArr['Income'] = 'Income';
            }

            if ($tablesArr['members']) {
                $filterArr['Member'] = 'Member';
            }

            if ($tablesArr['petty_cash_logs'] && $tablesArr['petty_cashes']) {
                $filterArr['Petty Cash'] = 'Petty Cash';
            }

            if ($tablesArr['users']) {
                $filterArr['User'] = 'User';
            }

            $permissionData = new Permission();
            return view('role.update', ['pageConfigs' => $pageConfigs, 'role' => $role, 'accessData' => $filterArr, 'permissionData' => $permissionData]);
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $param = $request->all();
        $role = Role::find($param['id']);
        $validator = Validator::make($param, [
            'name' => ['required', 'string', 'max:20', 'unique:roles,name,' . $role->id],
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        $role_id = $param['id'];

        if (!empty($param['permission'])) {
            Permission::where('role_id', $role_id)->delete();
            foreach ($param['permission'] as $key => $value) {
                $value['module'] = $key;
                $value['role_id'] = $role_id;
                Permission::create($value);
            }
            // dd($param['permission']);
        } else {
            Permission::where('role_id', $role_id)->delete();
        }
        if (!empty($param)) {

            $role = Role::find($param['id']);
            unset($param['id']);
            $isUpdated = $role->update($param);
            if ($isUpdated) {
                return redirect('roles')->with('success', 'Updated Successfully.!');
            } else {
                return Redirect::back()->with('error', 'Something Wrong happend.!');
            }
        } else {
            return Redirect::back()->with('error', 'ID not selected or not found.!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, $id)
    {
        $user = User::where('role_id', $id)->exists();

        if ($user) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        if (!empty($id)) {
            $data = Role::find($id);
            $isDeleted = $data->delete();

            if ($isDeleted) {
                return response()->json([
                    'status' => true,
                    'message' => 'Record deleted successfully.'
                ]);
            } else {
                return Redirect::back()->with('error', 'Something went wrong.');
            }
        } else {
            return Redirect::back()->with('error', 'Id not selected or found.');
        }
    }
}
