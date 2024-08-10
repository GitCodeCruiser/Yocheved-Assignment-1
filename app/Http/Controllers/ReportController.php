<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\Session;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Services\ReportService;

class ReportController extends Controller
{
    private $reportService;
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function addReport (Request $request){
        try 
        {
            $report = $this->reportService->addReport($request);
            return $this->sendResponse("report updated successfully", Response::HTTP_OK, $report);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function getReport(Request $request){
        try 
        {
            $report = $this->reportService->getReport($request);
            return $this->sendResponse("report fetched successfully", Response::HTTP_OK, $report);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function generatePDF(Request $request){
        $session = Session::where('id', $request->session_id)->with(['rating', 'students'])->first();
        $report = Report::first();
        $html = $report->body;

        $placeholders = [
            '@student_full_name',
            '@session_date',
            '@session_minutes',
            '@session_start_time',
            '@session_end_time',
            // '@target_start_date',
            // '@target_end_date',
            // '@target_end_date',
            '@session_rating',
        ];
        
        $startTime = Carbon::parse($session->start_date_time);
        $endTime = Carbon::parse($session->end_date_time);
        $sessionTime = $startTime->diff($endTime);

        $formattedDiff = $sessionTime->format('%h hours %i minutes');

        $replacements = [
            $session->students[0]->full_name,
            $startTime->format('Y-m-d'),
            $formattedDiff,
            $startTime->format('Y-m-d H:i:s'),
            $endTime->format('Y-m-d H:i:s'),
            $session->rating->obtained_rating
        ];
        
        $updatedHtml = str_replace($placeholders, $replacements, $html);

        $html = $report->body;

        $pdf = PDF::loadHTML($updatedHtml);
        $title = $report->title."pdf";

        return $pdf->download($title);
    }
}
