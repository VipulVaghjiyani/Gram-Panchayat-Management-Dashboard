<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Activity Log');
    }

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $from = ($request->from) ? \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d') : null;
            $to = ($request->to) ? \DateTime::createFromFormat('d/m/Y', $request->to)->format('Y-m-d') : null;
            $data = ActivityLog::orderBy('id', 'DESC')->limit(100);

            if ($request->log_name) {
                $data->where('log_name', $request->log_name);
            }

            if ($from != null && $to != null) {
                $data->whereBetween('created_at', ["$from" . ' 00:00:00', "$to" . ' 23:59:59']);
            } else {
                if ($from != null) {
                    $data->where('created_at', '>=', "$from");
                }
            }

            $allData = $data->get();

            return datatables()->of($allData)
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d/m/Y'); // human readable format
                })
                ->editColumn('causer_id', function ($request) {
                    return $request->user->full_name ?? ''; // human readable format
                })
                ->editColumn('properties', function ($request) {
                    $activityLog = json_decode($request->properties, true); // Decode the JSON string

                    if (isset($activityLog['old']) && isset($activityLog['attributes'])) {
                        // dd($activityLog);
                        $attributesString = '';

                        // Iterate over each attribute and construct the string
                        foreach ($activityLog['attributes'] as $key => $value) {
                            $attributesString .= $key . ' - ' . $value . ', ';
                        }

                        // Remove the trailing comma and space
                        $attributesString = 'New:- ' . rtrim($attributesString, ', ');

                        $oldsString = '';

                        // Iterate over each attribute and construct the string
                        foreach ($activityLog['old'] as $key => $value) {
                            $oldsString .= $key . ' - ' . $value . ', ';
                        }

                        // Remove the trailing comma and space
                        $oldsString = 'Old:- ' . rtrim($oldsString, ', ');

                        // dd($attributesString);

                        return [$attributesString, $oldsString];
                    }

                    // Check if 'attributes' key exists
                    else if (isset($activityLog['attributes'])) {
                        $attributesString = '';

                        // Iterate over each attribute and construct the string
                        foreach ($activityLog['attributes'] as $key => $value) {
                            $attributesString .= $key . ' - ' . $value . ', ';
                        }

                        // Remove the trailing comma and space
                        $attributesString = rtrim($attributesString, ', ');

                        return $attributesString;
                    } else {
                        $attributesString = '';

                        // Iterate over each attribute and construct the string
                        foreach ($activityLog['old'] as $key => $value) {
                            $attributesString .= $key . ' - ' . $value . ', ';
                        }

                        // Remove the trailing comma and space
                        $attributesString = rtrim($attributesString, ', ');

                        return $attributesString; // Handle case where 'attributes' is missing
                    }

                })
                ->addIndexColumn()
                ->make(true);
        }

        $modules = ActivityLog::pluck('log_name')->unique();

        return view('activity_log.index', compact('modules'));
    }
}
