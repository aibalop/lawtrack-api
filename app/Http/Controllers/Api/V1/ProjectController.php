<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $all = Project::all();

        return response()->json($all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:50'],
            'description' => ['required', 'max:500'],
            'status' => ['required', 'size:1'],
            'lawyer_id' => ['required', 'exists:App\Models\Lawyer,id'],
            'customer_id' => ['required', 'exists:App\Models\Customer,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['name', 'description', 'status', 'lawyer_id', 'customer_id']);

        if (!Lawyer::find($data['lawyer_id'])->customers->where('id', $data['customer_id'])->first()) {
            return response()->json(['error' => ['Customer don´t belong to lawyer']], RESPONSE::HTTP_BAD_REQUEST);
        }

        $code_number = (Project::withTrashed()->count() + 1) + 1000;

        $data['code'] = 'PJ-' . $code_number;

        $project = Project::create($data);

        return response()->json($project);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Project::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'max:50'],
            'description' => ['sometimes', 'required', 'max:500'],
            'status' => ['sometimes', 'required', 'size:1']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['name', 'description', 'status']);

        $project->update($data);

        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        try {
            $project->delete();
            return response()->json($project);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error to delete'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
