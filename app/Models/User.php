<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    //micropostsメソッドを定義(Userモデルから関連するMicropostモデルにアクセスできる)
    public function microposts(){
        return $this->hasMany(Micropost::class);    //hasManyメソッドは一対多を定義するため使用。Micropost::classは関連するモデルクラスを指定
    }
    
    //ユーザーに関係するモデルの件数をロードする
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);
    }
    
    //このユーザーがフォロー中のユーザー。（Userモデルとの関係を定義）
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    //このユーザーをフォロー中のユーザー。（Userモデルとの関係を定義)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    //$userIdで指定されたユーザーをフォローする
    public function follow(int $userId)
    {
        $exist = $this->is_following($userId);  //存在するか確認
        $its_me = $this->id == $userId;         //自分のidを取得
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    //$userIdで指定されたユーザーをアンフォローする
    public function unfollow(int $userId)
    {
        $exist = $this->is_following($userId);  //存在するかis_followingメソッドで確認
        $its_me = $this->id == $userId;         //自分のidかチェックしtrueかfalse
        
        if ($exist && !$its_me) {                   //一致しないか確認
            $this->followings()->detach($userId);   //detachを使用して削除
            return true;
        } else {
            return false;
        }
    }
    
    //指定された$userIdのユーザーをこのユーザーがフォロー中であるか調べる。フォロー中ならtrueを返す
    public function is_following(int $userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    //このユーザーとフォロー中のユーザーの投稿に絞り込む
    public function feed_microposts(){
        //このユーザーがフォロー中のユーザーidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        //このユーザーのidもその配列に追加
        $userIds[] = $this->id;
        //それらのユーザーが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    
    //このユーザーがお気に入り中のマイクロポスト
    public function favorites() {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    //このマイクロポストをお気に入り中のユーザー・・・【不要かも(マイクロポストモデルに記載】
    // public function favorite_users() {
    //     return $this->belongsTomany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    // }
    
    //$userIdで指定されたマイクロポストをお気に入りする
    public function favorite(int $micropostId) {
        $exist = $this->is_favorite($micropostId);  //マイクロポストのidが存在すればtrue（以下if文ですでにお気に入りしている為falseになる）
        // $its_me = $this->id == $micropostId;     //自分のidとマイクロポストのidを比較する必要が無い為、不要（間違い）
        
        if($exist) {
            return false;
        } else {
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    //$userIdで指定されたマイクロポストのお気に入りを外す。
    public function unfavorite(int $micropostId) {
        $exist = $this->is_favorite($micropostId);  //マイクロポストのidが存在すればtrue(すでにお気に入りに)
        //$its_me = $this->id == $micropostId;      //自分のidとマイクロポストのidを比較する必要が無い為、不要（間違い）
        
        if($exist) {
            $this->favorites()->detach($micropostId);
            return true;
        }else{
            return false;
        }
    }
    
    
    //指定された$userIdのユーザーがこのマイクロポストをお気に入り中であるか調べる。お気に入り中ならtrueを返す。
    public function is_favorite(int $micropostId) {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }

}
