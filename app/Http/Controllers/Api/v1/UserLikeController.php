<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PostPhoto;
use App\Models\UserLike;
use Illuminate\Http\Request;

class UserLikeController extends Controller
{
    public function getAll($id){
        return UserLike::where('user_id',$id)->get();
    }

    public function likePost(){

    }

    public function delikePost(){

    }

}
