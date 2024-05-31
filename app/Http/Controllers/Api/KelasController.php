<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function addKelas(Request $request){

        $validation = Validator::make($request->all(),[
            'kelas' => 'required|unique:kelas,name'
        ]);

        if($validation->fails())return response($validation->errors());

        $kelas = Kelas::create([
            'name' => $request->kelas
        ]);

        return response()->json([
            'message' => 'Berhasil Membuat Kelas',
            'data' => $kelas
        ],201);
    }

    public function getKelas(){
        $kelas = Kelas::all();

        if(count($kelas) == 0 )return response(['message' => 'Kelas Masih Kosong'],204);

        return response()->json(['message' => $kelas],200);
    }

    public function getKelasByUser(Request $request){
           $user = $request->user()->kelas_id;

           $kelas = Kelas::find($user);

           if($kelas != null) return response(['message' => 'Tidak Menemukan Kelas'],404);

           return response()->json(['message' => $kelas]);
    }
}
