<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\PostPhoto;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostsPhotosController extends Controller
{
    function index()
    {
        $data = PostPhoto::all();
        return $data;
//        return view('store_image', compact('data'))
//            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    function store(Request $request)
    {
        $data = $request->all();

        $filename = uniqid().$data['post_photo']->getClientOriginalName();

        //Сохраняем оригинальную картинку
        $data['post_photo']->move(Storage::path('/public/image/news/').'origin/', $filename);


        //Сохраняем новость в БД
        $data['image'] = $filename;
        $postPhoto = PostPhoto::create(
            [
                "post_id"=>$request->post_id,
                "post_photo"=>$filename
            ]
        );

        return[
            "status"=>true,
            "post"=>$postPhoto
        ];
    }

    function fetch_image($image_id)
    {
        $image = PostPhoto::findOrFail($image_id);

        $image_file = PostPhoto::make($image->user_image);

        $response = Response::make($image_file->encode('jpeg'));

        $response->header('Content-Type', 'image/jpeg');

        return $response;
    }
}
