<?php

namespace App\Http\Controllers;

use App\Models\Wall;
use App\Models\WallLike;
use Illuminate\Http\Request;

class WallController extends Controller
{
    public function getAll(Request $request){
        $array = [];

        $user = $request->user();
        $walls = Wall::all();
        foreach($walls as $wallKey => $wallValue){
            $numberOfLikes = WallLike::
                where('id_wall', $wallValue['id'])
                ->where('id_user', !null)
                ->count();
            $likedForMe = WallLike::
                where('id_wall', $wallValue['id'])
                ->where('id_user', $user->id)
                ->first();
            
            $array['walls'][$wallKey] = $wallValue;
            $array['walls'][$wallKey]['likes'] = $numberOfLikes;
            $array['walls'][$wallKey]['likedForMe'] = $likedForMe ? true : false;
        }
 
        return response()->json($array);
    }

    public function like(Request $request, $id){
        $user = $request->user();
        $likedForMe = WallLike::
            where('id_wall', $id)
            ->where('id_user', $user->id)
            ->first();
        
        if($likedForMe){
            $likedForMe->delete();
            return response()->json(['success' => true, 'message' => 'unliked']);
        } else {
            WallLike::create([
                'id_wall' => $id,
                'id_user' => $user->id
            ]);
            return response()->json(['success' => true, 'message' => 'liked']);
        }

    }
}
