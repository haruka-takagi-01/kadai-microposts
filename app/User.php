<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    ////　リレーション定義
    //　ユーザーは複数の投稿を持っている
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    //　ユーザーとユーザーは中間テーブルuser_followを通いて多対多の関係である
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    //　ユーザーとユーザーは中間テーブルuser_followを通いて多対多の関係である
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    //　ユーザーと投稿は中間テーブルfavoritesを通いて多対多の関係である
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();;
    }


    ////　関数
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    

    public function favorite($micropost_id)
    {
        // 既にお気に入り登録しているかの確認
        $exist = $this->is_favorite($micropost_id);
        // 相手が自分自身ではないかの確認
        //$its_me = $this->id == $userId;
        // いったん無視
        $its_me = false;
    
        if ($exist || $its_me) {
            // 既にお気に入り登録していれば何もしない
            return false;
        } else {
            // お気に入り未登録であればフォローする
            $this->favorites()->attach($micropost_id);
            return true;
        }
    }

    public function unfavorite($micropost_id)
    {
        // 既にお気に入り登録しているかの確認
        $exist = $this->is_favorite($micropost_id);
        // 相手が自分自身ではないかの確認
        //$its_me = $this->id == $userId;
        // いったん無視
        $its_me = false;
    
        if ($exist && !$its_me) {
            // 既にお気に入り登録していればフォローを外す
            $this->favorites()->detach($micropost_id);
            return true;
        } else {
            // お気に入り未登録であれば何もしない
            return false;
        }
    }

    //// 状態確認
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function is_favorite($micropost_id)
    {
        return $this->favorites()->where('micropost_id', $micropost_id)->exists();
    }
    
}
