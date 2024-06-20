<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**マイクロポストをお気に入りするアクション
     * @param $id マイクロポストのid
     */
     public function store(string $id){
         //認証済みのユーザー（閲覧者）が、idのマイクロポストをお気に入りする
         \Auth::user()->favorite(intval($id));
         //前のURLへリダイレクトさせる
         return back();
     }
     
     /**マイクロポストのお気に入りを外すアクション
      * @param $id マイクロポストのid
      */
      public function destroy(string $id){
          //認証済みのユーザー（閲覧者）が、idのマイクロポストをお気に入りから外す
          \Auth::user()->unfavorite(intval($id));
          //前のURLへリダイレクトさせる
          return back();
      }
    
}
