<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmountSetting;

class AmountSettingController extends Controller
{
    public function index(){
        return AmountSetting::latest()->paginate(5);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'amount' => 'required',
            'period' => 'required|string'
        ]);

        return AmountSetting::create([
            'name' => $request->get('name'),
            'amount' => $request->get('amount'),
            'period' => $request->get('period')
        ]);
    }

    public function update(Request $request,$id)
    {
        $amountsetting = AmountSetting::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'amount' => 'required',
            'period' => 'required|string'
        ]);
        
        $amountsetting->update($request->all());
        return (['message' => 'updated']);
    }

    public function destroy(Request $request,$id)
    {
        $amountsetting = AmountSetting::findOrFail($id);

        $amountsetting->delete();
        return (['message'=>'deleted']);
    }}
