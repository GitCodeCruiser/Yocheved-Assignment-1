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

    // Add a new report
    public function addReport(Request $request)
    {
        try {
            $report = $this->reportService->addReport($request);
            return $this->sendResponse("report updated successfully", Response::HTTP_OK, $report);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    // Fetch a report
    public function getReport(Request $request)
    {
        try {
            $report = $this->reportService->getReport($request);
            return $this->sendResponse("report fetched successfully", Response::HTTP_OK, $report);
        } catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    // Generate a PDF from report and session data
    public function generatePDF(Request $request)
    {
        $session = Session::where('id', $request->session_id)->with(['rating', 'students'])->first();
        $report = Report::first();
        $html = $report->body;

        $placeholders = [
            '@student_full_name',
            '@session_date',
            '@session_minutes',
            '@session_start_time',
            '@session_end_time',
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

        $pdf = PDF::loadHTML($updatedHtml);
        $title = $report->title."pdf";

        return $pdf->download($title);
    }

    // Generate report based on session data within a date range
    public function generateReport(Request $request)
    {
        $sessions = Session::with(['students', 'rating'])
            ->where('start_date', '>=', Carbon::parse($request->start_date))
            ->where('start_date', '<=', Carbon::parse($request->end_date))
            ->get();
 
        if ($sessions->isEmpty()) {
            throw new Exception("No session found", Response::HTTP_OK);
        }
 
        $report = Report::first();
        $updatedHtml = '';
 
        foreach ($sessions as $session) {
            $startTime = Carbon::parse($session->start_time);
            $endTime = Carbon::parse($session->end_time);
            $diffInMinutes = $startTime->diffInMinutes($endTime);
            $html = $report->body;
            
            $noOfReports = ceil($diffInMinutes / $request->split);

            for ($i = 0; $i < $noOfReports; $i++) {
                $segmentStartTime = $startTime->copy()->addMinutes($i * $request->split);
                $segmentEndTime = $segmentStartTime->copy()->addMinutes(min($request->split, $diffInMinutes - $i * $request->split));
                $segmentDiffInMinutes = $segmentStartTime->diffInMinutes($segmentEndTime);

                $placeholders = [
                    '@target_start_date',
                    '@target_end_date',
                    '@session_minutes',
                    '@student_full_name',
                    '@session_date',
                    '@session_start_time',
                    '@session_end_time',
                    '@session_rating',
                    '@target',
                ];
                
                $replacements = [
                    $request->start_date ?? 'N/A',
                    $request->end_date ?? 'N/A',
                    $segmentDiffInMinutes,
                    $session->students[0]->full_name ?? 'N/A',
                    $session->start_date,
                    $diffInMinutes,
                    $startTime->format('H:i:s'),
                    $endTime->format('H:i:s'),
                    $session->rating->obtained_rating ?? 'N/A',
                    $session->rating->total_rating ?? 'N/A',
                    $session->target ?? 'N/A',
                ];
                
                // Append the updated HTML for each session segment
                if (isset($session->rating)) {
                    $updatedHtml .= str_replace($placeholders, $replacements, $html);
                }
            }
        }

        if (empty($updatedHtml)) {
            return $this->sendResponse("No session with rating found", Response::HTTP_OK, null, false);
        }

        $updatedHtml = mb_convert_encoding($updatedHtml, 'UTF-8', 'auto');
        $pdf = PDF::loadHTML($updatedHtml);
        $title = $report->title . ".pdf";

        return $pdf->download($title);
    }
}
