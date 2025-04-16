@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-edit">
    <h2>配送先の編集</h2>

    <form action="{{ route('address.update') }}" method="POST">
        @csrf
        <label>氏名: <input type="text" name="name" value="{{ old('name', $address->name) }}"></label><br>
        <label>郵便番号: <input type="text" name="post_code" value="{{ old('post_code', $address->post_code) }}"></label><br>
        <label>住所: <input type="text" name="address" value="{{ old('address', $address->address) }}"></label><br>
        <label>建物名（任意）: <input type="text" name="building" value="{{ old('building', $address->building) }}"></label><br>

        <button type="submit">更新する</button>
    </form>
</div>
@endsection