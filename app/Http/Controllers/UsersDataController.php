<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usersData;
use DB;

class UsersDataController extends Controller
{
    public function index()
    {
        return view('welcome', ['showDatas' => usersData::orderBy('id','DESC')->get()]);
    }

    public function edit(usersData $usersData)
    {
        return response()->json($usersData);
    }

    public function store(Request $request)
    {
       
       $query = usersData::updateOrCreate(
            ['id' => $request-> id],
            
            ['name' => $request-> name,
            'address' => $request-> address,
            'phone' => $request-> phone
            ]
        );
        //$query=usersData::insert(['name' => $request-> name,'address' => $request-> address,'phone' => $request-> phone]);
        response()->json($query);
    }
    
    public function destroy(usersData $usersData)
    {
        $usersData->delete();
        return response()->json('success');
    }
}
