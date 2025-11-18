<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      {{-- サブカテゴリー表示を追加 --}}
      @if($post->subCategories->isNotEmpty())
        <div class="d-flex flex-wrap mt-2">
            @foreach($post->subCategories as $subCategory)
                <span class="bg-gray-200 text-gray-700 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $subCategory->sub_category }}</span>
            @endforeach
        </div>
      @endif
      {{-- /サブカテゴリー表示を追加 --}}
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
          <div class="mr-5">
            <i class="fa fa-comment"></i><span class="">{{ $post->postComments->count() }}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span></p>
            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span></p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- 検索エリアを更新 --}}
<div class="other_area">
  <div class="other_area_inner">
    <div class="post_input_area">
      <a href="{{ route('post.input') }}" class="btn-post">投稿</a>
    </div>

    {{-- 1. キーワード検索 --}}
    <div class="keyword_search_box">
      <div class="input_group">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest" class="form_control">
        <div class="input_group_append">
          <input type="submit" value="検索" form="postSearchRequest" class="btn-search">
        </div>
      </div>
    </div>

    {{-- 2. 特殊な絞り込み --}}
    <div class="filter_buttons">
      <input type="submit" name="like_posts" class="btn-filter btn-outline-danger" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="btn-filter btn-outline-success" value="自分の投稿" form="postSearchRequest">
    </div>

    {{-- 3. カテゴリー検索エリア (メイン/サブカテゴリー一覧) --}}
    <div class="category_search_box">
        {{-- MainCategoryごとにループ処理 --}}
        @foreach($categories as $main_category)
            <div class="main_category_item">
                {{-- メインカテゴリー名 --}}
                <span class="main_category_name">{{ $main_category->main_category }}</span>

                <div class="sub_category_list">
                    {{-- サブカテゴリーをループで表示 --}}
                    @foreach($main_category->subCategories as $sub_category)
                        <button type="submit" name="category_id" value="{{ $sub_category->id }}" form="postSearchRequest"
                           class="btn-sub-category">
                            {{ $sub_category->sub_category }}
                        </button>
                    @endforeach
                </div>
            </div>
            @if(!$loop->last)
                <div class="separator"></div> {{-- メインカテゴリー間の区切り線 --}}
            @endif
        @endforeach
    </div>
    {{-- /カテゴリー検索エリア --}}

    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
</x-sidebar>

<script>
// jQuery ($) が使える環境を前提としています
// DOMContentLoadedの代わりにjQueryのready関数を使用

$(function() {
    // 1. いいね！ボタンがクリックされた時の処理 (初期状態: いいね！がまだされていない投稿)
    $(document).on('click', '.like_btn', function(e) {
        e.preventDefault();
        // 投稿IDを取得
        const postId = $(this).attr('post_id');

        // 🚨 修正点: ルーティングに合わせ、URLに投稿IDを含める ( /like/post/123 の形式 )
        const url = `/like/post/${postId}`;

        // CSRFトークンを取得
        const token = $('meta[name="csrf-token"]').attr('content');

        // サーバーへPOSTリクエストを送信
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            // post_idはURLに含まれているため、ここではbodyは空でOK
            body: JSON.stringify({})
        })
        .then(response => {
            // ステータスコードが200番台でなければエラーを投げる
            if (!response.ok) {
                return response.json().then(error => {
                    throw new Error(`Server Error: ${response.status} - ${error.message || '不明なエラー'}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // 成功した後のDOM操作
            if (data.success) {
                const likeBtn = $(`.like_btn[post_id='${postId}']`);
                const likeCounts = $(`.like_counts${postId}`);

                // クラスを入れ替え（いいね！済み状態へ）
                likeBtn.removeClass('like_btn text-secondary').addClass('un_like_btn text-danger');

                // カウント数を更新
                likeCounts.text(data.likeCount);
            }
        })
        .catch(error => {
            console.error('いいね！追加エラー:', error.message);
            // ユーザーにエラーを通知
            // alert('いいねの処理中にエラーが発生しました。');
        });
    });

    // 2. いいね！解除ボタンがクリックされた時の処理 (初期状態: いいね！がされている投稿)
    $(document).on('click', '.un_like_btn', function(e) {
        e.preventDefault();
        // 投稿IDを取得
        const postId = $(this).attr('post_id');

        // 🚨 修正点: ルーティングに合わせ、URLに投稿IDを含める ( /unlike/post/123 の形式 )
        const url = `/unlike/post/${postId}`;

        // CSRFトークンを取得
        const token = $('meta[name="csrf-token"]').attr('content');

        // サーバーへPOSTリクエストを送信
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            // post_idはURLに含まれているため、ここではbodyは空でOK
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(error => {
                    throw new Error(`Server Error: ${response.status} - ${error.message || '不明なエラー'}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // 成功した後のDOM操作
            if (data.success) {
                const unLikeBtn = $(`.un_like_btn[post_id='${postId}']`);
                const likeCounts = $(`.like_counts${postId}`);

                // クラスを入れ替え（いいね！未済状態へ）
                unLikeBtn.removeClass('un_like_btn text-danger').addClass('like_btn text-secondary');

                // カウント数を更新
                likeCounts.text(data.likeCount);
            }
        })
        .catch(error => {
            console.error('いいね！解除エラー:', error.message);
            // ユーザーにエラーを通知
            // alert('いいね解除の処理中にエラーが発生しました。');
        });
    });
});
</script>
