@extends("front.layout")
@section("title", "xxx系統")
@push("style")
<link rel="stylesheet" href="/css/front/index.css">
@endpush
@section("content")
<main id="content-wrap">

    {{-- ======================================
         SECTION 1：Hero 輪播
    ======================================= --}}
    <div class="hero" role="region" aria-label="首頁輪播">
        {{-- Hero 前進/後退 --}}
        <button class="hero-prev" onclick="heroMove(-1)" aria-label="上一張">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button class="hero-next" onclick="heroMove(1)" aria-label="下一張">
            <i class="fa fa-chevron-right"></i>
        </button>

        <div class="hero-slides" id="heroSlides">

            {{-- Slide 1 --}}
            {{-- Laravel: 替換背景：<img class="hero-bg" src="{{ asset('images/hero-1.jpg') }}" alt=""> --}}
            <div class="hero-slide">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <div class="hero-eyebrow">全新系列 2025</div>
                    <h1 class="hero-title">卓越品質<br>成就每一刻</h1>
                    <p class="hero-desc">嚴選頂級原料，結合精湛工藝，為您帶來無與倫比的使用體驗。</p>
                    <div class="hero-actions">
                        <a href="products-list.html" class="btn-primary">
                            <i class="fa fa-shopping-bag"></i> 立即選購
                        </a>
                        <a href="about.html" class="btn-outline">了解更多</a>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="hero-slide">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <div class="hero-eyebrow">限時優惠</div>
                    <h1 class="hero-title">夏季特惠<br>全館最高 <span style="color:var(--gold)">8 折</span></h1>
                    <p class="hero-desc">活動期間限定，多項人氣商品同步特價，把握機會立即搶購。</p>
                    <div class="hero-actions">
                        <a href="products-list.html" class="btn-primary">
                            <i class="fa fa-tags"></i> 查看優惠商品
                        </a>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="hero-slide">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <div class="hero-eyebrow">會員獨享</div>
                    <h1 class="hero-title">加入會員<br>享更多專屬好禮</h1>
                    <p class="hero-desc">立即免費加入，獲得首購折扣、積點回饋與會員專屬活動通知。</p>
                    <div class="hero-actions">
                        <a href="register.html" class="btn-primary">
                            <i class="fa fa-user-plus"></i> 免費加入
                        </a>
                        <a href="login.html" class="btn-outline">已是會員？登入</a>
                    </div>
                </div>
            </div>

        </div>{{-- /hero-slides --}}

        {{-- Dots --}}
        <div class="hero-controls" id="heroDots">
            <button class="hero-dot active" onclick="heroGo(0)"></button>
            <button class="hero-dot" onclick="heroGo(1)"></button>
            <button class="hero-dot" onclick="heroGo(2)"></button>
        </div>
    </div>{{-- /hero --}}


    {{-- ======================================
         SECTION 2：品牌數字
    ======================================= --}}
    <div class="stats-band">
        <div class="container">
            {{-- Laravel: 數字可由後端 $stats 傳入 --}}
            <div class="stats-grid">
                <div class="stat-item reveal">
                    <div class="stat-num" data-target="12">0<sup>+</sup></div>
                    <div class="stat-label">品牌成立年數</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-num" data-target="280">0<sup>+</sup></div>
                    <div class="stat-label">產品品項</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-num" data-target="50000">0<sup>+</sup></div>
                    <div class="stat-label">累計服務會員</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-num" data-target="98">0<sup>%</sup></div>
                    <div class="stat-label">顧客好評率</div>
                </div>
            </div>
        </div>
    </div>


    {{-- ======================================
         SECTION 3：品牌特色
    ======================================= --}}
    <section class="section section-alt">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-eyebrow">Why Us</div>
                <h2 class="section-title">我們的<span>核心優勢</span></h2>
                <div class="gold-line"></div>
                <p class="section-desc">從產品開發到售後服務，每一個環節都傾注我們對品質的執著。</p>
            </div>
            {{-- Laravel: @foreach($features as $f) --}}
            <div class="features-grid">
                <div class="feature-card reveal">
                    <div class="feature-icon"><i class="fa fa-medal"></i></div>
                    <div class="feature-title">嚴格品質把關</div>
                    <p class="feature-desc">每件產品出廠前均通過多道檢測程序，確保每一次購買都令您滿意。</p>
                </div>
                <div class="feature-card reveal" style="transition-delay:.1s">
                    <div class="feature-icon"><i class="fa fa-truck"></i></div>
                    <div class="feature-title">快速安全配送</div>
                    <p class="feature-desc">與台灣主要物流合作，訂單成立後 24 小時內出貨，全程追蹤貨態。</p>
                </div>
                <div class="feature-card reveal" style="transition-delay:.2s">
                    <div class="feature-icon"><i class="fa fa-headset"></i></div>
                    <div class="feature-title">專業客服支援</div>
                    <p class="feature-desc">週一至週六 09:00–18:00 專人服務，LINE 官方帳號隨時為您解答。</p>
                </div>
            </div>
            {{-- Laravel: @endforeach --}}
        </div>
    </section>


    {{-- ======================================
         SECTION 4：精選產品
    ======================================= --}}
    <section class="section">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-eyebrow">Featured Products</div>
                <h2 class="section-title">精選<span>熱銷商品</span></h2>
                <div class="gold-line"></div>
            </div>

            {{-- Laravel: @foreach($featuredProducts as $p) --}}
            <div class="products-grid">
                {{-- Product 1 --}}
                <div class="product-card reveal">
                    <div class="product-img">
                        {{-- Laravel: <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}"> --}}
                        <div class="product-img-placeholder"><i class="fa fa-cube"></i></div>
                        <span class="product-badge-new">NEW</span>
                    </div>
                    <div class="product-info">
                        <div class="product-cat">系列一</div>
                        <div class="product-name">精品系列旗艦款 A1</div>
                        <div class="product-price">
                            <span class="price-now">NT$ 2,980</span>
                            <span class="price-was">NT$ 3,500</span>
                        </div>
                    </div>
                    <button class="btn-cart" onclick="addToCart(this)">
                        <i class="fa fa-cart-plus"></i> 加入購物車
                    </button>
                </div>

                {{-- Product 2 --}}
                <div class="product-card reveal" style="transition-delay:.08s">
                    <div class="product-img">
                        <div class="product-img-placeholder"><i class="fa fa-cube"></i></div>
                    </div>
                    <div class="product-info">
                        <div class="product-cat">系列二</div>
                        <div class="product-name">經典系列標準款 B2</div>
                        <div class="product-price">
                            <span class="price-now">NT$ 1,680</span>
                        </div>
                    </div>
                    <button class="btn-cart" onclick="addToCart(this)">
                        <i class="fa fa-cart-plus"></i> 加入購物車
                    </button>
                </div>

                {{-- Product 3 --}}
                <div class="product-card reveal" style="transition-delay:.16s">
                    <div class="product-img">
                        <div class="product-img-placeholder"><i class="fa fa-cube"></i></div>
                        <span class="product-badge-new" style="background:var(--danger);color:#fff">HOT</span>
                    </div>
                    <div class="product-info">
                        <div class="product-cat">系列三</div>
                        <div class="product-name">限量聯名款特別版 C1</div>
                        <div class="product-price">
                            <span class="price-now">NT$ 4,200</span>
                            <span class="price-was">NT$ 5,000</span>
                        </div>
                    </div>
                    <button class="btn-cart" onclick="addToCart(this)">
                        <i class="fa fa-cart-plus"></i> 加入購物車
                    </button>
                </div>

                {{-- Product 4 --}}
                <div class="product-card reveal" style="transition-delay:.24s">
                    <div class="product-img">
                        <div class="product-img-placeholder"><i class="fa fa-cube"></i></div>
                    </div>
                    <div class="product-info">
                        <div class="product-cat">系列一</div>
                        <div class="product-name">精品系列進階款 A2</div>
                        <div class="product-price">
                            <span class="price-now">NT$ 3,480</span>
                            <span class="price-was">NT$ 3,900</span>
                        </div>
                    </div>
                    <button class="btn-cart" onclick="addToCart(this)">
                        <i class="fa fa-cart-plus"></i> 加入購物車
                    </button>
                </div>
            </div>
            {{-- Laravel: @endforeach --}}

            <div class="section-footer reveal">
                <a href="products-list.html" class="btn-more">
                    查看全部商品 <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>


    {{-- ======================================
         SECTION 5：最新消息
    ======================================= --}}
    <section class="section section-alt">
        <div class="container">
            <div class="section-header reveal">
                <div class="section-eyebrow">Latest News</div>
                <h2 class="section-title">最新<span>消息</span></h2>
                <div class="gold-line"></div>
            </div>

            {{-- Laravel: @foreach($latestNews->take(3) as $n) --}}
            <div class="news-grid">
                {{-- News 1 --}}
                <a href="news-detail.html" class="news-card reveal">
                    <div class="news-cover">
                        {{-- Laravel: <img src="{{ asset('storage/'.$n->cover) }}" alt="{{ $n->title }}"> --}}
                        <i class="fa fa-newspaper"></i>
                    </div>
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-date">2025-06-01</span>
                            <span class="news-tag">公司公告</span>
                        </div>
                        <div class="news-title">2025 年下半年新品發布計畫正式公告</div>
                        <div class="news-excerpt">全新系列將於第三季陸續上市，涵蓋精品旗艦款與入門款，歡迎各位顧客持續關注。</div>
                        <span class="news-read-more">閱讀更多 <i class="fa fa-arrow-right" style="font-size:11px"></i></span>
                    </div>
                </a>

                {{-- News 2 --}}
                <a href="news-detail.html" class="news-card reveal" style="transition-delay:.1s">
                    <div class="news-cover"><i class="fa fa-newspaper"></i></div>
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-date">2025-05-20</span>
                            <span class="news-tag">活動資訊</span>
                        </div>
                        <div class="news-title">夏季會員回饋活動開跑，限時消費享雙倍積點</div>
                        <div class="news-excerpt">即日起至 6 月 30 日，會員消費每滿 NT$1,000 即享雙倍點數回饋，點數可折抵下次購物。</div>
                        <span class="news-read-more">閱讀更多 <i class="fa fa-arrow-right" style="font-size:11px"></i></span>
                    </div>
                </a>

                {{-- News 3 --}}
                <a href="news-detail.html" class="news-card reveal" style="transition-delay:.2s">
                    <div class="news-cover"><i class="fa fa-newspaper"></i></div>
                    <div class="news-body">
                        <div class="news-meta">
                            <span class="news-date">2025-05-10</span>
                            <span class="news-tag">產品動態</span>
                        </div>
                        <div class="news-title">C 系列限量聯名款正式開放預購</div>
                        <div class="news-excerpt">與知名設計師跨界合作的 C1 聯名款現正接受預購，數量有限，先搶先贏。</div>
                        <span class="news-read-more">閱讀更多 <i class="fa fa-arrow-right" style="font-size:11px"></i></span>
                    </div>
                </a>
            </div>
            {{-- Laravel: @endforeach --}}

            <div class="section-footer reveal">
                <a href="news-list.html" class="btn-more">
                    查看全部消息 <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>


    {{-- ======================================
         SECTION 6：CTA 行動呼籲
    ======================================= --}}
    <div class="cta-band">
        <div class="container">
            <div class="reveal">
                <div class="cta-eyebrow">加入我們</div>
                <h2 class="cta-title">立即加入會員<br>享受更多專屬優惠</h2>
                <p class="cta-desc">免費注冊，首購享 9 折優惠，並獲得積點回饋與不定期會員專屬活動。</p>
                <div class="cta-actions">
                    <a href="register.html" class="btn-primary">
                        <i class="fa fa-user-plus"></i> 立即免費加入
                    </a>
                    <a href="products-list.html" class="btn-outline">
                        <i class="fa fa-box-open"></i> 瀏覽商品
                    </a>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection