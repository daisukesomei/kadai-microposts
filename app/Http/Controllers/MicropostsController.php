<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicropostsController extends Controller
{
    //一覧表示のアクション
    public function index(){
        $data = [];
        if(\Auth::check()) {    //認証済みの場合
            //認証済みユーザーを取得
            $user = \Auth::user();
            //ユーザーの投稿の一覧を作成日時の降順で取得
            //（後のChapterで他ユーザーの投稿も取得できるように変更しますが、現時点ではこのユーザーの投稿のみ取得します）
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            $data = ['user' => $user,'microposts' => $microposts];
        }
        
        //dashboardビューで表示させる
        return view('dashboard', $data);
    }
    
    //新規投稿の登録のアクション
    public function store(Request $request){
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);
        
        //認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->microposts()->create([
            'content' => $request->content
            ]);
        //前のURLへリダイレクトさせる
        return back();
    }
    
    //投稿削除のアクション
    public function destroy(string $id){
        //idの値で投稿を検索して取得
        $micropost = Micropost::findOrFail($id);
        
        //認証済みユーザー（閲覧者)がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $micropost->user_id ) {
            $micropost->delete();
            return back()->with('success', 'Delete Successful');
        }
        
        //前のURLへリダイレクトさせる
        return back()->with('Delete Failed');
    }
}
