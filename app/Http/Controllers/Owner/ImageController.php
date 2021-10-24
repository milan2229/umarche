<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use App\Http\Requests\uploadImageRequest;
use App\Services\ImageService;



class ImageController extends Controller
{
    public function __construct()
    {
        // オーナーでログインしているかの確認
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next){

            $id = $request->route()->parameter('image');
            if(!is_null($id)){ // null判定
            $imagesOwnerId = Image::findOrFail($id)->owner->id;
                    $imageId = (int)$imagesOwnerId; // キャスト 文字列→数値に型変換
                    // $ownerId = Auth::id();
                    if($imageId !== Auth::id()){ // 同じでなかったら
                    abort(404); // 404画面表示
                    }
            }
            return $next($request);
        });
    }


    public function index()
    {
        $images = Image::where('owner_id', Auth::id())->orderBy('updated_at', 'desc')->paginate(20);

        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(uploadImageRequest $request)
    {
        $imageFiles = $request->file('files');
        // dd($$imageFiles = $request->file('files'));
        if(!is_null($imageFiles)) {
            foreach($imageFiles as $imageFile){
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                    ]); 
                }
        }

        return redirect()->route('owner.images.index')->with(['message'=>'画像登録を実施しました。','status'=>'info']);
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['string', 'max:50']
        ]);

    $image = Image::findOrFail($id);
    $image->title = $request->title;
    $image->save();
    
    return redirect()->route('owner.images.index')->with(['message'=>'画像情報を更新しました。','status'=>'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //削除処理をする前にstorageの画像を削除する必要がある。
        $image = Image::findOrFail($id);//Imageのインスタンスをとる
        $filePath = 'public/products/' . $image->filename;//パスを表記して画像のありかを示す
        
        if(Storage::exists($filePath)){
            Storage::delete('file','otherFile');
        }

        Image::findOrFail($id)->delete();

        return redirect()->route('owner.images.index')->with(['message'=>'画像を削除しました。','status'=>'alert']);
    }
}
