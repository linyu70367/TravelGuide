@extends("front.layout")

@section("title")
{{ $detail->name }} - 旅遊景點指南
@endsection

@push("style")
<link rel="stylesheet" href="{{ asset('css/front/views_detail.css') }}?v={{ time() }}">
<!-- 引入 Fancybox 5 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

@endpush

@section("content")
<!-- 1. Hero 頂部區塊 (動態變數) -->
<div class="page-hero">
  <div class="container">
    <h1>{{ $detail->name }}</h1>
    <div class="bc">
      <a href="/">首頁</a>
      <i class="fa fa-chevron-right" style="font-size:10px"></i>
      <a href="/views">旅遊景點</a>
      <i class="fa fa-chevron-right" style="font-size:10px"></i>
      {{ $detail->types?->typeName ?? '景點資訊' }}
    </div>
  </div>
</div>

<div class="section">
  <div class="container">
    <div class="layout-2col">

      <div>
        <!-- 文章/景點主要卡片 -->
        <div class="article-card">

          <!-- 🖼️ 相簿展示區 -->
          @if (!empty($detail->imgs) && $detail->imgs->isNotEmpty())
          <div class="views-gallery mb-4">

            <!-- (1) 主展示大圖：固定綁定第 1 張圖片 -->
            <div class="main-cover-box mb-3">
              <a href="/images/views/{{ $detail->imgs->first()->imgSrc }}"
                data-fancybox="gallery"
                data-thumb="/images/views/S/{{ $detail->imgs->first()->imgSrc }}"
                data-caption="{{ $detail->name }} - 圖 1">
                <img src="/images/views/{{ $detail->imgs->first()->imgSrc }}"
                  class="article-cover rounded shadow-sm w-100"
                  alt="{{ $detail->name }}"
                  style="max-height: 450px; object-fit: cover;">
              </a>
            </div>

            <!-- (2) 下方小相簿列表：跳過第 1 張 (skip(1))，只印第 2 張之後的圖片 -->
            @if($detail->imgs->count() > 1)
            <div class="d-flex gap-2 flex-wrap thumbnail-gallery">
              @foreach($detail->imgs->skip(1) as $index => $img)
              <a href="/images/views/{{ $img->imgSrc }}"
                data-fancybox="gallery"
                data-thumb="/images/views/S/{{ $img->imgSrc }}"
                data-caption="{{ $detail->name }} - 圖 {{ $index + 2 }}"
                class="thumb-link">
                <img src="/images/views/S/{{ $img->imgSrc }}"
                  class="rounded border img-fluid"
                  alt="縮圖 {{ $index + 2 }}">
              </a>
              @endforeach
            </div>
            @endif

          </div>
          @else
          <!-- 無圖片時的預設展示框 -->
          <div class="article-cover d-flex align-items-center justify-content-center bg-light rounded mb-4" style="height: 250px;">
            <i class="fa fa-newspaper fa-3x text-secondary"></i>
          </div>
          @endif

          <!-- 文章內容區 -->
          <div class="article-body">
            <div class="d-flex align-items-center">
              <h2 class="article-title fw-bold my-3" style="color: var(--brand);">
                {{ $detail->name }}
              </h2>


            </div>
            <!-- 地址與電話 (已修正為 Blade 語法) -->
            <div class="d-flex align-items-center flex-wrap gap-3 text-muted small mb-3">
              @if($detail->city || $detail->town || $detail->address)
              <div>
                <i class="fa fa-map-marker-alt text-danger me-1"></i>
                {{ $detail->city }}{{ $detail->town }}{{ $detail->address }}
              </div>
              @endif

              @if($detail->tel)
              <div>
                <i class="fa fa-phone text-primary me-1"></i>
                {{ $detail->tel }}
              </div>
              @endif

              <div class="d-flex align-items-center ms-auto" id="app">
                <!-- 愛心按鈕 -->
                <div class="stats-item m-1">
                  <button type="button" class="btn favorite-btn p-0" v-on:click="toggleFavorite">
                    <i
                      v-bind:class="isLiked ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"
                      style="color: rgb(233, 122, 122);"></i>
                  </button>
                </div>

                <!-- 點讚數字 (同步 Vue 資料) -->
                <div class="stats-item m-1">
                  <span>@{{ likeCount }} 次收藏</span>
                </div>

                <!-- 瀏覽次數 -->
                <div class="stats-item m-1">
                  <i class="fa-solid fa-eye" style="color: rgb(116, 192, 252);"></i>
                  <span>{{ $detail->like }}次瀏覽</span>
                </div>
              </div>
            </div>

            <!-- 景點介紹文案 -->
            <div class="article-content mt-3">
              {!! $detail->content !!}
            </div>
          </div>

          <!-- 分類標籤 -->
          <div class="article-meta mb-2 mt-4 pt-3 border-top">
            <span class="news-badge">{{ $detail->types?->typeName ?? '未分類' }}</span>
          </div>

          <!-- 上一則 / 下一則 (無邊框) -->
          <div class="article-nav d-flex justify-content-between align-items-center pt-3">
            @if($prevViews)
            <a href="/views/{{ $prevViews->id }}" class="btn-nav-prev text-decoration-none">
              <i class="fa fa-chevron-left me-1"></i> 上一則：{{ $prevViews->name }}
            </a>
            @else
            <span></span>
            @endif

            @if($nextViews)
            <a href="/views/{{ $nextViews->id }}" class="btn-nav-next text-decoration-none text-end">
              下一則：{{ $nextViews->name }} <i class="fa fa-chevron-right ms-1"></i>
            </a>
            @else
            <span></span>
            @endif
          </div>
        </div>

        <!-- 返回按鈕 -->
        <a href="/views" class="back-btn mt-3 mb-3 d-inline-block text-decoration-none">
          <i class="fa fa-arrow-left me-1"></i> 返回列表
        </a>
      </div>

      {{-- 側邊欄 Sidebar --}}
      <div>
        <div class="sidebar-card mb-4">
          <div class="sidebar-title fw-bold mb-2"><i class="fa fa-list me-1"></i> 景點分類</div>
          @foreach($list as $data)
          <div class="cat-list-item py-1">
            <a href="/views?type={{ $data->id }}" class="text-decoration-none">{{ $data->typeName }}</a>
          </div>
          @endforeach
        </div>

        <div class="sidebar-card">
          <div class="sidebar-title fw-bold mb-3"><i class="fa fa-fire text-danger me-1"></i> 熱門景點</div>
          @foreach($recentViews as $item)
          <div class="recent-item d-flex align-items-center mb-3">

            <!-- 景點縮圖 (1:1 正方形) -->
            <a href="/views/{{ $item->id }}" class="me-3 text-decoration-none">
              @if(!empty($item->imgs) && $item->imgs->isNotEmpty() && $item->imgs->first()->imgSrc)
              <img src="/images/views/S/{{ $item->imgs->first()->imgSrc }}"
                alt="{{ $item->name }}"
                class="recent-item-img border shadow-sm">
              @else
              <div class="recent-no-img border">
                <i class="fa fa-image"></i>
              </div>
              @endif
            </a>

            <!-- 景點名稱與資訊 -->
            <div class="recent-body">
              <div class="rt mb-1">
                <a href="/views/{{ $item->id }}" class="text-decoration-none text-dark fw-bold hover-gold">
                  {{ $item->name }}
                </a>
              </div>
              @if($item->city || $item->town)
              <div class="small text-muted" style="font-size: 12px;">
                <i class="fa fa-map-marker-alt me-1 text-danger"></i>{{ $item->city }}{{ $item->town }}
              </div>
              @endif
            </div>

          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push("script")
