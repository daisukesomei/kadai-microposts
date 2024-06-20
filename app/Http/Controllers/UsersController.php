<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Micropost;

class UsersController extends Controller
{
    //ユーザー一覧のアクション
    public function index(){
        // ユーザー一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(5);
        
        // ユーザー一覧ビューでそれを表示
        return view('users.index', [
            'users' => $users
        ]);
    }
    
    //ユーザー詳細のアクション
    public function show(int $id){
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        //関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(5);

        // ユーザー詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts
        ]);
    }
    
    /**
     * ユーザーのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザーのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
    
    //ユーザーが追加したお気に入りを一覧表示するページを表示するアクション
    public function favorites($id) {
        //idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        
        //関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        //ユーザーのお気に入り一覧を取得
        $favorites = $user->favorites()->paginate(10);
        
        //お気に入り一覧ビューで上記を表示
        return view('users.favorites', ['user' => $user, 'microposts' => $favorites]);
    }
}
