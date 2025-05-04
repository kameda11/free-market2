<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Exhibition;
use App\Models\Address;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Exhibitionモデルからすべての出品データを取得
        $exhibitions = Exhibition::all();

        // ビューに渡す
        return view('index', compact('exhibitions'));
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $user = User::with(['exhibitions', 'purchases.exhibition', 'address'])->find($user->id);
        $address = $user->address;

        // 出品した商品を取得（自分の出品のみ）
        $exhibitions = $user->exhibitions()->where('user_id', $user->id)->get();

        // 購入した商品を取得
        $purchases = $user->purchases->map(function ($purchase) {
            return $purchase->exhibition;
        });

        return view('profile', compact('user', 'address', 'exhibitions', 'purchases'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $address = $user->address;
        return view('edit', compact('user', 'address'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();

        // ユーザー名の更新
        $user->name = $request->input('name');

        // プロフィール画像のアップロード
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');

            // ユーザーに紐づく profile を取得（なければ作成）
            $profile = $user->profile ?? new \App\Models\Profile(); // リレーションを使って取得
            $profile->user_id = $user->id;
            $profile->profile_image = $path;
            $profile->save();
        }

        // 住所の更新または作成
        $address = $user->address ?? new Address(); // 既存の住所があれば取得、なければ新規作成
        $address->user_id = auth()->id();
        $address->name = $request->input('name');
        $address->post_code = $request->input('post_code');
        $address->address = $request->input('address');
        $address->building = $request->input('building');
        $address->save();

        return redirect()->route('index')->with('success', 'プロフィールを更新しました');
    }

    public function addresses()
    {
        $user = auth()->user();
        $address = $user->address ?? new Address(); // 住所が存在しない場合は新しいAddressインスタンスを作成
        return view('address', compact('user', 'address'));
    }

    public function edit($item_id)
    {
        $user = auth()->user();
        $address = $user->address;
        return view('edit', compact('user', 'address', 'item_id'));
    }

    public function updateAddress(AddressRequest $request)
    {
        $address = auth()->user()->address; // ログインユーザーに紐づく住所を取得

        // 住所が存在しない場合、新規作成
        if (!$address) {
            $address = new Address();
            $address->user_id = auth()->id();
        }

        // 住所の更新
        $address->update($request->validated());

        return redirect()->route('purchase', [
            'item_id' => session('purchase_item_id'),
        ])->with('success', '住所を更新しました');
    }

    public function purchaseAddress($item_id)
    {
        $user = auth()->user();
        $address = $user->address ?? new Address();
        $exhibition = Exhibition::findOrFail($item_id);

        return view('address', compact('user', 'address', 'exhibition'));
    }
}
