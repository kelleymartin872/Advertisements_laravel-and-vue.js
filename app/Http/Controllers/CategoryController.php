<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest;
use Illuminate\Support\Facades\Storage;
use Image;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::get();
        return view('backend.category.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.category.create');
    }

    public function store(CategoryFormRequest $request)
    {

    //     if (request()->hasFile('image')){

    //         $image = $request->file('image');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $destinationPath = public_path('/backend/product/');
    //         $uploadedImage = $request->file('image');
    //         $uploadedImage->move($destinationPath, $imageName);
    //         $image->image = $destinationPath . $imageName;
    //     }

        $image = $request->file('image');

        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
    	Image::make($image)->resize(300,300)->save('categories/new/'.$name_gen);
    	$save_url = 'categories/new/'.$name_gen;

      $cat=  Category::create([
            'name' => $name = $request->name,
            'image' => $save_url,
            'slug' => Str::slug($name)
        ]);

        return redirect()->route('category.index')->with('message', 'Category Created successfully');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('backend.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if ($request->hasFile('image')) {
            Storage::delete($category->image);
            $image = $request->file('image')->store('public/category');
            $category->update([
                'name' => $request->name,
                'image' => $image
            ]);
        }

        $category->update(['name' => $request->name]);
        return redirect()->route('category.index')->with('message', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (Storage::delete($category->image)) {
            $category->delete();
        }
        return back()->with('message', 'Category Deleted successfully');
    }
}
