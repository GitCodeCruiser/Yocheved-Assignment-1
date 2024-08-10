<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Services\SessionService;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    private $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    public function addSession(Request $request){
        try 
        {
            $session = $this->sessionService->addSession($request);
            return $this->sendResponse("Session added successfully", Response::HTTP_OK, $session);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function getSessions(){
        try 
        {
            $sessions = $this->sessionService->getSessions();
            return $this->sendResponse("Session fetched successfully", Response::HTTP_OK, $sessions);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function scheduleStudent(Request $request){
        try 
        {
            $studentSchedule = $this->sessionService->scheduleStudent($request);
            return $this->sendResponse("Student scheduled successfully", Response::HTTP_OK, $studentSchedule);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function addSessionRating(Request $request){
        try 
        {
            $rating = $this->sessionService->rateSession($request);
            return $this->sendResponse("Rating added successfully", Response::HTTP_OK, $rating);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }

    public function getSession(Request $request){
        try 
        {
            $rating = $this->sessionService->getSession($request);
            return $this->sendResponse("Session fetched successfully", Response::HTTP_OK, $rating);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
    public function addMultipleSessions(Request $request){
        try 
        {
            $session = $this->sessionService->addMultipleSessions($request);
            return $this->sendResponse("Session fetched successfully", Response::HTTP_OK, $session);
        } 
        catch (Exception $exception) {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
}
