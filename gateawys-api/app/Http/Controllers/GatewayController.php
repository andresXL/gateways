<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Gateway::orderBy('updated_at', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_gateway = new Gateway;

        $new_gateway->serial_number = $request['serial_number'];
        $new_gateway->name = $request['name'];
        $new_gateway->ipv4_address = $request['ipv4_address'];
        $new_gateway->save();
        return $new_gateway;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gateway = Gateway::find($id);
        if($gateway != NULL)
            return $gateway;
        return response()->json(['error' => 'record not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gateway = Gateway::find($id);
        if($gateway != NULL){
            if($request['serial_number'])
                $gateway->serial_number = $request['serial_number'];
            if($request['name'])
                $gateway->serial_number = $request['name'];
            if($request['ipv4_address'])
                $gateway->ipv4_address = $request['ipv4_address'];
            $gateway->save();
            return $gateway;
        }
        else
            return response()->json(['error' => 'record not found'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gateway = Gateway::find($id);
        if($gateway != NULL){
            $gateway->delete();
            return response()->json (['success' => 'item deleted'], 201);
        }
        else
            return response()->json(['error' => 'record not found'], 404);
    }
}
