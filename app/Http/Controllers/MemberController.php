<?php

namespace App\Http\Controllers;

use App\Models\ExpenseMember;
use App\Models\Expense;
use App\Models\House;
use App\Models\HouseOwner;
use App\Models\Income;
use App\Models\Member;
use App\Models\MemberAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('role:Member');
    } */

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = Member::select(DB::raw('members.id, members.first_name, members.middle_name, members.last_name, members.email, members.mobile, members.created_at, members.created_by, houses.house_no, members.house_hold_type, members.customer_no'))
                ->leftJoin('houses', 'houses.id', 'members.house_id')
                ->leftJoin('users', 'users.id', 'members.created_by')
                ->where('members.deleted_at', NULL);

            // dd($data->get());
            if ($request->house_hold_type) {
                $data->where('house_hold_type', $request->house_hold_type);
            }

            if ($request->houseId) {
                $data->where('house_id', $request->houseId);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('created_at', ["$from", "$to"]);
            } else {
                if ($from != null) {
                    $data->where('created_at', '>=', "$from");
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
                        ->orWhere('members.dob', 'LIKE', "%$search%")
                        ->orWhere('members.email', 'LIKE', "%$search%")
                        ->orWhere('members.mobile', 'LIKE', "%$search%")
                        ->orWhere('members.customer_no', 'LIKE', "%$search%")
                        ->orWhere(DB::raw("DATE_FORMAT(members.created_at, '%d/%m/%Y')"), 'LIKE', "%$search%")
                        ->orWhere('members.created_by', 'LIKE', "%$search%")
                        ->orWhere('house_hold_type', 'LIKE', "%$search%")
                        ->orWhere('houses.house_no', 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name)"), 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name)"), 'LIKE', "%$search%")
                        ->orWhere('users.first_name', 'LIKE', "%$search%")
                        ->orWhere('users.middle_name', 'LIKE', "%$search%")
                        ->orWhere('users.last_name', 'LIKE', "%$search%");
                });
            }

            $allData = $data->orderBy('members.id', 'DESC')->paginate($request->page_length ?? 10);

            $houses = House::all();
            return view('member.index', compact('allData', 'request', 'houses'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $houses = House::all();
        return view('member.create', compact('houses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:255'],
            'permanent_address' => ['required'],
            'current_address' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $params['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $params['dob'])->format('Y-m-d') : null;
        $params['is_income_member'] = ($request->is_income_member && $params['is_income_member'] == 'on') ? true : false;
        $params['is_expance_member'] = ($request->is_expance_member && $params['is_expance_member'] == 'on') ? true : false;
        $params['is_same_as_permanent_address'] = ($request->is_same_as_permanent_address && $params['is_same_as_permanent_address'] == 'on') ? true : false;
        $params['created_by'] = Auth::user()->id;

        if ($request->has('memberHouse')) {
            $errorBag = [];

            foreach ($request->memberHouse as $key => $value) {
                $house = House::find($value['house_id']);
                if ($house) {
                    if (in_array($value['house_hold_type'], ['Owner', 'Owner Member', 'Rental'])) {
                        $existingOwner = HouseOwner::where([
                            ['house_id', $value['house_id']],
                            ['house_hold_type', 'Owner']
                        ])->first();

                        // Ensure there is no existing owner for this house if creating a new owner
                        if ($value['house_hold_type'] === 'Owner' && $existingOwner) {
                            $errorBag["memberHouse.$key.house_hold_type"] = 'This house already has an owner.';
                        }
                    }
                }
            }
        }

        if (!empty($errorBag)) {
            return Redirect::back()->withErrors($errorBag)->withInput();
        }

        $createRecord = Member::create($params);
        if ($request->has('memberHouse')) {
            foreach ($params['memberHouse'] as $key => $value) {
                $value['is_currently_living'] = (isset($value['is_currently_living']) && $value['is_currently_living'] == 'on') ? true : false;
                if (in_array($value['house_hold_type'], ['Owner', 'Owner Member', 'Rental'])) {
                    HouseOwner::create([
                        'house_id' => $value['house_id'],
                        'member_id' => $createRecord->id,
                        'house_hold_type' => $value['house_hold_type'],
                        'is_owner' => $value['house_hold_type'] == 'Owner' ? 1 : 0,
                        'is_currently_living' => $value['is_currently_living'],
                        'created_by' => $createRecord->created_by,
                    ]);
                }
            }

            $houseHoldType = HouseOwner::where([['member_id', $createRecord['id']], ['is_currently_living', 1]])->first();

            if ($houseHoldType) {
                Member::where('id', $createRecord['id'])->update([
                    'house_id' => $houseHoldType->house_id,
                    'house_hold_type' => $houseHoldType->house_hold_type,
                ]);

                $createRecord->house_id = $houseHoldType->house_id;
                $createRecord->house_hold_type = $houseHoldType->house_hold_type;

                $OwnerMember = Member::where([['house_id', $createRecord->house_id], ['house_hold_type', 'Owner']])->first();
                $RentalMember = Member::where([['house_id', $createRecord->house_id], ['house_hold_type', 'Rental']])->first();
                $updateHouse = House::whereId($createRecord->house_id)->first();
                if (($createRecord->house_hold_type == "Owner") && ($updateHouse !== null && $updateHouse->owner_member_id === null)) {
                    House::whereId($createRecord->house_id)->update([
                        'owner_member_id' => $OwnerMember->id,
                        'is_currently_living' => true,
                    ]);
                }

                if (($createRecord->house_hold_type == "Rental") && ($updateHouse !== null && $updateHouse->rental_member_id == Null)) {
                    House::whereId($createRecord->house_id)->update([
                        'rental_member_id' => $RentalMember->id,
                        'is_currently_living' => true,
                    ]);
                }
            }
        }


        if (!empty($params['permanent_address'])) {
            MemberAddress::create([
                'member_id' => $createRecord->id,
                'permanent_address' => $params['permanent_address'],
                'gaam' => $params['permanent_gaam'],
                'taluka' => $params['permanent_taluka'],
                'district' => $params['permanent_district'],
                'state' => $params['permanent_state'],
                'country' => $params['permanent_country'],
                'post_code' => $params['permanent_post_code'],
            ]);
        }

        if (!empty($params['current_address'])) {
            MemberAddress::create([
                'member_id' => $createRecord->id,
                'is_same_as_permanent_address' => $params['is_same_as_permanent_address'],
                'current_address' => $params['current_address'],
                'gaam' => $params['current_gaam'],
                'taluka' => $params['current_taluka'],
                'district' => $params['current_district'],
                'state' => $params['current_state'],
                'country' => $params['current_country'],
                'post_code' => $params['current_post_code'],
            ]);
        }

        // count total member in house
        $houses = House::all();

        foreach ($houses as $key => $value) {
            $Id = $value->id;
            // $membersId = Member::where('house_id', $Id)->get()->count();
            $membersId = HouseOwner::where([['house_id', $Id], ['is_currently_living', 1]])->get()->count();

            $updateHouse = House::whereId($Id)->update([
                'total_members' => $membersId
            ]);
        }

        // added expense member

        if ($createRecord->is_expance_member == true) {
            $ExpenseMember = ExpenseMember::create([
                'name' => $createRecord->first_name . " " . $createRecord->middle_name . " " . $createRecord->last_name,
                'mobile' => $createRecord->mobile,
                'created_by' => Auth::user()->id
            ]);
        }

        if ($createRecord) {
            Session::flash('success', 'Member Added Successfully..!');
            return redirect('member')->with([
                'message' => 'Member added successfully!',
                'status' => 'success'
            ]);
        } else {
            return Redirect::back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::whereId($id)->first();
        $memberPermanentAddress = MemberAddress::where([['member_id', $id], ['current_address', null]])->first();
        $memberCurrentAddress = MemberAddress::where([['member_id', $id], ['current_address', '!=', null]])->first();
        $houses = HouseOwner::where('member_id', $id)->get();
        $incomes = Income::with('member')->where([['member_id', $id], ['income_category_id', 1]])->get();
        return view('member.show', compact('member', 'memberPermanentAddress', 'memberCurrentAddress', 'houses', 'incomes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::whereId($id)->first();
        $memberPermanentAddress = MemberAddress::where([['member_id', $id], ['current_address', null]])->first();
        $memberCurrentAddress = MemberAddress::where([['member_id', $id], ['current_address', '!=', null]])->first();
        $houses = House::all();
        $memberHouses = HouseOwner::where('member_id', $id)->get();
        return view('member.update', compact('member', 'memberPermanentAddress', 'memberCurrentAddress', 'houses', 'memberHouses'));
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
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:255'],
            'permanent_address' => ['required'],
            'current_address' => ['required'],
            // 'house_id' => ['required'],
            // 'house_hold_type' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $payLoad['dob'] = ($request->dob) ? \DateTime::createFromFormat('d/m/Y', $payLoad['dob'])->format('Y-m-d') : null;
        $payLoad['is_income_member'] = ($request->is_income_member && $payLoad['is_income_member'] == 'on') ? true : false;
        $payLoad['is_expance_member'] = ($request->is_expance_member && $payLoad['is_expance_member'] == 'on') ? true : false;
        $payLoad['is_same_as_permanent_address'] = ($request->is_same_as_permanent_address && $payLoad['is_same_as_permanent_address'] == 'on') ? true : false;
        $payLoad['is_currently_living'] = ($request->is_currently_living && $payLoad['is_currently_living'] == 'on') ? true : false;


        if ($request->has('memberHouse')) {
            $errorBag = [];

            foreach ($request->memberHouse as $key => $value) {
                $initialHouseId = $request->input("initial_house_id_$key");
                $initialHouseHoldType = $request->input("initial_house_hold_type_$key");

                $house = House::find($value['house_id']);
                if ($house) {
                    if (in_array($value['house_hold_type'], ['Owner', 'Owner Member', 'Rental'])) {
                        $existingOwner = HouseOwner::where([
                            ['house_id', $value['house_id']],
                            ['house_hold_type', 'Owner']
                        ])->first();

                        $isModified = $initialHouseId != $value['house_id'] || $initialHouseHoldType != $value['house_hold_type'];
                        if ($isModified && $value['house_hold_type'] === 'Owner') {
                            if ($existingOwner && $existingOwner->member_id != $id) {
                                $errorBag["memberHouse.$key.house_hold_type"] = 'This house already has an owner.';
                            }
                        }
                    }
                }
            }
        }

        if (!empty($errorBag)) {
            return Redirect::back()->withErrors($errorBag)->withInput();
        }

        $member = Member::find($id);
        $member->update($payLoad);
        if ($request->has('memberHouse')) {

            foreach ($request->memberHouse as $key => $value) {
                $value['is_currently_living'] = (isset($value['is_currently_living']) && $value['is_currently_living'] == 'on') ? true : false;
                if (($value['house_hold_type'] == ('Owner' || 'Owner Member' || 'Rental'))) {
                    if (isset($value['id'])) {
                        HouseOwner::whereId($value['id'])->update([
                            'house_id' => $value['house_id'],
                            'member_id' => $member->id,
                            'house_hold_type' => $value['house_hold_type'],
                            'is_owner' => $value['house_hold_type'] == 'Owner' ? 1 : 0,
                            'is_currently_living' => $value['is_currently_living'],
                            'created_by' => $member->created_by,
                        ]);
                    } else {
                        HouseOwner::create([
                            'house_id' => $value['house_id'],
                            'member_id' => $member->id,
                            'house_hold_type' => $value['house_hold_type'],
                            'is_owner' => $value['house_hold_type'] == 'Owner' ? 1 : 0,
                            'is_currently_living' => $value['is_currently_living'],
                            'created_by' => $member->created_by,
                        ]);
                    }

                }
            }

            $houseHoldType = HouseOwner::where([['member_id', $id], ['is_currently_living', 1]])->first();

            if ($houseHoldType) {
                Member::whereId($id)->update([
                    'house_id' => $houseHoldType->house_id,
                    'house_hold_type' => $houseHoldType->house_hold_type,
                ]);

                $member->house_id = $houseHoldType->house_id;
                $member->house_hold_type = $houseHoldType->house_hold_type;

                $OwnerMember = Member::where([['house_id', $member->house_id], ['house_hold_type', 'Owner']])->first();
                $RentalMember = Member::where([['house_id', $member->house_id], ['house_hold_type', 'Rental']])->first();
                $updateHouse = House::whereId($member->house_id)->first();

                if (($member->house_hold_type == "Owner") && ($updateHouse !== null)) {
                    House::whereId($member->house_id)->update([
                        'owner_member_id' => $OwnerMember->id ?? 0,
                        'is_currently_living' => true,
                    ]);
                }
                if (($member->house_hold_type == "Rental") && ($updateHouse !== null)) {
                    House::whereId($member->house_id)->update([
                        'rental_member_id' => $RentalMember->id,
                        'is_currently_living' => true,
                    ]);
                }
            }
        }


        if (!empty($payLoad['permanent_address'])) {
            /* $createPermanentAddress = MemberAddress::where([['id', $request->memberPermanentAddressId], ['member_id', $id]])->update([
                'permanent_address' => $payLoad['permanent_address'],
                'gaam' => $payLoad['permanent_gaam'],
                'taluka' => $payLoad['permanent_taluka'],
                'district' => $payLoad['permanent_district'],
                'state' => $payLoad['permanent_state'],
                'country' => $payLoad['permanent_country'],
                'post_code' => $payLoad['permanent_post_code'],
            ]); */

            MemberAddress::updateOrCreate([
                ['id', $request->memberPermanentAddressId],
                ['member_id', $id]
            ], [
                'member_id' => $id,
                'permanent_address' => $payLoad['permanent_address'],
                'gaam' => $payLoad['permanent_gaam'],
                'taluka' => $payLoad['permanent_taluka'],
                'district' => $payLoad['permanent_district'],
                'state' => $payLoad['permanent_state'],
                'country' => $payLoad['permanent_country'],
                'post_code' => $payLoad['permanent_post_code'],
            ]);
        }

        if (!empty($payLoad['current_address'])) {
            /* $createCurrentAddress = MemberAddress::where([['member_id', $id], ['id', $request->memberCurrentAddressId]])->update([
                'member_id' => $id,
                'is_same_as_permanent_address' => $payLoad['is_same_as_permanent_address'],
                'current_address' => $payLoad['current_address'],
                'gaam' => $payLoad['current_gaam'],
                'taluka' => $payLoad['current_taluka'],
                'district' => $payLoad['current_district'],
                'state' => $payLoad['current_state'],
                'country' => $payLoad['current_country'],
                'post_code' => $payLoad['current_post_code'],
            ]); */

            MemberAddress::updateOrCreate([
                ['id', $request->memberCurrentAddressId],
                ['member_id', $id]
            ], [
                'member_id' => $id,
                'is_same_as_permanent_address' => $payLoad['is_same_as_permanent_address'],
                'current_address' => $payLoad['current_address'],
                'gaam' => $payLoad['current_gaam'],
                'taluka' => $payLoad['current_taluka'],
                'district' => $payLoad['current_district'],
                'state' => $payLoad['current_state'],
                'country' => $payLoad['current_country'],
                'post_code' => $payLoad['current_post_code'],
            ]);
        }

        // count total member in house

        $houses = House::all();

        foreach ($houses as $key => $value) {
            $Id = $value->id;
            // $membersId = Member::where('house_id', $Id)->get()->count();
            $membersId = HouseOwner::where([['house_id', $Id], ['is_currently_living', 1]])->get()->count();

            $updateHouse = House::whereId($Id)->update([
                'total_members' => $membersId
            ]);
        }

        // added expense member

        if ($member->is_expance_member == true) {
            $ExpenseMember = ExpenseMember::create([
                'name' => $member->first_name . " " . $member->middle_name . " " . $member->last_name,
                'mobile' => $member->mobile,
                'created_by' => Auth::user()->id
            ]);

            // Update expense_id in Member table
            $member->update(['expense_id' => $ExpenseMember->id]);

        }

        return redirect()->route('member.index')->with([
            'message' => 'Member updated successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isExpenseExists = Expense::where('member_id', $id)->exists();
        $isIncomeExists = Income::where('member_id', $id)->exists();
        $isHouseExists = HouseOwner::where('member_id', $id)->exists();

        if ($isExpenseExists || $isIncomeExists || $isHouseExists) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        $member = Member::find($id);
        if ($member) {
            $memberAddresses = MemberAddress::where('member_id', $id)->get();
            $addressId = $memberAddresses->pluck('id')->toArray();

            if (!empty($addressId)) {
                $deleteMemberAddress = MemberAddress::whereIn('id', $addressId);
                $deleteMemberAddress->delete();
            }

            $member->delete();
        }

        // count total member in house

        $houses = House::all();

        foreach ($houses as $key => $value) {
            $Id = $value->id;
            // $membersId = Member::where('house_id', $Id)->get()->count();
            $membersId = HouseOwner::where([['house_id', $Id], ['is_currently_living', 1]])->get()->count();

            $updateHouse = House::whereId($Id)->update([
                'total_members' => $membersId
            ]);
        }

        $message = 'Member deleted Successfully..!';
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function fetchHouseAddress(Request $request, $id)
    {

        $house = House::find($id);

        return response()->json($house);
    }
}
