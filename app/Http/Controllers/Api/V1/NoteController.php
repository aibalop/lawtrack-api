<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
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
        $all = Note::all();

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
            'title' => ['required', 'max:50'],
            'content' => ['required', 'max:500'],
            'project_id' => ['required', 'exists:App\Models\Project,id'],
            'notable_id' => ['required'],
            'notable_type' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['title', 'content', 'project_id', 'notable_id', 'notable_type']);

        $note = Note::create($data);

        return response()->json($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Note::findOrFail($id);
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
        $note = Note::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'max:50'],
            'content' => ['sometimes', 'required', 'max:500'],
            'viewed' => ['sometimes', 'boolean']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['title', 'content', 'viewed']);

        if (array_key_exists('viewed', $data)) {
            $data['viewed_at'] = Carbon::now();
        }

        $note->update($data);

        return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        try {
            $note->delete();
            return response()->json($note);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error to delete'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
