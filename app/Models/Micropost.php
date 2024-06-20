<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    use HasFactory;
    
    protected $fillable = ['content'];  //fillableで一気に保存可能なパラメータの設定
    
    //userメソッドを定義(Micropostモデルから関連するUserモデルにアクセスできる)
    public function user(){
        return $this->belongsTo(User::class);  //belongsToメソッドで一対多を定義するため使用。User::classは関連するモデルクラスを指定。
    }
    
    //このマイクロポストをお気に入り中のユーザー（多対多）
    public function favorite_users(){
        return $this->belongsTomany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }
}
