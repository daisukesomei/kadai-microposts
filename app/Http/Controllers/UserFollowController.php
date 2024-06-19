<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    //ユーザーをフォローするアクション
    public function store(string $id){
        // 認証済みユーザー（閲覧者）が、 idのユーザーをフォローする
        \Auth::user()->follow(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    //ユーザーをアンフォローするアクション
    public function destroy(string $id){
        // 認証済みユーザー（閲覧者）が、 idのユーザーをアンフォローする
        \Auth::user()->unfollow(intval($id));
        // 前のURLへリダイレクトさせる
        return back();
    }
}
