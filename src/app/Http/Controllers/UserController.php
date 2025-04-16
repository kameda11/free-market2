<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Exhibition;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\Address;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;


class UserController extends Controller
{
    public function index()
    {
        // Exhibitionモデルからすべての出品データを取得
        $exhibitions = Exhibition::all();

        // ビューに渡す
        return view('index', compact('exhibitions'));
    }

    public function profile()
    {
        $user = auth()->user();
        $address = $user->address;
        $exhibitions = Exhibition::where('user_id', $user->id)->get(); // 出品商品
        $purchases = $user->purchases ?? []; // 購入商品（リレーション設定している前提）

        return view('profile', compact('user', 'address', 'exhibitions', 'purchases'));
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
            $profile = $user->profile ?: new \App\Models\Profile();
            $profile->user_id = $user->id;
            $profile->profile_image = $path;
            $profile->save();
        }

        // 住所の更新（Addressモデルへ）
        $address = new Address();
        $address->user_id = auth()->id();
        $address->name = $request->input('name');
        $address->post_code = $request->input('post_code');
        $address->address = $request->input('address');
        $address->building = $request->input('building');
        $address->save();

        return redirect()->route('index')->with('success', 'プロフィールを更新しました');
    }

    public function address()
    {
        $user = auth()->user();
        $address = $user->address;
        return view('address', compact('user', 'address'));
    }

    public function edit()
    {
        $user = auth()->user();
        $address = $user->address;
        return view('edit', compact('user'));
    }

    public function updateAddress(AddressRequest $request)
    {
        $address = auth()->user()->address; // ログインユーザーに紐づく住所を更新

        // ユーザーが住所を持っていない場合、新しく作成する
        if (!$address) {
            $address = new Address();
            $address->user_id = auth()->id();
        }

        $address->update($request->validated());

        return redirect()->route('purchase.confirm', [
            'id' => session('purchase_item_id'),
            'quantity' => session('purchase_quantity'),
        ])->with('success', '住所を更新しました');
    }
}
