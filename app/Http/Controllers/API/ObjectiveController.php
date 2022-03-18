<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Objective;

class ObjectiveController extends Controller
{
    public function index()
    {
        return Objective::latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string',
            'body' => 'required'
        ]);

        return Objective::create([
            'title' => $request->get('title'),
            'body' => $request->get('body')
        ]);   
}

    public function update(Request $request, $id)
    {
        $objective = Objective::findOrFail($id);
        $this->validate($request,[
            'title' => 'required|string',
            'body' => 'required|string'
        ]);
        $objective->update($request->all());   

    }

    public function destroy(Request $request,$id)
    {
        $objective = Objective::findOrFail($id);
        $objective->delete();
    }     
}
