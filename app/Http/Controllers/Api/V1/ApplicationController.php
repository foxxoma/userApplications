<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Api\V1\Applications\IndexFormRequest;
use App\Http\Requests\Api\V1\Applications\StoreFormRequest;
use App\Http\Requests\Api\V1\Applications\CommentFormRequest;

use App\Http\Resources\Api\V1\ApplicationResource;
use App\Services\ApplicationService;

use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['store']);
        $this->middleware('role:admin,editor')->except(['store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexFormRequest $request, ApplicationService $service)
    {
        $applications = $service->index($request->validated());

        return ApplicationResource::collection($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request, ApplicationService $service)
    {
        $application = $service->store($request->validated());

        return new ApplicationResource($application);
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationService $service, Application $application)
    {
        $application = $service->show($application);

        return new ApplicationResource($application);
    }

    /**
     * comment resource.
     */
    public function comment(CommentFormRequest $request, ApplicationService $service, Application $application)
    {
        $application = $service->comment($request->validated(), $application);

        return new ApplicationResource($application);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationService $service, Application $application)
    {
        return $service->destroy($application);
    }
}
