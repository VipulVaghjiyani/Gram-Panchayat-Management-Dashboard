<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Member;
use App\Models\HouseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:House');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = House::select(DB::raw('houses.id, houses.house_no, houses.total_members, houses.created_at, houses.created_by'))->join('users', 'created_by', 'users.id')->orderBy('houses.id', 'DESC');

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
                    $w->orWhere('house_no', 'LIKE', "%$search%")
                        ->orWhere('total_members', 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%")
                        ->orWhere(DB::raw("CONCAT(first_name, ' ', middle_name)"), 'LIKE', "%$search%")
                        ->orWhere('first_name', 'LIKE', "%$search%")
                        ->orWhere('middle_name', 'LIKE', "%$search%")
                        ->orWhere('last_name', 'LIKE', "%$search%")
                        ->orWhereRaw("DATE_FORMAT(houses.created_at, '%d/%m/%Y') LIKE ?", ["%$search%"]);
                });
            }

            $allData = $data->orderBy('houses.id', 'DESC')->paginate($request->page_length ?? 10);
            $members = Member::all();
            $house = House::all();
            return view('house.index', compact('allData', 'request', 'members', 'house'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('house.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'house_no' => ['required', 'unique:houses,house_no'],
            'address' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $params['created_by'] = Auth::user()->id;

        $createRecord = House::create($params);

        if ($createRecord) {
            Session::flash('success', 'House Added Successfully..!');
            return redirect('house')->with([
                'message' => 'House added successfully!',
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
        $house = House::whereId($id)->first();
        $houseMember = HouseOwner::where('house_id',$id)->get();
        $members = Member::where('house_id', $house->id)->get();
        // dd($houseMember);
        return view('house.show', compact('house', 'members', 'houseMember'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $house = House::whereId($id)->first();
        return view('house.update', compact('house'));
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
            'house_no' => ['required', 'unique:houses,house_no,' .$id],
            'address' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $house = House::find($id);
        $house->update($payLoad);

        return redirect()->route('house.index')->with([
            'message' => 'House updated successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isMemberExists = Member::where('house_id', $id)->exists();
        $isHouseExists = HouseOwner::where('house_id', $id)->exists();

        if ($isMemberExists || $isHouseExists) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        $house = House::find($id);
        $house->delete();
        $message = 'House deleted Successfully..!';
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function removeHouseMember(Request $request,$id)
    {
        $houseOwner = HouseOwner::find($id);
        $currentLivingHouse = HouseOwner::where([['id', $id], ['is_currently_living', 1]])->exists();

        if ($currentLivingHouse) {
            return response()->json([
                'status' => false,
                'message' => 'This is already in Use'
            ]);
        }

        House::where('id',$request->house_id)->update([
            'owner_member_id' => 0,
        ]);

        $houseOwner->delete();

        return response()->json([
            'status' => true,
            'message' => 'House removed successfully!!!'
        ]);
    }

    public function fetchOwner($id)
    {
        $house = House::find($id);
        $houseOwner = HouseOwner::where([['house_id', $house->id], ['is_owner', 1]])->first();
        $members = Member::all();

        return response()->json([
            'houseOwner' => $houseOwner,
            'members' => $members,
            'house' => $house,
        ]);
    }

    public function changeOwner(Request $request, $id)
    {
        $params = $request->all();
        unset($params['_token']);
        $memberId = $params['member_id'];
        $houseId = $params['house_id'];
        $params['created_by'] = Auth::user()->id;

        $matchThese = ['member_id' => $memberId, 'house_id' => $houseId];

        HouseOwner::where('house_id', $houseId)->update(['is_owner'=>0]);
        HouseOwner::updateOrCreate($matchThese, $params);

        return redirect('house')->with([
            'status' => 'success',
            'message' => 'House owner updated successfully.'
        ]);
    }

    public function fetchAllHouses()
    {
        $houses = House::all();
        return response()->json($houses);
    }
}
