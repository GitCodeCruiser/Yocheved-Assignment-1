<?php
namespace App\Http\Services;

use App\Models\Report;

class ReportService{
    public function addReport($request){
        $report = Report::first();
        
        if($report){
            $report = $report->update([
                'title' => $request->title,
                'body' => $request->body
            ]);
        }
        else{
            $report = Report::create([
                'title' => $request->title,
                'body' => $request->body
            ]);
        }

        return $report;
    }

    public function getReport(){
        $report = Report::first();
        return $report;
    }
}