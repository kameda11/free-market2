@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="left-column">
        <img src="{{ $exhibition->product_image }}" alt="image" class="card__image">
    </div>

    <div class="right-column">
        <h2>{{ $exhibition->name }}</h2>

        @if(!empty($exhibition->brand))
        <h4>ブランド：{{ $exhibition->brand }}</h4>
        @endif

        <p>
            <span class="price-symbol">&yen;</span>
            <span class="price-number">{{ number_format($exhibition->price) }}</span>
            <span class="price-tax">(税込)</span>
        </p>

        {{-- アイコン表示 --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
        $isFavorited = Auth::check() && $exhibition->favorites->contains('user_id', Auth::id());
        @endphp

        <div class="icon-group">
            <div class="icon-block">
                <button class="favorite-button" data-id="{{ $exhibition->id }}">
                    <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-star"></i>
                </button>
                <span class="favorite-count">{{ $exhibition->favorites->count() }}</span>
            </div>
            <div class="icon-block">
                <i class="fa-regular fa-comment-dots"></i>
                <span class="comment-count">{{ $exhibition->comments->count() }}</span>
            </div>
        </div>

        <form action="{{ route('purchase', ['exhibition_id' => $exhibition->id]) }}" method="GET">
            <div class="purchase-button">
                <button type="submit">購入手続きへ</button>
            </div>
        </form>

        <h3>商品説明</h3>
        <p>{{ $exhibition->detail }}</p>

        <h3>商品の情報</h3>
        <div class="product-info">
            <div class="product-info-item">
                <span class="product-info-label">カテゴリー</span>
                @php
                $categories = json_decode($exhibition->category, true);
                @endphp

                @if(!empty($categories) && is_array($categories))
                <div class="category-tags">
                    @foreach($categories as $category)
                    <span class="category-tag">{{ $category }}</span>
                    @endforeach
                </div>
                @else
                <span class="category-tag">不明</span>
                @endif
            </div>

            <div class="product-info-item">
                <span class="product-info-label">商品の状態</span>
                @php
                $conditionLabels = [
                'brand_new' => '新品・未使用',
                'used_like_new' => '未使用に近い',
                'used_good' => '目立った傷や汚れなし',
                'used_acceptable' => 'やや傷や汚れあり',
                'used_poor' => '全体的に状態が悪い',
                ];
                @endphp
                <span class="condition-label">{{ $conditionLabels[$exhibition->condition] ?? '不明' }}</span>
            </div>
        </div>

        {{-- コメント一覧 --}}
        <div class="comments">
            <div class="comment-header">
                <span class="comment-title">コメント</span>
                <span class="comment-counts">({{ $exhibition->comments->count() }})</span>
            </div>
            @foreach($exhibition->comments as $comment)
            <div class="comment">
                <div class="comment-header">
                    @if($comment->user && $comment->user->profile && $comment->user->profile->profile_image)
                    <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="profile" class="profile-image">
                    @else
                    <img src="{{ asset('images/profile.png') }}" alt="default profile" class="profile-image">
                    @endif
                    <div class="comment-user">{{ $comment->user->name ?? '名無し' }}</div>
                </div>
                <div class="comment-body">
                    <p>{{ $comment->comment }}</p>
                    <small>{{ $comment->created_at->format('Y/m/d H:i') }}</small>
                </div>
            </div>
            @endforeach
        </div>

        {{-- コメント投稿 --}}
        <div class="comment-form">
            <h4>商品へのコメント</h4>
            @if(session('success'))
            <p class="success">{{ session('success') }}</p>
            @endif
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="exhibition_id" value="{{ $exhibition->id }}">
                <textarea name="comment" id="comment" required></textarea>
                <button type="submit">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.favorite-button').forEach(button => {
            button.addEventListener('click', function() {
                const exhibitionId = this.dataset.id;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/favorites/toggle', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            exhibition_id: exhibitionId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const icon = this.querySelector('i');
                        const countSpan = this.closest('.icon-block').querySelector('.favorite-count');

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