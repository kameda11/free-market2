@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="left-column">
        <img src="{{ asset('storage/' . $exhibition->product_image) }}" alt="image" class="product-image">
    </div>

    <div class="right-column">
        <h2>{{ $exhibition->name }}</h2>

        @if(!empty($exhibition->brand))
        <h4>ブランド：{{ $exhibition->brand }}</h4>
        @endif

        <p>{{ $exhibition->detail }}</p>

        <div class="product-info">
            <p>カテゴリ：
            @php
                $categories = json_decode($exhibition->category, true);
            @endphp

            @if(!empty($categories) && is_array($categories))
                 {{ implode(' / ', $categories) }}
            @else
                不明
            @endif
            </p>
    <p>商品の状態：
        @php
            $conditionLabels = [
                'brand_new' => '新品・未使用',
                'used_like_new' => '未使用に近い',
                'used_good' => '目立った傷や汚れなし',
                'used_acceptable' => 'やや傷や汚れあり',
                'used_poor' => '全体的に状態が悪い',
            ];
        @endphp
        {{ $conditionLabels[$exhibition->condition] ?? '不明' }}
    </p>
    <p>価格：&yen; {{ number_format($exhibition->price) }}</p>
</div>

        {{-- お気に入り登録数・コメント数 --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
        $isFavorited = Auth::check() && $exhibition->favorites->contains('user_id', Auth::id());
        @endphp

        <button class="favorite-button" data-id="{{ $exhibition->id }}">
            <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-star"></i>
            <span class="favorite-count">{{ $exhibition->favorites->count() }}</span>
        </button>

            <p><i class="far fa-comment"></i>{{ $exhibition->comments->count() }}</p>

        <form action="{{ route('purchase.confirm', ['item_id' => $exhibition->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $exhibition->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit">購入手続きへ</button>
        </form>

        {{-- コメント表示 --}}
        <div class="comments">
            <h3>他のユーザーのコメント</h3>
            @foreach($exhibition->comments as $comment)
            <div class="comment">
                @if($comment->user && $comment->user->profile && $comment->user->profile->profile_image)
                <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="profile"
                    class="profile-image">
                @else
                <img src="{{ asset('images/default-profile.png') }}" alt="default profile" class="profile-image">
                @endif

                <div class="comment-content">
                    <strong>{{ $comment->user->name ?? '名無し' }}</strong>
                    <p>{{ $comment->comment }}</p>
                    <small>{{ $comment->created_at->format('Y/m/d H:i') }}</small>
                </div>
            </div>
            @endforeach
        </div>

        {{-- コメント投稿フォーム --}}
        <div class="comment-form">
            <h4>コメントを投稿する</h4>
            @if(session('success'))
            <p class="success">{{ session('success') }}</p>
            @endif
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="exhibition_id" value="{{ $exhibition->id }}">
                {{-- user_name 入力は削除 --}}
                <div>
                    <label for="comment">コメント:</label>
                    <textarea name="comment" id="comment" required></textarea>
                </div>
                <button type="submit">投稿</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.favorite-button').forEach(button => {
        button.addEventListener('click', function () {
            const exhibitionId = this.dataset.id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ exhibition_id: exhibitionId })
            })
            .then(response => response.json())
            .then(data => {
                const icon = this.querySelector('i');
                const countSpan = this.querySelector('.favorite-count');

                if (data.status === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else if (data.status === 'removed') {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }

                countSpan.textContent = data.count;
            })
            .catch(error => console.error('通信エラー:', error));
        });
    });
});
</script>
@endsection