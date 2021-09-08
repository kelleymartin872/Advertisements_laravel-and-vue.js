<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Image;
class ProfileController extends Controller
{
    public function index()
    {

        return view('profile.index');
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ]);
        $user = User::find(auth()->user()->id);

        $save_url=$user->avatar;
        if($request->hasFile('image')){

            $image=$user->avatar;
              $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('imagesProfile/'.$name_gen);
            $save_url ='imagesProfile/'. $name_gen;

        }
        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'avatar' => $save_url

        ]);

        return redirect()->back()->with('message','Profile Updated');
    }
}
