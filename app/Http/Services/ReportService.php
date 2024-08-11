<?php
namespace App\Http\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\Session;
    use Barryvdh\DomPDF\Facade\Pdf; 
use Symfony\Component\HttpFoundation\Response;

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