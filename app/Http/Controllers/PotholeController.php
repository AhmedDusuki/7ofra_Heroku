<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Pothole;

class PotholeController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    const MIN_DISTANCE = 12;
    const DEL_THRESHOLD = 3;
    public static function latLngDistance(
      $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
      // convert from degrees to radians
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);

      $lonDelta = $lonTo - $lonFrom;
      $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
      $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

      $angle = atan2(sqrt($a), $b);
      return $angle * $earthRadius;
    }

    public function add($lat, $lng, $user, $grid){
        $potholes = DB::table('potholes')->where([['grid','=',$grid]])->get();
        $potholesCount = count($potholes);
        $closest = Array(-1, self::MIN_DISTANCE);
        for($i=0; $i<$potholesCount; $i++){
            $latTo = floatval($potholes[$i]->lat);
            $lngTo = floatval($potholes[$i]->lng);
            $distance = self::latLngDistance($lat, $lng, $latTo, $lngTo);
            if ($distance < self::MIN_DISTANCE && $distance < $closest){
                $closest[0]=$potholes[$i]->id;
                $closest[1]=$distance;
            }
        }
        if ($closest[0]!=-1){
            self::addExisisting($closest[0], $user);
        }
        else{
            self::addNew($lat, $lng, $user, $grid);
        }
    }
    public function addNew($lat, $lng, $user, $grid){
        $userArray = Array(intval($user));
        $userRemoveArray = Array();
        Pothole::create(['lat'=>$lat,'lng'=>$lng, 'reports'=>json_encode($userArray), 'remove reports'=>json_encode($userRemoveArray), 'user'=>$user, 'grid'=>$grid]);
    }

    public function addJson($json, $user){
        $json = json_decode($json);
        $json.add($user);
    }

    public function removeJson($json, $user){
        $json = json_decode($json);
        $json.remove($user);
    }

    public function addExisisting($id, $user){
        // add 1 to number of reports of record
        $userArray = json_decode(Pothole::where('id',$id)->get()[0]["reports"]);
        if (in_array(intval($user), $userArray)){
            return;
        }
        array_push($userArray, intval($user));
        $userArray = json_encode($userArray);
        Pothole::where('id',$id)->update(['reports'=>$userArray]);
    }
    public function getGrid($grid){
        return Pothole::where([['grid','=',$grid]])->get();
    }
    public function removePothole($id, $user){
        $pothole = Pothole::where('id',$id);
        if (count($pothole->get())){
            $userRemoveArray = json_decode($pothole->get()[0]["remove reports"]);
            if (in_array(intval($user), $userRemoveArray)){
                return;
            }
            if (count($userRemoveArray) >= (self::DEL_THRESHOLD - 1)){
                $pothole->delete();
            }
            else{
                array_push($userRemoveArray, intval($user));
                $userRemoveArray = json_encode($userRemoveArray);
                $pothole->update(["remove reports"=>$userRemoveArray]);
            }
        }
    }

}