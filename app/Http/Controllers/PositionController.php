<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(){
        $position = Position::all();
        return ['positions'=>$position];
    }

    public function store(Request $request){
        $position = Position::create($request->all());
        return ['position'=>$position];
    }

    public function update(Request $request,$id){
        $position = Position::find($id);
        $position->fill($request->all());
        return ['position'=>$position];
    }
}
