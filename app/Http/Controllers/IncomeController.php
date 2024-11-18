<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\House;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Income');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;

            $data = Income::select(DB::raw('incomes.id, incomes.financial_year, incomes.paid_date, incomes.amount, incomes.payment_type, incomes.no_of_year, incomes.from_date, incomes.to_date, incomes.created_at, incomes.created_by, incomes.to_date, incomes.income_category_id, houses.house_no, income_categories.name as income_category, members.first_name, members.middle_name, members.last_name, members.customer_no'))
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

            if ($request->financialYear) {
                $data->where('incomes.financial_year', $request->financialYear);
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
            $financial_years = Income::select('financial_year')->groupBy('financial_year')->get()->pluck('financial_year');
            return view('income.index', compact('allData', 'request', 'members', 'income_categories', 'financial_years'));
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
        $houses = House::all();
        $income_categories = IncomeCategory::all();
        $banks = Bank::all();
        return view('income.create', compact('members',  'houses', 'income_categories', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'member_id' => ['required'],
            'income_category_id' => ['required'],
            'amount' => ['required'],
            'payment_type' => ['required'],
            'paid_date' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($params);
        }

        /* $start_year = date('Y') . '-04-01';

        if (date('Y-m-d') > $start_year) {
            $financialYear = date('y') . '-' . date('y', strtotime('+1 year', strtotime($start_year)));
        } else {
            $financialYear = date('y', strtotime('-1 year', strtotime($start_year))) . '-' . date('y');
        }

        $params['financial_year'] = $financialYear; */
        $params['transaction_date'] = ($request->transaction_date) ? \DateTime::createFromFormat('d/m/Y', $params['transaction_date'])->format('Y-m-d') : null;
        $params['paid_date'] = ($request->paid_date) ? \DateTime::createFromFormat('d/m/Y', $params['paid_date'])->format('Y-m-d') : null;
        $params['from_date'] = ($request->from_date) ? \DateTime::createFromFormat('d/m/Y', $params['from_date'])->format('Y-m-d') : null;
        $params['to_date'] = ($request->to_date) ? \DateTime::createFromFormat('d/m/Y', $params['to_date'])->format('Y-m-d') : null;
        $params['is_taxable'] = ($request->is_taxable && $params['is_taxable'] == 'on') ? true : false;
        $params['is_late_paid'] = ($request->is_late_paid && $params['is_late_paid'] == 'on') ? true : false;

        $params['created_by'] = Auth::user()->id;

        $total_years = $params['no_of_year'];
        if ($params['income_category_id'] != 2) {

            if ($params['from_date'] && $params['to_date']) {
                $start_date = new \DateTime($params['from_date']);
                $end_date = new \DateTime($params['to_date']);

                $start_year = $start_date->format('y');
                $end_year = $end_date->format('y');

                /* for ($year = $start_year; $year <= $end_year; $year++) { */
                $from_date = $start_year . '-01-01';
                $to_date = $end_year . '-12-31';

                $params['financial_year'] = $start_year . '-' . ($end_year);
                $params['from_date'] = $from_date;
                $params['to_date'] = $to_date;

                Income::create($params);
                /* } */
            }
        }

        /* $params['from_date'] = date('Y-m-d');
        for ($value = 1; $value < $total_years + 1; $value++) {
            $params['to_date'] = date('Y-m-d', strtotime('+'.$value.' year'));
            Income::create($params);
            $params['from_date'] = $params['to_date'];
        } */

        // $params['from_date'] = ($request->from_date) ? \DateTime::createFromFormat('d/m/Y', $params['from_date'])->format('Y-m-d') : null;
        // if ($params['income_category_id'] != 2) {
        //     // $params['from_date'] = date('Y') . '-01-01';

        //     for ($value = 0; $value < $total_years; $value++) {
        //         $from_date = new \DateTime($params['from_date']);
        //         $from_date->modify('+1 year');
        //         $to_date = $from_date->format('Y-m-d');
        //         $params['to_date'] = $from_date->modify('-1 day')->format('Y-m-d');

        //         $start_year = date('Y') . '-04-01';


        //         if (date('Y-m-d') > $start_year) {
        //             $financialYear = date('y') + $value . '-' . date('y', strtotime('+1 year', strtotime($start_year))) + $value;
        //             // dd($financialYear);
        //         } else {
        //             $financialYear = date('y', strtotime('-1 year', strtotime($start_year))) + $value . '-' . date('y') + $value;
        //         }

        //         $params['financial_year'] = $financialYear;
        //         Income::create($params);
        //         $params['from_date'] = $to_date;
        //     }
        // }
        else {
            $start_year = date('Y') . '-05-01';

            if (now() > $start_year) {
                $financialYear = date('y') . '-' . date('y', strtotime('+1 year', strtotime($start_year)));
            } else {
                $financialYear = date('y', strtotime('-1 year', strtotime($start_year))) . '-' . date('y');
            }

            $params['financial_year'] = $financialYear;
            Income::create($params);
        }

        Session::flash('success', 'Income Added Successfully..!');
        return redirect('income')->with([
            'message' => 'Income added successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $income = Income::whereId($id)->first();
        return view('income.show', compact('income'));
    }

    public function receipt(string $id)
    {
        $data = Income::whereId($id)->first();
        $fromDate = Income::where([['house_id', $data->house_id], ['paid_date', $data->paid_date]])->orderBy('id', 'asc')->pluck('from_date')->first();
        $toDate = Income::where([['house_id', $data->house_id], ['paid_date', $data->paid_date]])->orderBy('id', 'desc')->pluck('to_date')->first();
        $amountInWords = $this->getIndianCurrency($data->amount);

        $minmaxYear = Income::where([
            ['house_id', $data->house_id],
            ['paid_date', $data->paid_date],
            ['income_category_id', $data->income_category_id]
        ])->select(DB::raw('MIN(SUBSTRING_INDEX(financial_year, "-", 1)) as min_year, MAX(SUBSTRING_INDEX(financial_year, "-", -1)) as max_year'))->first();
        $minYear = $minmaxYear->min_year ?? null;
        $maxYear = $minmaxYear->max_year ?? null;

        return view('income.receipt', compact('data', 'amountInWords', 'fromDate', 'toDate', 'minYear', 'maxYear'));
    }

    public function donation(string $id)
    {
        $data = Income::whereId($id)->first();
        $amountInWords = $this->getIndianCurrency($data->amount);
        return view('income.donation', compact('data', 'amountInWords'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $income = Income::whereId($id)->first();
        $members = Member::all();
        $houses = House::all();
        $income_categories = IncomeCategory::all();
        return view('income.update', compact('income', 'members', 'houses', 'income_categories'));
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
            // 'member_id' => ['required'],
            'income_category_id' => ['sometimes', 'required'],
            // 'amount' => ['required'],
            'payment_type' => ['required'],
            'paid_date' => ['required'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($payLoad);
        }

        $payLoad['transaction_date'] = ($request->transaction_date) ? \DateTime::createFromFormat('d/m/Y', $payLoad['transaction_date'])->format('Y-m-d') : null;
        $payLoad['paid_date'] = ($request->paid_date) ? \DateTime::createFromFormat('d/m/Y', $payLoad['paid_date'])->format('Y-m-d') : null;
        $payLoad['from_date'] = ($request->from_date) ? \DateTime::createFromFormat('d/m/Y', $payLoad['from_date'])->format('Y-m-d') : null;
        $payLoad['to_date'] = ($request->to_date) ? \DateTime::createFromFormat('d/m/Y', $payLoad['to_date'])->format('Y-m-d') : null;
        $payLoad['is_taxable'] = ($request->is_taxable && $payLoad['is_taxable'] == 'on') ? true : false;
        $payLoad['is_late_paid'] = ($request->is_late_paid && $payLoad['is_late_paid'] == 'on') ? true : false;

        $income = Income::find($id);
        $payLoad['income_category_id'] = $income->income_category_id;
        if ($payLoad['income_category_id'] != 2) {

            if ($payLoad['from_date'] && $payLoad['to_date']) {
                $start_date = new \DateTime($payLoad['from_date']);
                $end_date = new \DateTime($payLoad['to_date']);

                $start_year = $start_date->format('y');
                $end_year = $end_date->format('y');

                /* for ($year = $start_year; $year <= $end_year; $year++) { */
                    $from_date = $start_year . '-01-01';
                    $to_date = $end_year . '-12-31';

                    $payLoad['financial_year'] = $start_year . '-' . ($end_year);
                    $payLoad['from_date'] = $from_date;
                    $payLoad['to_date'] = $to_date;

                    $income->update($payLoad);
                /* } */
            }
        } else {
            $start_year = date('Y') . '-05-01';

            if (now() > $start_year) {
                $financialYear = date('y') . '-' . date('y', strtotime('+1 year', strtotime($start_year)));
            } else {
                $financialYear = date('y', strtotime('-1 year', strtotime($start_year))) . '-' . date('y');
            }

            $payLoad['financial_year'] = $financialYear;
            $income->update($payLoad);
        }


        // $income = Income::find($id);
        // $income->update($payLoad);

        return redirect()->route('income.index')->with([
            'message' => 'Income updated successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $income = Income::find($id);
        $income->delete();
        $message = 'Income deleted Successfully..!';
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function fetchHouseMember(Request $request)
    {
        if ($request->house_id > 0) {
            // dd($request->house_id);
            $members = Member::where('house_id', $request->house_id)->get();
        } else {
            $members = Member::all();
        }

        return response()->json($members);
    }

    function getIndianCurrency(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'એક', 2 => 'બે', 3 => 'ત્રણ', 4 => 'ચાર', 5 => 'પાંચ', 6 => 'છ', 7 => 'સાત', 8 => 'આઠ', 9 => 'નવ',
            10 => 'દસ', 11 => 'અગિયાર', 12 => 'બાર', 13 => 'તેર', 14 => 'ચૌદ', 15 => 'પંદર', 16 => 'સોળ', 17 => 'સત્તર', 18 => 'અઢાર', 19 => 'ઓગણિસ',
            20 => 'વીસ', 21 => 'અગિયાર', 22 => 'બાવીસ', 23 => 'તેવીસ', 24 => 'ચોવીસ', 25 => 'પચ્ચીસ', 26 => 'છવીસ', 27 => 'સત્તાવીસ', 28 => 'અઠ્ઠાવીસ', 29 => 'ઓગણત્રીસ',
            30 => 'ત્રીસ', 31 => 'એકત્રીસ', 32 => 'બત્રીસ', 33 => 'તેત્રીસ', 34 => 'ચોત્રીસ', 35 => 'પાંત્રીસ', 36 => 'છત્રીસ', 37 => 'સડત્રીસ', 38 => 'અડત્રીસ', 39 => 'ઓગણચાલીસ',
            40 => 'ચાલીસ', 41 => 'એકતાલીસ ', 42 => 'બેતાલીસ ', 43 => 'ત્રેતાલીસ ', 44 => 'ચુંમાલીસ ', 45 => 'પિસ્તાલીસ ', 46 => 'છેતાલીસ ', 47 => 'સુડતાલીસ ', 48 => 'અડતાલીસ ', 49 => 'ઓગણપચાસ ',
            50 => 'પચાસ', 51 => 'એકાવન ', 52 => 'બાવન ', 53 => 'ત્રેપન ', 54 => 'ચોપન ', 55 => 'પંચાવન ', 56 => 'છપ્પન ', 57 => 'સત્તાવન ', 58 => 'અઠ્ઠાવન ', 59 => 'ઓગણસાઠ ',
            60 => 'સાઈઠ', 61 => 'એકસઠ ', 62 => 'બાસઠ ', 63 => 'ત્રેસઠ ', 64 => 'ચોસઠ ', 65 => 'પાંસઠ ', 66 => 'છાસઠ ', 67 => 'સડસઠ ', 68 => 'અડસઠ ', 69 => 'અગણોસિત્તેર ',
            70 => 'સિત્તેર', 71 => 'એકોતેર ', 72 => 'બોતેર ', 73 => 'તોતેર ', 74 => 'ચુમોતેર ', 75 => 'પંચોતેર ', 76 => 'છોતેર ', 77 => 'સિત્યોતેર ', 78 => 'ઇઠ્યોતેર ', 79 => 'ઓગણાએંસી ',
            80 => 'એંસી', 81 => 'એક્યાસી ', 82 => 'બ્યાસી ', 83 => 'ત્યાસી ', 84 => 'ચોર્યાસી ', 85 => 'પંચાસી ', 86 => 'છ્યાસી ', 87 => 'સિત્યાસી ', 88 => 'ઈઠ્યાસી ', 89 => 'નેવ્યાસી ',
            90 => 'નેવું', 91 => 'એકાણું ', 92 => 'બાણું ', 93 => 'ત્રાણું ', 94 => 'ચોરાણું ', 95 => 'પંચાણું ', 96 => 'છન્નું ', 97 => 'સત્તાણું ', 98 => 'અઠ્ઠાણું ', 99 => 'નવ્વાણું ',
        );
        $digits = array('', 'સો', 'હજાર', 'લાખ', 'કરોડ઼');
        while ($i < $digits_length) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                // $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 100) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' પૈસા' : '';
        return ($Rupees ? $Rupees . 'પૂરા ' : '') . $paise;
    }
}
