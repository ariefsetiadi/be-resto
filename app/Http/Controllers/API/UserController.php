<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use File;
use Hash;
use Validator;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $image      =   $request->image;
        $image_name =   NULL;
        $password   =   date('dmY', strtotime($request->date_of_birth));

        $validate   =   Validator::make($request->all(), [
                            'emp_id'            =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/|unique:tm_user,emp_id',
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'place_of_birth'    =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/',
                            'date_of_birth'     =>  'required|date',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:9,15|unique:tm_user,phone',
                            'image'             =>  'max:2048',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                            'status'            =>  'required|in:0,1'
                        ],
                        [
                            'emp_id.required'           =>  'ID Karyawan wajib diisi',
                            'emp_id.max'                =>  'ID Karyawan maksimal 10 karakter',
                            'emp_id.regex'              =>  'ID Karyawan hanya boleh angka & huruf',
                            'emp_id.unique'             =>  'ID Karyawan sudah digunakan',
                            'fullname.required'         =>  'Nama Lengkap wajib diisi',
                            'fullname.max'              =>  'Nama Lengkap maksimal 255 karakter',
                            'fullname.regex'            =>  'Nama Lengkap hanya boleh huruf dan spasi',
                            'gender.required'           =>  'Jenis Kelamin wajib dipilih',
                            'gender.in'                 =>  'Jenis Kelamin tidak valid',
                            'place_of_birth.required'   =>  'Tempat Lahir wajib diisi',
                            'place_of_birth.max'        =>  'Tempat Lahir maksimal 255 karakter',
                            'place_of_birth.regex'      =>  'Tempat Lahir hanya boleh angka, huruf dan spasi',
                            'date_of_birth.required'    =>  'Tanggal Lahir wajib diisi',
                            'date_of_birth.date'        =>  'Tanggal Lahir tidak valid',
                            'phone.required'            =>  'Telepon wajib diisi',
                            'address.required'          =>  'Alamat wajib dipilih',
                            'phone.digits_between'      =>  'Telepon 9 - 15 angka',
                            'phone.unique'              =>  'Telepon sudah digunakan',
                            'image.max'                 =>  'Foto maksimal 2 MB',
                            'image.mimes'               =>  'Foto hanya boleh jpg, jpeg, atau png',
                            'status.required'           =>  'Status wajib dipilih',
                            'status.in'                 =>  'Status tidak valid'
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        if(!empty($image)) {
            $image_name =   strtoupper($request->emp_id) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/users'), $image_name);
        }

        $user                   =   new User;
        $user->emp_id           =   strtoupper($request->emp_id);
        $user->fullname         =   ucwords(strtolower($request->fullname));
        $user->gender           =   $request->gender;
        $user->place_of_birth   =   ucwords(strtolower($request->place_of_birth));
        $user->date_of_birth    =   $request->date_of_birth;
        $user->address          =   $request->address;
        $user->phone            =   $request->phone;
        $user->image            =   $image_name;
        $user->status           =   $request->status;
        $user->password         =   Hash::make('Pass' . $password);
        $user->save();

        return response()->json(['messages' => 'User berhasil ditambah'], 201);
    }

    public function show($id)
    {
        $user   =   User::where('id', $id)->first();

        if(!$user) {
            return response()->json(['messages' => 'User tidak ditemukan'], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user   =   User::where('id', $id)->first();

        if(!$user) {
            return response()->json(['messages' => 'User tidak ditemukan'], 404);
        }

        $image_name =   $user->image;
        $image      =   $request->image;

        $validate   =   Validator::make($request->all(), [
                            'emp_id'            =>  'required|max:10|regex:/^[a-zA-Z0-9]*$/|unique:tm_user,emp_id,' . $id,
                            'fullname'          =>  'required|max:255|regex:/^[a-zA-Z ]*$/',
                            'gender'            =>  'required|in:Laki-Laki,Perempuan',
                            'place_of_birth'    =>  'required|max:255|regex:/^[a-zA-Z0-9 ]*$/',
                            'date_of_birth'     =>  'required|date',
                            'address'           =>  'required',
                            'phone'             =>  'required|digits_between:9,15|unique:tm_user,phone,' . $id,
                            'image'             =>  'max:2048',
                            'image.*'           =>  'mimes:jpg,jpeg,png',
                            'status'            =>  'required|in:0,1'
                        ],
                        [
                            'emp_id.required'           =>  'ID Karyawan wajib diisi',
                            'emp_id.max'                =>  'ID Karyawan maksimal 10 karakter',
                            'emp_id.regex'              =>  'ID Karyawan hanya boleh angka & huruf',
                            'emp_id.unique'             =>  'ID Karyawan sudah digunakan',
                            'fullname.required'         =>  'Nama Lengkap wajib diisi',
                            'fullname.max'              =>  'Nama Lengkap maksimal 255 karakter',
                            'fullname.regex'            =>  'Nama Lengkap hanya boleh huruf dan spasi',
                            'gender.required'           =>  'Jenis Kelamin wajib dipilih',
                            'gender.in'                 =>  'Jenis Kelamin tidak valid',
                            'place_of_birth.required'   =>  'Tempat Lahir wajib diisi',
                            'place_of_birth.max'        =>  'Tempat Lahir maksimal 255 karakter',
                            'place_of_birth.regex'      =>  'Tempat Lahir hanya boleh angka, huruf dan spasi',
                            'date_of_birth.required'    =>  'Tanggal Lahir wajib diisi',
                            'date_of_birth.date'        =>  'Tanggal Lahir tidak valid',
                            'phone.required'            =>  'Telepon wajib diisi',
                            'address.required'          =>  'Alamat wajib dipilih',
                            'phone.digits_between'      =>  'Telepon 9 - 15 angka',
                            'phone.unique'              =>  'Telepon sudah digunakan',
                            'image.max'                 =>  'Foto maksimal 2 MB',
                            'image.mimes'               =>  'Foto hanya boleh jpg, jpeg, atau png',
                            'status.required'           =>  'Status wajib dipilih',
                            'status.in'                 =>  'Status tidak valid'
                        ]);

        if($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        if(!empty($image)) {
            File::delete('uploads/users/' . $user->image);

            $image_name =   strtoupper($request->emp_id) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/users'), $image_name);
        }

        $data   =   array(
                        'emp_id'            =>  strtoupper($request->emp_id),
                        'fullname'          =>  ucwords(strtolower($request->fullname)),
                        'gender'            =>  $request->gender,
                        'place_of_birth'    =>  ucwords(strtolower($request->place_of_birth)),
                        'date_of_birth'     =>  $request->date_of_birth,
                        'address'           =>  $request->address,
                        'phone'             =>  $request->phone,
                        'image'             =>  $image_name,
                        'status'            =>  $request->status
                    );

        User::where('id', $id)->update($data);

        return response()->json(['messages' => 'User berhasil diupdate'], 200);
    }

    public function destroy($id)
    {
        //
    }
}
