<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
    
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
       // return view('image-upload');
    }
          
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:4096'
        ]);
            
        $image_name = time().'.'.$request->image->extension();  
             
        $request->image->move(public_path('images'), $image_name);
        
        $Image = new Image();
        $Image->name = $image_name;
        $Image->save();
        
        //Image::create(['name' => $image_name]);
  
        return response()->json(['success' => 'Images uploaded successfully!']);
    }
}