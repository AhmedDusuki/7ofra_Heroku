<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use App\Models\Help;
use App\Models\User;


class HelpController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getGrid($grid){
        return Help::where([['grid','=',$grid]])->get();
    }

    public function add($lat, $lng, $userId, $grid){

         $user = User::where('id', $userId)->get();
        
         if ($user[0]['help'] == -1){
            $help = Help::create(['lat'=>$lat,
                'lng'=>$lng, 
                'user'=>$userId, 
                'grid'=>$grid
            ]);
            User::where('id', $userId)->update(["help"=>$help['id']]);
        }
        else{
            echo "user has already requested";
            return;
        }

    }

    public function remove($id, $userId){
        $user = User::where('id', $userId)->get();
        if ($user[0]['help']==-1){
            echo "user has not requested";
            return;
        }
        else{
            
            Help::where('id', $id)->delete();
            User::where('id', $userId)->update(["help"=>-1]);
        }
    }

}