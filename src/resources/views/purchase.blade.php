@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-confirm">
    <h2>購入内容の確認</h2>

    <p><strong>商品名:</strong> {{ $exhibition->name }}</p>
    <img src="{{ asset($exhibition->product_image) }}" alt="商品画像" width="200">
    <p>{{ $exhibition->detail }}</p>
    <p>価格: &yen;{{ number_format($exhibition->price) }}</p>
    <p>数量: {{ $quantity }}</p>
    <p>合計: &yen;{{ number_format($exhibition->price * $quantity) }}</p>

    <form action="{{ route('purchase.complete') }}" method="POST" id="purchase-form">
        @csrf
        <input type="hidden" name="exhibition_id" value="{{ $exhibition->id }}">
        <input type="hidden" name="quantity" value="{{ $quantity }}">
        @if($address)
        <input type="hidden" name="address_id" value="{{ $address->id }}">
        @endif

        <h3>支払い方法</h3>
        <label><input type="radio" name="payment_method" value="1" required checked> コンビニ払い</label><br>
        <label><input type="radio" name="payment_method" value="2" required> カード払い</label>

        <h3>配送先情報</h3>
        @if($address)
        <p>氏名: {{ $address->name }}</p>
        <p>郵便番号: {{ $address->post_code }}</p>
        <p>住所: {{ $address->address }}</p>
        @if($address->building)
        <p>建物名: {{ $address->building }}</p>
        @endif
        <a href="{{ route('purchase.address', ['item_id' => $exhibition->id]) }}">住所を変更する</a>
        @else
        <p>住所が登録されていません。</p>
        <a href="{{ route('purchase.address', ['item_id' => $exhibition->id]) }}">住所を登録する</a>
        @endif
        <button type="submit" id="purchase-button">購入する</button>
    </form>
</div>
@endsection