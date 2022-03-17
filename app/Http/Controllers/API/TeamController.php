<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        return Team::latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'required|string'
        ]);

        if($request->image){
            $name = time().'.'.explode('/',explode(':', substr($request->image, 0, strpos
            ($request->image, ';')))[1])[1];
        \Image::make($request->image)->save(public_path('uploads/').$name);
            $request->merge(['image' => $name]);

        return Team::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'position' => $request->get('position'),
        ]);
        }else{
        return Team::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'position' => $request->get('position'),
        ]);            
        }
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $this->validate($request,[
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'required|string'
        ]);
        
        $currentImage = $team->image;

        if($request->image != $currentImage){
            $name = time().'.'.explode('/',explode(':', substr($request->image, 0, strpos
            ($request->image, ';')))[1])[1];
        \Image::make($request->image)->save(public_path('uploads/').$name);
            $request->merge(['image' => $name]);

         //delete old photo from server
        $teamImage = public_path('uploads/').$currentImage;
        if(file_exists($teamImage)){
            @unlink($teamImage);
        } 
        $team->update($request->all());   
        }else{
        $team->update($request->all());
        }
    }

    public function destroy(Request $request,$id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
    }
}
