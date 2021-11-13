<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
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
        $all = Customer::all();

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
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'email'],
            'zip' => ['required', 'size:5'],
            'lawyer_id' => ['required', 'exists:App\Models\Lawyer,id'],
            'phone' => ['sometimes', 'size:10'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['name', 'lastname', 'surname', 'email', 'phone', 'address', 'zip', 'rfc', 'lawyer_id']);

        $rules = [
            'email' => [Rule::unique('customers')->where(function ($query) use ($data) {
                return $query->where('lawyer_id', $data['lawyer_id']);
            })],
            'phone' => ['sometimes', Rule::unique('lawyers')->where(function ($query) use ($data) {
                return $query->where('lawyer_id', $data['lawyer_id']);
            })],
            'rfc' => ['sometimes', Rule::unique('lawyers')->where(function ($query) use ($data) {
                return $query->where('lawyer_id', $data['lawyer_id']);
            })],
        ];

        $validator_extra = Validator::make($data, $rules);

        if ($validator_extra->fails()) {
            return response()->json($validator_extra->errors(), Response::HTTP_BAD_REQUEST);
        }

        $code_number = (Customer::withTrashed()->count() + 1) + 1000;

        $data['code'] = 'CU-' . $code_number;

        $customer = Customer::create($data);

        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Customer::findOrFail($id);
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
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required'],
            'lastname' => ['sometimes', 'required'],
            'email' => ['sometimes', 'required', 'email'],
            'zip' => ['sometimes', 'required', 'size:5']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['name', 'lastname', 'surname', 'email', 'phone', 'address', 'zip', 'rfc']);

        $rules = [
            'email' => ['sometimes', Rule::unique('customers')->where(function ($query) use ($customer) {
                return $query->where('lawyer_id', $customer->lawyer_id)->where('id', '<>', $customer->id);
            })],
            'phone' => ['sometimes', 'size:10', Rule::unique('customers')->where(function ($query) use ($customer) {
                return $query->where('lawyer_id', $customer->lawyer_id)->where('id', '<>', $customer->id);
            })],
            'rfc' => ['sometimes', Rule::unique('customers')->where(function ($query) use ($customer) {
                return $query->where('lawyer_id', $customer->lawyer_id)->where('id', '<>', $customer->id);
            })],
        ];

        $validator_extra = Validator::make($data, $rules);

        if ($validator_extra->fails()) {
            return response()->json($validator_extra->errors(), Response::HTTP_BAD_REQUEST);
        }

        $customer->update($data);

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        try {
            $customer->delete();
            return response()->json($customer);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error to delete'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
