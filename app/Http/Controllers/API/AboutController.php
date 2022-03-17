<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        return About::latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'tagline' => 'required|string',
            'details' => 'required|string'
        ]);

        if($request->image){
                $name = time().'.'.explode('/',explode(':', substr($request->image, 0, strpos
                ($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/').$name);
                $request->merge(['image' => $name]);
            return About::create([
                'tagline' => $request->get('tagline'),
                'details' => $request->get('details')
            ]);
        }else{
            return About::create([
                'tagline' => $request->get('tagline'),
                'details' => $request->get('details')
            ]);   
        }
    }

    public function update(Request $request, $id)
    {
        $about = About::findOrFail($id);
        $currentImage = $about->image;

        if($request->image != $currentImage){
            $name = time().'.'.explode('/',explode(':', substr($request->image, 0, strpos
            ($request->image, ';')))[1])[1];
        \Image::make($request->image)->save(public_path('uploads/').$name);
            $request->merge(['image' => $name]);

         //delete old photo from server
        $aboutImage = public_path('uploads/').$currentImage;
        if(file_exists($aboutImage)){
            @unlink($aboutImage);
        } 
        $about->update($request->all());   
        }else{
        $about->update($request->all());
        }
    }

    public function destroy(Request $request,$id)
    {
        $about = About::findOrFail($id);
        $about->delete();
    }    
}
