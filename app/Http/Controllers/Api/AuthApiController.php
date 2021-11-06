<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Response\ResponseFailed;
use App\Http\Response\ResponseSuccess;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ResponseFailed::make($errors, 500);
        }

        $validated = $validator->validated();

        $data = [
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        try {
            $data = User::where('email', $validated['email'])->first();
            if ($data) {
                $checkPassword = Hash::check($validated['password'], $data->password);
                if ($checkPassword) {
                    return ResponseSuccess::make($data);
                }
                return ResponseFailed::make("Password yang anda masukan salah", 404);
            }
            return ResponseFailed::make("Email yang anda masukan tidak ditemukan");
        } catch (Exception $ex) {
            return ResponseFailed::make($ex, 500);
        }
    }

    public function register(Request $request)
    {
        //     $data = [
        //         'name' => $request->name,
        //         'email' => $request->email,
        //         'password' => Hash::make($request->password),
        //         'role' => $request->role,
        //         'image' => $request->image,
        //     ];

        //     $image_64 = $data['image'];
        //     $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        //     $replace = substr($image_64,0,strpos($image_64,',')+1);
        //     $image = str_replace($replace,'',$image_64);
        //     $image = str_replace(' ','+',$image);
        //     $imageName = Str::random(10).'.'.$extension;
        //     Storage::disk('public')->put($imageName,base64_decode($image));
        //     $data['image']=$imageName;

        //     return User::create($data);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:10',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ResponseFailed::make($errors, 500);
            // return response()->json([
            //     "status" => 0,
            //     "message" => "Validation Error",
            //     "reason" => $errors
            // ]);
        }

        $validated = $validator->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'image' => $request->image
        ];

        if ($data['image'] != null) {
            $image_64 = $data['image'];
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10) . '.' . $extension;
            Storage::disk('public')->put($imageName, base64_decode($image));
            $data['image'] = $imageName;
        }

        try {
            $insert = User::create($data);
            return ResponseSuccess::make($insert);
            // return [
            //     'status' => 1,
            //     'message' => "Berhasil Register",
            //     'data' => $insert
            // ];
        } catch (Exception $ex) {
            // return [
            //     'status' => 0,
            //     'message' => "Gagal Register",
            //     'reason' => $ex
            // ];
            return ResponseFailed::make($ex, 500);
        }
        return User::create($data);
    }

    public function view(Request $request)
    {
        $data = User::all()->where('id', $request->id)->first();
        return $data;
    }

    public function showAll()
    {
        $request = User::get();
        try {
            $result = [
                'status' => 1,
                'message' => "Berhasil tampil",
                'data' => $request
            ];
        } catch (\Exception $ex) {
            $result = [
                'status' => 0,
                'message' => "Gagal Tampil",
                'reason' => $ex
            ];
        }
        return json_encode($result);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'min:10',
            'email' => 'email',
            'role' => 'min:1',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ResponseFailed::make($errors, 500);
        }

        $validated = $validator->validated();


        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'image' => $request->image
        ];
        if ($request['password'] != null) {
            if (strlen($request['password']) < 8) {
                return ResponseFailed::make("PASSWORD MINIMAL 8", 500);
            }
            $data['password'] = hash::make($request['password']);
        }

        if ($data['image'] != null) {
            $image_64 = $data['image'];
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10) . '.' . $extension;
            Storage::disk('public')->put($imageName, base64_decode($image));
            $data['image'] = $imageName;
        }
        try {
            $data_u = User::find($request->id);

            $data_u->update($data);

            return ResponseSuccess::make($data_u);
        } catch (Exception) {
            return ResponseFailed::make();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = User::all()->where('id', $request->id)->first();
            $data->delete($data);
            return ResponseSuccess::make($data);
        } catch (Exception) {
            return ResponseFailed::make();
        }
    }
}
