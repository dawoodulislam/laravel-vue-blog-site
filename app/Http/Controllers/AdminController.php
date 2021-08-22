<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function addTag(Request $request){
        $this->validate($request, [
            'tagName' => 'required'
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editTag(Request $request){
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function getTag(){
        return Tag::orderBy('id', 'desc')->get();
    }
    public function deleteTag(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);

        return Tag::where('id', $request->id)->delete();
    }

    public function uploadCategory(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $fileName = time().'.'.$request->file->extension();
        $directory = 'admin/category/';
        $request->file->move($directory, $fileName);

        return $fileName;
    }

    public function deleteImage(Request $request){
        $fileName = $request->fileName;
        $path = $fileName;
        if(file_exists(public_path().$path)){
            @unlink(public_path().$path);
        }
        return public_path().$path;
    }

    public function addCategory(Request $request){
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function getCategory(){
        return Category::orderBy('id', 'desc')->get();
    }

    public function editCategory(Request $request){
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function deleteCategory(Request $request){
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        $fileName = $request->iconImage;
        $path = $fileName;
        if(file_exists(public_path().$path)){
            @unlink(public_path().$path);
        }
        return Category::where('id', $request->id)->delete();
    }
    public function addAdminUser(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'userType' => 'required',
        ]);
        $password = bcrypt($request->password);

        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'userType' => $request->userType
        ]);

        return $user;
    }
    public function getUsers(){
        return User::where('userType', '!=', 'User')->get();
    }
    public function editUser(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required',
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType
        ];

        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        

        $user = User::where('id', $request->id)->update($data);

        return $user;
    }
    public function adminLogin(Request $request){
        $this->validate($request, [
            'email' => "bail|required|email",
            'password' => 'bail|required|min:6'
        ]);
        if(Auth::attempt(['email'=> $request->email, 'password'=> $request->password])){
            $user = Auth::user();
            if($user->userType == 'User'){
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect Logging Details'
                ], 401);
            }
            return response()->json([
                'msg' => 'You are logged in'
            ]);
        }
        else{
            return response()->json([
                'msg' => 'Incorrect Logging Details'
            ], 401);
        }
    }
}
