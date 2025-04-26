@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile__container">
    {{-- ユーザー情報表示 --}}
    <div class="profile__info">
        {{-- プロフィール画像 --}}
        <div class="profile__image">
            <img src="{{ asset('storage/' . (optional($user->profile)->profile_image ?? 'profile.png')) }}" alt="プロフィール画像" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
        </div>
        <p>{{ $address->name ?? $user->name }}</p>
        <a href="{{ route('profile.edit') }}" class="profile__edit-button">プロフィール編集</a>
    </div>

    {{-- 出品商品と購入商品の切り替えタブ --}}
    <div class="profile__tabs">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}">
            <button class="{{ request('tab') === 'sell' ? 'active' : '' }}">出品商品</button>
        </a>
        <a href="{{ route('mypage', ['tab' => 'buy']) }}">
            <button class="{{ request('tab') === 'buy' ? 'active' : '' }}">購入商品</button>
        </a>
    </div>

    @php
    $tab = request('tab', 'sell'); // デフォルトを 'sell'
    @endphp

    {{-- 出品商品 --}}
    @if ($tab === 'sell')
    <div class="profile__tab-content">
        @forelse ($exhibitions as $exhibition)
        <a href="{{ route('detail', $exhibition->id) }}" class="card__button card__button--compact">
            <div class="l-wrapper">
                <article class="card">
                    <figure class="card__thumbnail">
                        <img src="{{ asset('storage/' . $exhibition->product_image) }}" alt="image" class="card__image">
                        @if($exhibition->sold)
                        <span class="sold-label">Sold</span>
                        @endif
                    </figure>
                    <h3 class="card__title">{{ $exhibition->name }}</h3>
                </article>
            </div>
        </a>
        @empty
        <p>出品商品はありません。</p>
        @endforelse
    </div>

    {{-- 購入商品 --}}
    @else
    <div class="profile__tab-content">
        @forelse ($purchases as $exhibition)
        <a href="{{ route('detail', $exhibition->id) }}" class="card__button card__button--compact">
            <div class="l-wrapper">
                <article class="card">
                    <figure class="card__thumbnail">
                        <img src="{{ asset('storage/' . $exhibition->product_image) }}" alt="image" class="card__image">
                    </figure>
                    <h3 class="card__title">{{ $exhibition->name }}</h3>
                </article>
            </div>
        </a>
        @empty
        <p>購入商品はありません。</p>
        @endforelse
    </div>
    @endif
</div>

@endsection