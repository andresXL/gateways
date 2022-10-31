<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peripheral;

class PeripheralController extends Controller
{

    private $peripheral_status = [
        "on" => "online",
        "off" => "offline",
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request['gateway'])
            return Peripheral::where('gateway_id', $request['gateway']) -> get();
        return Peripheral::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $size_list =  count(Peripheral::where('gateway_id', $request['gateway_id']) -> get());
        
        if($size_list == 10)
            return response()->json(['error' => 'This gateway alredy has 10 peripherals'], 404);
        $new_peripheral = new Peripheral;
        $new_peripheral->gateway_id = $request['gateway_id'];
        $new_peripheral->vendor = $request['vendor'];
        $new_peripheral->status = $this->peripheral_status['on'];
        $new_peripheral->save();
        return $new_peripheral;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $peripheral = Peripheral::find($id);
        if($peripheral != NULL)
            return $peripheral;
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
        $peripheral = Peripheral::find($id);
        if($peripheral != NULL){
            if($request['vendor'])$peripheral->vendor = $request['vendor'];
            if($request['status'])$peripheral->status = $this->peripheral_status[$request['status']];
            $peripheral->save();
            return $peripheral;
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
        $peripheral = Peripheral::find($id);
        if($peripheral != NULL){
            $peripheral->delete();
            return response()->json (['success' => 'item deleted'], 201);
        }
        else
            return response()->json(['error' => 'record not found'], 404);
    }
}
