<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Revolution\Google\Sheets\Facades\Sheets;

class JurnalController extends Controller
{
    public function createJurnals(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:5',
            'content' => 'required|min:10',
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        date_default_timezone_set('Asia/Jakarta');

        $date_now = date('Y-m-d');

        $userNow = Jurnal::where('users_id',$request->user()->id)->get();

        $filter = collect($userNow)->filter(function($val) use ($date_now){
            return $val->date === $date_now;
        })->all();

        if(count($filter) > 0)return response(['message' => 'Anda Sudah Mengisi Jurnal!'],400);

        $jurnals = Jurnal::create([
            'title' => $request->title,
            'content' => $request->content,
            'users_id' => $request->user()->id,
            'date' => $date_now
        ]);

        $num = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->range('A:A')->all();

        foreach($num as $val){
           if($val[0] !== 'No'){
            $count = intval($val[0]) + 1;
           }else{
            $count = 1;
           }
        }

        $userKelas = $request->user()->kelas_id;

        $kelas = Kelas::find($userKelas);

        Sheets::spreadsheet(config('google.post_spreadsheet_id'))
              ->sheet($kelas->name)
              ->append([[
                'No' => $count,
                'Tanggal' => $date_now,
                'Jenis Kegiatan' => $request->title,
                'Keterangan' => $request->content,
                'Siswa' => $request->user()->name,
            ]]);

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
