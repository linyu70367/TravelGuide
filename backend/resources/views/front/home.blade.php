@extends("front.layout")

@section("title")
首頁 - 台灣旅遊與美食指南
@endsection

@push("style")
<!-- 引入 Swiper 11 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('css/front/home.css') }}?v={{ time() }}">
@endpush

@section("content")
<!-- 1. Hero 區塊：風景照片輪播 & 視差滾動標題 -->
<section class="hero-section" id="heroSection">

  <!-- 視差滾動標題內容 -->
  <div class="hero-parallax-content" id="heroParallaxContent">
    <h1 class="hero-title">探索台灣 發現美麗新視界</h1>
    <p class="hero-subtitle">嚴選全台熱門景點、在地美食與秘境指南，展開您的下一趟難忘旅程</p>
    <a href="{{ url('/views') }}" class="hero-btn">
      <i class="fa fa-compass me-2"></i>探索熱門景點
    </a>
  </div>

  <!-- Swiper 背景輪播圖 -->
  <div class="swiper heroSwiper">
    <div class="swiper-wrapper">

      <!-- 預設背景圖 1 -->
      <div class="swiper-slide" style="background-image: url('{{ asset('images/front/detail-hero.jpg') }}');">
        <div class="hero-overlay"></div>
      </div>

      <!-- 預設背景圖 2 -->
      <div class="swiper-slide" style="background-image: url('{{ asset('images/front/hero-banner.jpg') }}');">
        <div class="hero-overlay"></div>
      </div>

      <!-- 動態載入景點圖片作為輪播 -->
      @if(isset($recentViews) && $recentViews->isNotEmpty())
      @foreach($recentViews->take(4) as $slide)
      @php
      $firstImg = $slide->imgs?->first()?->imgSrc;
      @endphp
      @if($firstImg)
      <div class="swiper-slide" style="background-image: url('{{ asset('images/views/' . $firstImg) }}');">
        <div class="hero-overlay"></div>
      </div>
      @endif
      @endforeach
      @endif

    </div>

    <!-- 輪播控制按鈕 -->
    <div class="swiper-button-next d-none d-md-flex"></div>
    <div class="swiper-button-prev d-none d-md-flex"></div>
    <div class="swiper-pagination"></div>
  </div>

</section>

<!-- 2. 熱門推薦景點區塊 -->
<section class="section py-4">
  <div class="container">

    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title mb-0"><i class="fa fa-fire text-danger me-2"></i>熱門推薦景點</h2>
      </div>
      <a href="{{ url('/views') }}" class="view-more-link text-decoration-none">
        檢視全部 <i class="fa fa-chevron-right ms-1" style="font-size: 12px;"></i>
      </a>
    </div>

    <div class="row g-4">
      @if(isset($recentViews) && $recentViews->isNotEmpty())
      @foreach($recentViews->take(6) as $view)
      @php
      $coverImg = $view->imgs?->first()?->imgSrc;
      @endphp
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card view-card h-100 shadow-sm border-0 rounded-3 overflow-hidden">

          <!-- 景點縮圖 -->
          <a href="{{ url('/views/' . $view->id) }}" class="card-img-wrap text-decoration-none">
            @if($coverImg)
            <img src="{{ asset('images/views/' . $coverImg) }}"
              class="card-img-top"
              alt="{{ $view->name }}"
              loading="lazy">
            @else
            <div class="no-img-box d-flex align-items-center justify-content-center">
              <i class="fa fa-image fa-2x text-muted"></i>
            </div>
            @endif
            <div class="img-overlay">
              <span class="btn btn-light rounded-pill px-4">
                <i class="fa fa-eye me-2"></i>查看
              </span>
            </div>
          </a>

          <!-- 景點內容 -->
          <div class="card-body d-flex flex-column">
            <div class="small text-muted mb-2">
              <span class="news-badge me-2">{{ $view->types?->typeName ?? '未分類' }}</span>
              @if($view->city || $view->town)
              <span><i class="fa fa-map-marker-alt text-danger me-1"></i>{{ $view->city }}{{ $view->town }}</span>
              @endif
            </div>

            <h5 class="card-title fw-bold">
              <a href="{{ url('/views/' . $view->id) }}" class="text-decoration-none text-dark hover-gold">
                {{ $view->name }}
              </a>
            </h5>

            <p class="card-text text-muted small flex-grow-1">
              {{ Str::limit(strip_tags((string)$view->content), 80, '...') }}
            </p>

            
          </div>

        </div>
      </div>
      @endforeach
      @endif
    </div>

  </div>
