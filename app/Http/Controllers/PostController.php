<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::all();
        return $this->SuccessRespone(PostResource::collection($post),'berhasil get data');
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
        $input = $request->all();

        $validator = Validator::make($input,[
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            $content = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($content, 400);
        }

        $post = Post::create($input);
        $content = [
            'success' => true,
            'data' => new PostResource($post),
            'message' => 'we did it'
        ];
        return response()->json($content, 200);
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
        if (is_null($post)) {
            return $this->ErrorRespone('not foud');
        }
        return $this->SuccessRespone(new PostResource($post),'ok');

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
        $input = $request->all();
        $validator = Validator::make($input,[
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->ErrorRespone('gagal',$validator->errors());
        }
        $post = Post::find($id);
        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->save();
        if ($post) {
            $content = [
                'success' => true,
                'data' => new PostResource($post),
                'message' => 'we did it'
            ];
            return response()->json($content, 200);

        }else {
            $content = [
                'success' => false,
                'message' => 'cannot save data'
            ];
            return response()->json($content, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $post = Post::find($id);
        if (is_null($post)) {
            $content = [
                'success' => false,
                'message' => 'data tidak ditemukan'
            ];
            return response()->json($content, 403);
        }
        $post->delete();
        if (isNull($post)) {
            $content = [
                'success' => true,
                'data' => new PostResource($post),
                'message' => 'berhasil di hapus'
            ];
            return response()->json($content, 200);
        }


    }
}
