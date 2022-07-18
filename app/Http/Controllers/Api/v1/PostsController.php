<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Patterns\PropertyContainer\FullPost;
use App\Patterns\PropertyContainer\PropertyContainer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $allParameters = $request->all();
        $posts = Post::all();
        if(isset($allParameters['cat_id']) && isset($allParameters['search'])){
            $arr = explode('%',$allParameters['cat_id']);
            $posts=Post::whereIn('category_id',$arr)->where
            (function ($query) use($allParameters){
                $query->where('title','like','%'.$allParameters['search'].'%')
                    ->orWhere('description','like','%'.$allParameters['search'].'%');
            })->get();
        }
        else{
            if(isset($allParameters['cat_id']))
            {
                $arr = explode('%',$allParameters['cat_id']);
                $posts=Post::whereIn('category_id',$arr)->get();
            }
            if(isset($allParameters['search']))
            {
                $posts=Post::where('title','like','%'.$allParameters['search'].'%')
                    ->orWhere('description','like','%'.$allParameters['search'].'%')->get();
            }
        }


        if(!$allParameters)
        {
            $posts=Post::all();
        }
        $response = [];
        foreach ($posts as $post){
            $post->covers;
            $post->user->covers;
            $post->category;
            $post = $post->toArray();
            $response[]=$post;
        }
        $data = $this->paginate($response);
        return $data;
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "title"=>["required"],
                "description"=>["required"],
                "category_id"=>["required"],
                "user_id"=>["required"],
            ]
        );

        if($validator->fails()){
          return [
              "status"=>false,
              "errors"=>$validator->messages()
          ];
        };

        $post = Post::create([
            "title"=>$request->title,
            "description"=>$request->description,
            "like_count"=>0,
            "user_id"=>$request->user_id,
            "category_id"=>$request->category_id,
        ]);

        return[
            "status"=>true,
            "post"=>$post
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if(!$post)
        {
          return response()->json( [
            "status"=>false,
              "message"=>'Post not found'
          ])->setStatusCode(404);
        };
        $post->covers;
        $post->user->covers;
        $post->category;
        $post = $post->toArray();
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
