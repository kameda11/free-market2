<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Favorite;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\Comment;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function add(Request $request)
    {
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity', 1);

        // 商品情報を取得
        $item = Exhibition::findOrFail($itemId);

        // セッションからカートを取得（なければ空の配列）
        $cart = session()->get('cart', []);

        // すでにカートにある場合は数量を追加
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $quantity;
        } else {
            // カートに追加
            $cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'image' => $item->product_image,
                'quantity' => $quantity,
            ];
        }

        // カートをセッションに保存
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'カートに追加しました。');
    }

    public function index()
    {
        $userId = Auth::id();

        // 自分以外の商品で、購入されていない or 購入されているかどうかを見てフィルタ
        $allExhibitions = Exhibition::where('user_id', '!=', $userId)
            ->with('purchases') // ← purchase情報を事前ロード（あとで便利）
            ->get();

        // お気に入り商品のうち自分の出品を除く
        $favoriteExhibitions = Exhibition::whereIn('id', function ($query) use ($userId) {
            $query->select('exhibition_id')
                ->from('favorites')
                ->where('user_id', $userId);
        })
            ->where('user_id', '!=', $userId)
            ->with('purchases')
            ->get();

        return view('index', compact('allExhibitions', 'favoriteExhibitions'));
    }

    public function confirm(PurchaseRequest $request, $item_id)
    {
        $product = Exhibition::findOrFail($item_id);
        $quantity = $request->quantity;
        $user = auth()->user();

    // ユーザーの住所を取得（ここでは1件仮定）
        $address = Address::first(); // 実際はuser_idなどで取得

        session([
            'purchase_item_id' => $product->id,
            'purchase_quantity' => $quantity,
        ]);
    
        return view('purchase', compact('product', 'quantity', 'address'));
       }

    public function create()
    {
        return view('sell'); // sell.blade.phpを表示
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        // 画像処理
        $path = $request->hasFile('product_image')
            ? $request->file('product_image')->store('products', 'public')
            : 'products/default.jpg';

        // カテゴリーをJSONとして保存（ここがポイント）
        $categories = json_encode($validated['category']);

        $data = [
            'name' => $validated['name'],
            'brand' => $validated['brand'] ?? null,
            'detail' => $validated['detail'],
            'category' => $categories, // ← JSON文字列
            'condition' => $validated['condition'],
            'price' => $validated['price'],
            'user_id' => auth()->id(),
            'product_image' => $path,
        ];

        Exhibition::create($data);

        return redirect()->route('index')->with('success', '商品を出品しました！');
    }

    public function __construct()
    {
        $this->middleware('auth');  // 認証されていない場合、コメント投稿はできない
    }

    public function storeComment(CommentRequest $request)
    {
        $validated = $request->validated();

        Comment::create([
            'user_id' => Auth::id(), // ← ログインユーザーのIDを保存
            'exhibition_id' => $validated['exhibition_id'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'コメントを投稿しました！');
    }

    public function show($item_id)
    {
        $exhibition = Exhibition::findOrFail($item_id);
        return view('detail', compact('exhibition'));
    }

    public function storeFavorite(Request $request)
    {
        // 認証ユーザー前提の場合
        $request->validate([
            'exhibition_id' => 'required|exists:exhibitions,id',
        ]);

        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'exhibition_id' => $request->exhibition_id,
        ]);

        return back()->with('success', 'お気に入りに追加しました');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'exhibition_id' => 'required|exists:exhibitions,id',
        ]);

        $exhibitionId = $request->input('exhibition_id');
        $user = $request->user(); // auth()->user() より読みやすい

        $favorite = Favorite::where('user_id', $user->id)
            ->where('exhibition_id', $exhibitionId)
            ->first();

        $status = 'added';

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'exhibition_id' => $exhibitionId,
            ]);
        }

        $count = Favorite::where('exhibition_id', $exhibitionId)->count();

        return response()->json([
            'status' => $status,
            'count' => $count,
        ]);
    }
}

