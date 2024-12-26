<?php

namespace App\Http\Controllers;

use App\Models\UrlLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreUrlLogRequest;
use App\Http\Requests\UpdateUrlLogRequest;

class UrlLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if the request is for DataTable
        if ($request->ajax()) {
            $logs = UrlLog::query();

            // You can add more filters here if you need
            return DataTables::of($logs)
                // ->modifyColumn('')
                // ->addColumn('action', function($log) {
                //     // Add actions like Edit/Delete buttons, etc.
                //     return '<a href="'.route('logs.show', $log->id).'" class="btn btn-primary">View</a>';
                // })
                ->make(true);
        }

        return view('admin.url-log.index');
    }

    public function statistic()
    {
        $dayAfterDate = Carbon::now()->subDays(180);// history from how many days ago
        $daysAfterDateString = $dayAfterDate->format('Y-m-d');
        $days = [];
        $visitedUrls = \DB::select("
            SELECT
                COUNT(DATE_FORMAT(created_at, '%Y-%m-%d')) AS day
            FROM `url_logs`
            WHERE `created_at` > '$daysAfterDateString'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d ');
        ");
        $labelString = '';
        $dataString = '';
        $i = count($visitedUrls);
        foreach($visitedUrls as $key => $value){
            $i--;
            $labelString .= Carbon::now()->subDays($i)->format('Y-m-d') . ',';
            $dataString .= $value->day . ',';
        }
        $labelString = substr($labelString, 0, strlen($labelString) - 1);
        $dataString = substr($dataString, 0, strlen($dataString) - 1);

        return view('admin.url-log.statistics', compact('labelString', 'dataString'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUrlLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UrlLog $urlLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UrlLog $urlLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUrlLogRequest $request, UrlLog $urlLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UrlLog $urlLog)
    {
        //
    }
}
