@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile__container">
    <h2>プロフィールページ</h2>

    {{-- ユーザー情報表示 --}}
    <div class="profile__info">
        <p><strong>ユーザー名：</strong>{{ $address->name ?? $user->name }}</p>
        <a href="{{ route('edit') }}" class="profile__edit-button">プロフィール編集</a>
    </div>

    {{-- 出品商品と購入商品の切り替えタブ --}}
    <div class="profile__tabs">
        <button onclick="showTab('exhibitions')">出品商品</button>
        <button onclick="showTab('purchases')">購入商品</button>
    </div>

    <div id="exhibitions" class="profile__tab-content">
        <h3>出品商品</h3>
        @forelse ($exhibitions as $item)
        <div class="product__item">
            <p>{{ $item->title }}</p>
            <p>{{ $item->price }}円</p>
        </div>
        @empty
        <p>出品商品はありません。</p>
        @endforelse
    </div>

    <div id="purchases" class="profile__tab-content" style="display: none;">
        <h3>購入商品</h3>
        @forelse ($purchases as $item)
        <div class="product__item">
            <p>{{ $item->title }}</p>
            <p>{{ $item->price }}円</p>
        </div>
        @empty
        <p>購入商品はありません。</p>
        @endforelse
    </div>
</div>

<script>
    function showTab(tab) {
        document.getElementById('exhibitions').style.display = 'none';
        document.getElementById('purchases').style.display = 'none';
        document.getElementById(tab).style.display = 'block';
    }
</script>

@endsection