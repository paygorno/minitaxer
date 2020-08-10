<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActionRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ShortenedAction;
use App\Http\Resources\DetailedAction;
use App\Action\ActionService;

class ActionController extends Controller
{
    protected ActionService $actionService;
    protected const ACTIONS_PER_PAGE = 20;

    public function __construct(
        ActionService $actionService
    )
    {
        $this->actionService = $actionService;
    }

    public function index(Request $request)
    {
        $page = $request->input('page');
        
        $collection = $this->actionService->indexFiltered(
            $request->user(),
            $request->input('startDate'),
            $request->input('endDate'),
            $request->input('currencies') 
                ? explode(',', $request->input('currencies'))
                : null,
            $request->input('types')
                ? explode(',', $request->input('types'))
                : ['income', 'exchange', 'forceExchange']
        );

        return ShortenedAction::collection(
            $collection->forPage($page, static::ACTIONS_PER_PAGE)->collect()
        );
    }

    public function store(ActionRequest $request)
    {
        if (
            $this->actionService->isPossibleToCreateAction(
                $request->user(),
                $request->input('type'),
                $request->input('currencyCode'),
                $request->input('date'),
                $request->input('amount'),
                $request->input('rate')
            )
        ) {
            $this->actionService->create(
                $request->user(),
                $request->input('type'),
                $request->input('currencyCode'),
                $request->input('date'),
                $request->input('amount'),
                $request->input('rate')
            );
            return response([
                'message' => 'Successfully created'
            ], 200);
        } else {
            $errorMessages = [''];
            return response([
                'message' => 'Creation failed',
                'errors' => $errorMessages
            ], 400);
        }
    }

    public function show($action)
    {
        if (Gate::allows('access-action', $action)) {
            return new DetailedAction($action);
        } else {
            return response([
                'message' => 'Unauthorised',
                'errors' => ['You are not authorised for such an action']
            ], 401);
        }
    }

    public function destroy($action)
    {
        if (Gate::allows('access-action', $action)) {
            $this->actionService->delete($action);
            return response([
                'message' => 'Successfully deleted'
            ], 200);
        } else {
            return response([
                'message' => 'Unauthorised',
                'errors' => ['You are not authorised for such an action']
            ], 401);
        }
    }
}
