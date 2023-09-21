<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Roadblock;

class RoadblockController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getGrid($grid){
        return Roadblock::where([['grid','=',$grid]])->get();
    }

    public function add($lat, $lng, $user, $grid){
        Roadblock::create(['lat'=>$lat,'lng'=>$lng, 'user'=>$user, 'grid'=>$grid]);
    }

    public function remove($id){
        Roadblock::where('id',$id)->delete();
    }

}