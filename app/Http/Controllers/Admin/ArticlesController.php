<?php

namespace App\Http\Controllers\Admin;

use Str;
use App\Like;
use App\Models\Tag;
use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use App\Models\ContentCategory;
use App\Notifications\NewArticles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{

    public function index()
    {
        $articles = Article::latest()->get();
        $content_category = ContentCategory::all();
        return view('admin.content-list.articles.index')
        ->with('content_category', $content_category)
        ->with('articles', $articles);
    }

    public function create()
    {
        $tags = Tag::all();
        $content_category = ContentCategory::all();
        return view('admin.content-list.articles.create', compact('content_category', 'tags'));
    }


    public function edit(Article $article)
    {
        $tags = Tag::with('articles')->get();

        $content_category = ContentCategory::all();
        return view('admin.content-list.articles.edit', compact('content_category', 'tags', 'article'));
    }


    public function store(Request $request)
    {

        $article = new Article();
        $article->category_id = $request->input('category_id');
        $article->name = $request->input('title');
        $article->content = $request->input('post_content');
        $article->category_id = $request->input('category_id');
        $article->save();

        // $category->save();
        if ($request->hasFile('feature_image')) {
            $article->addMedia($request->file('feature_image'))->toMediaCollection('featuredimage');
        }

        $article->tags()->sync($request->input('tags'));

        return redirect()->route('articles.index')->with('status', 'article added successfully');
    }

    public function update(Request $request, Article $article)
    {

        Article::where('id', $article->id)->update([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('title'),
            'content' => $request->input('post_content'),
        ]);
        if ($request->hasFile('feature_image')) {
            $article->addMedia($request->file('feature_image'))->toMediaCollection('featuredimage');
        }
        $article->tags()->sync($request->input('tags'));


        return redirect()->route('articles.index')->with('status', 'article added successfully');
    }

    public function uploadFoto(Request $request)//M
    {
        $original_name = $request->upload->getClientOriginalName();
        $filename_org = pathinfo($original_name, PATHINFO_FILENAME);
        $ext = $request->upload->getClientOriginalExtension();

        $filename = $filename_org.'_'.time().'.'.$ext;

        $request->upload->move(storage_path('app/public/articles/images'), $filename);

        $CKEditorFuncNum = $request->input('CKEditorFuncNum');

        $url = asset('storage/articles/images/'.$filename);
        $message = "Your foto has been added to the db successfully";

        $res = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$message')</script>";

        @header("Content-Type: text/html; charset = utf-8");

        echo $res;
    }



    public function delete($id)
    {
        $art_des = Article::findOrFail($id);
        $art_des->delete();

        //Refresh the model data to be returned
        $articles = Article::latest()->get();
        $content_category = ContentCategory::all();

        return view('admin.content-list.articles.index')->with('content_category', $content_category)->with('articles', $articles);
    }

 public function likePost($id)
    {
        // here you can check if product exists or is valid or whatever

        $this->handleLike('App\Models\Article', $id);
        return redirect()->back();
    }

    public function handleLike($type, $id)
    {
        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => Auth::id(),
                'likeable_id'   => $id,
                'likeable_type' => $type,
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
    }
}
