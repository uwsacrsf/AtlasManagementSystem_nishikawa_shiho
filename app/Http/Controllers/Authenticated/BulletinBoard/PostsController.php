<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;
use App\Models\Subjects;

class PostsController extends Controller
{
    public function show(Request $request){

        $posts = Post::query()->with(['user', 'postComments', 'likes', 'subCategories']);

        $categories = MainCategory::with('subCategories')->get();
        $like = new Like;
        $post_comment = new Post;

        // キーワード検索処理
        if(!empty($request->keyword)){
            $keyword = $request->keyword;

            //キーワードがSubCategory名と完全一致するかチェック
            $subCategory = SubCategory::where('sub_category', $keyword)->first();

            if ($subCategory) {
                // 完全一致した場合そのSubCategory IDで絞り込む
                $posts->whereHas('subCategories', function($query) use ($subCategory){
                    $query->where('sub_categories.id', $subCategory->id);
                });
            } else {
                // 不一致の場合従来通り、タイトルまたは内容の部分一致検索を行う
                $posts->where(function($query) use ($keyword){
                    $query->where('post_title', 'like', '%'.$keyword.'%')
                        ->orWhere('post', 'like', '%'.$keyword.'%');
                });
            }

        } else if($request->category_id){
            $posts->whereHas('subCategories', function($query) use ($request){
                $query->where('sub_categories.id', $request->category_id);
            });

        } else if($request->like_posts){
            $likes = Auth::user()->likePostId()->pluck('like_post_id');
            $posts->whereIn('id', $likes);

        } else if($request->my_posts){
            $posts->where('user_id', Auth::id());
        }

        $posts = $posts->get();

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments', 'subCategories')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);

        $sub_category_id = $request->post_category_id;
        $post->subCategories()->attach($sub_category_id);

        return redirect()->route('post.show');
    }

    public function postEdit(PostFormRequest $request){
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }

    public function mainCategoryCreate(Request $request){
        $request->validate([
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
        ]);
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request){

        $request->validate([
            'comment' => 'required|string|max:250',

        ]);
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        // リレーションをEager Load
        $posts = Auth::user()->posts()->with(['user', 'postComments', 'likes', 'subCategories'])->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::where('like_user_id', Auth::id())->pluck('like_post_id')->toArray();
        $posts = Post::with(['user', 'postComments', 'likes', 'subCategories'])->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request, $post_id){
            $user_id = Auth::id();
            // $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json(['success' => true, 'likeCount' => Like::where('like_post_id', $post_id)->count()]);
    }

    public function postUnLike(Request $request, $post_id){
        $user_id = Auth::id();
        // $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

             return response()->json(['success' => true, 'likeCount' => Like::where('like_post_id', $post_id)->count()]);
    }
    public function subCategoryCreate(Request $request){

        $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_name' => 'required|string|max:100',
        ]);
        SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' => $request->sub_category_name
        ]);

        return redirect()->route('post.input');
    }
}
