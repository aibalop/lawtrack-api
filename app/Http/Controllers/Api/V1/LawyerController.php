<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class LawyerController extends Controller
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
    public function index(): \Illuminate\Http\JsonResponse
    {
        $all = Lawyer::all();

        return response()->json($all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'email', 'unique:lawyers'],
            'zip' => ['required', 'size:5'],
            'license' => ['sometimes', 'unique:lawyers'],
            'user_id' => ['sometimes', 'exists:App\Models\User,id'],
            'phone' => ['sometimes', 'unique:lawyers', 'size:10'],
            'rfc' => ['sometimes', 'unique:lawyers'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['name', 'lastname', 'surname', 'email', 'phone', 'address', 'zip', 'license', 'rfc', 'web_url', 'user_id']);

        if (!$request->exists('user_id')) {
            $data['user_id'] = Auth()->id();
        }

        $code_number = (Lawyer::withTrashed()->count() + 1) + 1000;

        $data['code'] = 'LW-' . $code_number;

        $lawyer = Lawyer::create($data);

        return response()->json($lawyer);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Lawyer::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $lawyer = Lawyer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required'],
            'lastname' => ['sometimes', 'required'],
            'email' => ['sometimes', 'required', 'email'],
            'zip' => ['sometimes', 'required', 'size:5']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['name', 'lastname', 'surname', 'email', 'phone', 'address', 'zip', 'license', 'rfc', 'web_url']);

        $rules = [
            'email' => ['sometimes', Rule::unique('lawyers')->where(function ($query) use ($lawyer) {
                return $query->where('id', '<>', $lawyer->id);
            })],
            'phone' => ['sometimes', 'size:10', Rule::unique('lawyers')->where(function ($query) use ($lawyer) {
                return $query->where('id', '<>', $lawyer->id);
            })],
            'rfc' => ['sometimes', Rule::unique('lawyers')->where(function ($query) use ($lawyer) {
                return $query->where('id', '<>', $lawyer->id);
            })],
            'license' => ['sometimes', Rule::unique('lawyers')->where(function ($query) use ($lawyer) {
                return $query->where('id', '<>', $lawyer->id);
            })]
        ];

        $validator_extra = Validator::make($data, $rules);

        if ($validator_extra->fails()) {
            return response()->json($validator_extra->errors(), Response::HTTP_BAD_REQUEST);
        }

        $lawyer->update($data);

        return response()->json($lawyer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $lawyer = Lawyer::findOrFail($id);
        try {
            $lawyer->delete();
            return response()->json($lawyer);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error to delete'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
