<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

class JurnalController extends Controller
{
    public function createJurnals(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:5|unique:jurnals,title',
            'content' => 'required|min:10',
            'date' => 'required|date:Y-m-d|unique:jurnals,date'
        ]);

        if($validator->fails()){
            return $validator->errors();
        }


        $jurnals = Jurnal::create([
            'title' => $request->title,
            'content' => $request->content,
            'users_id' => $request->user()->id,
            'date' => $request->date
        ]);

        return response(['message' => "Berhasil Membuat Jurnals",'data' => $jurnals],201);
    }

    public function getAllJurnals(){
        $jurnals = Jurnal::all();

        if(count($jurnals) == 0) return response(['message' => 'Jurnals Kosong'],204);

        return response()->json(['message' => 'Berhasil Mendapatkan Jurnals', 'data' => $jurnals],200);
    }

    public function getJurnalUser(Request $request){
        $user = $request->user()->id;

        $jurnalsUser = Jurnal::where('users_id',$user)->get();

        if(count($jurnalsUser) == 0)return response(['Message' => 'Not Users Jurnals'],204);

        return response()->json([
            'message' => 'Berhasil Mendapatkan Jurnals Users',
            'data' => $jurnalsUser
        ],200);
    } 

    public function getJurnalsById(int $id){
        $jurnals = Jurnal::find($id);

        if($jurnals == null) return response(['message' => 'Not Found Content'],404);

        return response()->json(['message' => 'Berhasil Mendapatkan Jurnals', 'data' => $jurnals]);
    }
}
