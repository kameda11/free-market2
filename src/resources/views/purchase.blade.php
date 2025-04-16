@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-confirm">
    <h2>購入内容の確認</h2>

    <p><strong>商品名:</strong> {{ $product->name }}</p>
    <img src="{{ asset($product->product_image) }}" alt="商品画像" width="200">
    <p>{{ $product->detail }}</p>
    <p>価格: &yen;{{ number_format($product->price) }}</p>
    <p>数量: {{ $quantity }}</p>
    <p>合計: &yen;{{ number_format($product->price * $quantity) }}</p>

    <form action="{{ route('purchase.complete') }}" method="POST">
        @csrf
        <input type="hidden" name="item_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="{{ $quantity }}">
        <input type="hidden" name="address_id" value="{{ $address->id }}">

        <h3>支払い方法</h3>
        <label><input type="radio" name="payment_method" value="1" required> コンビニ払い</label><br>
        <label><input type="radio" name="payment_method" value="2" required> カード払い</label>

        <h3>配送先情報</h3>
        <p>{{ $address->name }}</p>
        <p>{{ $address->post_code }}</p>
        <p>{{ $address->address }} {{ $address->building }}</p>
        <p><a href="{{ route('edit') }}">住所を変更する</a></p>

        <button type="submit">購入する</button>
    </form>
</div>
@endsection