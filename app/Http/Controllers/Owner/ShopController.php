<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        // オーナーでログインしているかの確認
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next){
            // dd($request->route()->parameter('shop'));//urlから取得した数字は文字列
            // dd(Auth::id());//こっちは数字になる

            $id = $request->route()->parameter('shop'); //shopのid取得
            if(!is_null($id)){ // null判定
            $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                    $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換
                    $ownerId = Auth::id();
                    if($shopId !== $ownerId){ // 同じでなかったら
                    abort(404); // 404画面表示
                    }
            }
            return $next($request);
        });
    }

    public function index() {
        // $ownerId = Auth::id();
        $shops = Shop::where('owner_id', Auth::id())->get();
        // ショップモデルでオーナーidで検索して、引っかかった物をgetする。

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id) {
        dd(Shop::findOrFail($id));
    }

    public function update() {

    }

}