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

    public function getSession(){
        try 
        {
            $sessions = $this->sessionService->getSession();
            return $this->sendResponse("Session fetched successfully", Response::HTTP_OK, $sessions);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
}
