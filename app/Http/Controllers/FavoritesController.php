<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    
    // 555番目の投稿をお気に入り登録する
    // http://laravel-microposts.herokuapp.com/microposts/555/favorite [POST形式]
    public function store(Request $request, $id)
    {
        //$microposts = \App\Micropost::find($id);
        //$microposts->favorite();
        \Auth::user()->favorite($id);

        return back();
    }


    
    // 555番目の投稿を自分のお気に入りから削除する（元の画面に戻る）
    // http://laravel-microposts.herokuapp.com/microposts/555/unfavorite [DELETE形式]
    public function destroy($id)
    {
        //$microposts = \App\Micropost::find($id);
        //$microposts->unfavorite();
        \Auth::user()->unfavorite($id);

        return back();
    }
}
