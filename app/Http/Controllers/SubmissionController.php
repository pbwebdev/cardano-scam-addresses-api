<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Http\Resources\SubmissionCollection;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = new SubmissionCollection(Submission::paginate(request('per_page', 100)));

        return $data->preserveQuery()->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubmissionRequest  $request
     *
     * @return JsonResponse
     */
    public function store(SubmissionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $submission = Submission::create($data);

        $resource = new SubmissionResource($submission);

        return $resource->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  Submission  $submission
     *
     * @return JsonResponse
     */
    public function show(Submission $submission): JsonResponse
    {
        $resource = new SubmissionResource($submission);

        return $resource->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SubmissionRequest  $request
     * @param  Submission         $submission
     *
     * @return JsonResponse
     */
    public function update(SubmissionRequest $request, Submission $submission): JsonResponse
    {
        $data = $request->validated();

        $submission->update($data);

        $resource = new SubmissionResource($submission);

        return $resource->response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Submission  $submission
     *
     * @return JsonResponse
     */
    public function destroy(Submission $submission): JsonResponse
    {
        $submission->delete();

        return new JsonResponse([], 204);
    }
}