<!-- 引入 Fancybox 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
@push("script")
<!-- Fancybox 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<!-- Vue 3 -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Fancybox
    Fancybox.bind("[data-fancybox='gallery']", {
      Navigation: true,

      Thumbs: {
        type: "classic",
        autoStart: true,
      },

      Toolbar: {
        display: {
          left: ["infobar"],
          middle: [
            "zoomIn",
            "zoomOut",
            "toggle1to1",
            "rotateCCW",
            "rotateCW",
            "flipX",
            "flipY",
          ],
          right: ["slideshow", "thumbs", "close"],
        },
      },

      infinite: true,
      backdropClick: "close",
    });

    // Vue 3
    const App = {
      data() {
        return {
          isLiked: false,
          // 將 Laravel 的點讚數轉成整數
          likeCount: 0,
          viewsId: ''
        };
      },

      methods: {

        showLogin(error) {
          if (error.response.status == 401) {
            Swal.fire({
              title: "尚未登入?",
              text: "請登入或註冊後使用收藏功能",
              icon: "question"
            });
          }
        },
        async toggleFavorite() {
          const vm = this;
          let url = new URL(window.location.href);
          let id = url.pathname.split('/').pop();
          if (vm.isLiked) {
            // 呼叫刪除收藏 API
            console.log("取消收藏", id);
            try {
              let response = await axios.delete('/api/wishlist/delete', {
                data: {
                  viewsId: id
                }
              });
              console.log(response);
              vm.isLiked = false;
            } catch (error) {
              console.log(error.response);
              vm.showLogin(error);
            }

          } else {
            // 呼叫加入收藏 API
            console.log("加入收藏", id);
            try {
              let response = await axios.post('/api/wishlist/add', {
                viewsId: id
              });
              console.log(response);
              vm.isLiked = true;

            } catch (error) {
              console.log(error.response);
              vm.showLogin(error);
            }
          }
        },
        checkLiked() {
          const vm = this;
          let url = new URL(window.location.href);
          let id = url.pathname.split('/').pop();
          console.log(id);
          axios.get('/api/wishlist/checkLiked', {
              params: {
                viewsId: id
              }
            })
            .then(function(response) {
              console.log(response);
              if (response.data.status) {
                vm.isLiked = true;
              } else {
                vm.isLiked = false;
              }
            })
            .catch(function(error) {
              console.log(error);
              vm.isLiked = false;
            })
            .finally(function() {
              // always executed
            });
        },
        getLikeCount() {
          const vm = this;
          let url = new URL(window.location.href);
          let id = url.pathname.split('/').pop();
          axios.get('/api/wishlist/getLikes', {
              params: {
                viewsId: id
              }
            })
            .then(function(response) {
              console.log(response);
              if (response.data.status) {
                vm.likeCount = response.data.likes;
              }
            })
            .catch(function(error) {
              console.log(error);
            })
            .finally(function() {
              // always executed
            });

        }
      },
      mounted() {
        const vm = this;
        vm.checkLiked();
        vm.getLikeCount();
      }
    };

    Vue.createApp(App).mount("#app");
  });
</script>
@endpush