</section>

<!-- 3. 在地特色美食指南區塊（已對齊景點區塊排版） -->
<section class="section py-5 bg-light mt-5">
  <div class="container">

    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <h2 class="section-title mb-0">
          <i class="fa fa-utensils text-warning me-2"></i>
          在地特色美食指南
        </h2>
      </div>

      <a href="{{ url('/travelfood') }}" class="view-more-link text-decoration-none">
        檢視全部
        <i class="fa fa-chevron-right ms-1"></i>
      </a>
    </div>

    <div class="row g-4">

      @forelse($foods as $food)

      <div class="col-12 col-md-6 col-lg-4">

        <div class="card view-card h-100 shadow-sm border-0 rounded-3 overflow-hidden">

          <a href="/travelfood/{{ $food['id'] }}" class="card-img-wrap">

            @if(!empty($food['PicURL']))
            <img
              src="{{ $food['PicURL'] }}"
              class="card-img-top"
              alt="{{ $food['Name'] }}"
              loading="lazy">
            @else
            <div class="no-img-box d-flex align-items-center justify-content-center">
              <i class="fa fa-utensils fa-2x text-muted"></i>
            </div>
            @endif
            <div class="img-overlay">
              <span class="btn btn-light rounded-pill px-4">
                <i class="fa fa-eye me-2"></i>查看
              </span>
            </div>

          </a>

          <div class="card-body d-flex flex-column">

            <div class="small text-muted mb-2">
              <span class="news-badge me-2">在地美食</span>

              @if(!empty($food['City']) || !empty($food['Town']))
              <span>
                <i class="fa fa-map-marker-alt text-danger me-1"></i>
                {{ $food['City'] ?? '' }}
                {{ $food['Town'] ?? '' }}
              </span>
              @endif
            </div>

            <h5 class="card-title fw-bold">
              {{ $food['Name'] ?? '未知美食' }}
            </h5>

            <p class="card-text text-muted small flex-grow-1">
              {{ Str::limit(strip_tags($food['HostWords'] ?? $food['FoodFeature'] ?? ''),80) }}
            </p>
          </div>

        </div>

      </div>

      @empty

      <div class="col-12 text-center py-5">
        <i class="fa fa-info-circle me-2"></i>
        目前暫無美食資料
      </div>

      @endforelse

    </div>

  </div>
</section>
@endsection

@push("script")
<!-- 引入 Swiper 11 JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // 1. Swiper 輪播初始化
    const heroSwiper = new Swiper('.heroSwiper', {
      loop: true,
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      },
      speed: 1200,
      autoplay: {
        delay: 4500,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });

    // 2. 標題視差滾動效果（使用 requestAnimationFrame 確保滑順度）
    const parallaxContent = document.getElementById('heroParallaxContent');
    const heroSection = document.getElementById('heroSection');

    if (parallaxContent && heroSection) {
      let ticking = false;

      window.addEventListener('scroll', function() {
        if (!ticking) {
          window.requestAnimationFrame(function() {
            const scrollY = window.pageYOffset;
            const heroHeight = heroSection.offsetHeight;

            if (scrollY <= heroHeight) {
              const translateY = scrollY * 0.45;
              const opacity = Math.max(0, 1 - (scrollY / (heroHeight * 0.7)));

              parallaxContent.style.setProperty('--parallax-y', `${translateY}px`);
              parallaxContent.style.opacity = opacity;
            }
            ticking = false;
          });
          ticking = true;
        }
      });
    }
  });
</script>
@endpush