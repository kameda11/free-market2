@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email__content">
    <div class="verify-email__message">登録していただいたメールアドレスに認証メールを送付しました。<br>メール内のリンクから認証を完了してください。
    </div>

    <div class="verify-email__actions">
        <form method="GET" action="{{ route('edit') }}">
            <button type="submit" class="verify-email__check-btn">認証はこちらから</button>
        </form>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="verify-email__resend-btn">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection