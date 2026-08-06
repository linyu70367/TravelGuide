<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>搜索彰化 | 探索彰化之美</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700;900&family=Oswald:wght@500;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/home.css">

</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
    <div class="container">
      <a class="navbar-brand navbar-brand-cn" href="#">🌾 搜索彰化</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navMenu">
        <ul class="navbar-nav gap-lg-3 align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link" href="#">首頁</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">關於彰化</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">景點介紹</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">聯絡我們</a>
          </li>

          <!-- 登入按鈕 -->
          <li class="nav-item ms-lg-3">
            <a href="/login" class="btn btn-login">
              登入
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <header class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <h1 class="mb-3">BUILD YOUR<br>ELEGANT DREAM<br>HOME INTERIOR</h1>
          <p class="mb-4">走進彰化的田園與人文風景，感受純樸農村與傳統工藝交織而成的獨特生活美學。</p>
          <a href="#features" class="btn btn-leaf">探索更多 →</a>
        </div>
        <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end mt-4 mt-lg-0">
          <div class="hero-circle">
            <img src="https://images.unsplash.com/photo-1533619043018-8f5fcecdedc5?auto=format&fit=crop&w=500&q=80"
              alt="彰化燈會活動">
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- WAVE INTO FEATURE SECTION -->
  <div class="wave-divider" style="background:#fff;">
    <svg viewBox="0 0 1200 60" preserveAspectRatio="none">
      <path d="M0,30 C300,60 900,0 1200,30 L1200,60 L0,60 Z" fill="#cdeaf7"></path>
    </svg>
  </div>

  <!-- FEATURE SECTION -->
  <section class="feature-section" id="features">
    <div class="container">

      <!-- Row 1 -->
      <div class="row feature-row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
          <h3 class="feature-title">八卦山天空步道</h3>
          <p class="feature-text">漫步於八卦山天空步道，居高臨下俯瞰彰化平原，以獨特的鏤空棧道設計，讓您在半空中盡覽群山綠意與遠方風光。</p>
          <a href="#" class="read-more">繼續閱讀更多 →</a>
        </div>
        <div class="col-md-6">
          <div class="hexagon">
            <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=700&q=80"
              alt="八卦山天空步道">
          </div>
        </div>
      </div>

      <div class="wave-divider"><svg viewBox="0 0 1200 40" preserveAspectRatio="none">
          <path d="M0,20 C300,0 900,40 1200,20" fill="none" stroke="#2f6fb0" stroke-width="2"></path>
        </svg></div>

      <!-- Row 2 -->
      <div class="row feature-row align-items-center flex-md-row-reverse">
        <div class="col-md-6 mb-4 mb-md-0">
          <h3 class="feature-title">鹿港龍山寺</h3>
          <p class="feature-text">走訪歷史悠久的鹿港龍山寺，欣賞精雕細琢的木構建築與匠師工藝，感受這座百年古蹟深厚的人文底蘊。</p>
          <a href="#" class="read-more">繼續閱讀更多 →</a>
        </div>
        <div class="col-md-6">
          <div class="hexagon">
            <img src="https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=700&q=80"
              alt="鹿港龍山寺">
          </div>
        </div>
      </div>

      <div class="wave-divider"><svg viewBox="0 0 1200 40" preserveAspectRatio="none">
          <path d="M0,20 C300,40 900,0 1200,20" fill="none" stroke="#2f6fb0" stroke-width="2"></path>
        </svg></div>

      <!-- Row 3 -->
      <div class="row feature-row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
          <h3 class="feature-title">芳苑海牛車</h3>
          <p class="feature-text">體驗全台獨有的海牛採蚵文化，搭乘牛車緩緩駛入潮間帶，親近彰化沿海豐富的生態與純樸的漁村風情。</p>
          <a href="#" class="read-more">繼續閱讀更多 →</a>
        </div>
        <div class="col-md-6">
          <div class="hexagon">
            <img src="https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=700&q=80"
              alt="芳苑海牛車">
          </div>
        </div>
      </div>

      <a href="#" class="view-more-link">more 了解更多</a>
    </div>
  </section>

  <!-- ECOLOGY SECTION -->
  <section class="eco-section">
    <div class="container">
      <h3 class="eco-title">彰化生態美</h3>
      <div class="row justify-content-center g-3">
        <div class="col-6 col-md-4">
          <div class="eco-img">
            <img src="https://images.unsplash.com/photo-1444464666168-49d633b86797?auto=format&fit=crop&w=500&q=80"
              alt="彰化候鳥生態">
          </div>
        </div>
        <div class="col-6 col-md-4">
          <div class="eco-img">
            <img src="https://images.unsplash.com/photo-1522383225653-ed111181a951?auto=format&fit=crop&w=500&q=80"
              alt="彰化花田景觀">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SECONDARY BANNER -->
  <div class="banner-2">
    <div class="brand-tag">彰化觀光局</div>
    <div class="dots">
      <span class="active"></span><span></span><span></span><span></span><span></span>
    </div>
  </div>

  <!-- NEWS / EVENTS -->
  <section class="news-section">
    <div class="container">
      <h3 class="news-title">📣 活動快訊</h3>
      <div class="news-carousel-wrap">
        <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=800&q=80"
                class="d-block w-100" alt="活動一">
            </div>
            <div class="carousel-item">
              <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80"
                class="d-block w-100" alt="活動二">
            </div>
            <div class="carousel-item">
              <img src="https://images.unsplash.com/photo-1508973379184-7517410fb0bc?auto=format&fit=crop&w=800&q=80"
                class="d-block w-100" alt="活動三">
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
        <p class="news-caption">最新活動與節慶消息，掌握彰化第一手資訊</p>
      </div>
    </div>
  </section>

  <!-- PARTNER LOGOS -->
  <section class="partners">
    <div class="container d-flex flex-wrap justify-content-center gap-5 align-items-center">
      <img src="https://dummyimage.com/120x40/cccccc/666666.png&text=PARTNER" alt="合作單位1">
      <img src="https://dummyimage.com/120x40/cccccc/666666.png&text=PARTNER" alt="合作單位2">
      <img src="https://dummyimage.com/120x40/cccccc/666666.png&text=PARTNER" alt="合作單位3">
      <img src="https://dummyimage.com/120x40/cccccc/666666.png&text=PARTNER" alt="合作單位4">
    </div>
  </section>

  <!-- CTA BAND -->
  <div class="cta-band">
    <h2>LET'S EXPLORE YOUR OWN CHANGHUA JOURNEY NOW</h2>
    <a href="#" class="btn btn-cta">CONTACT US</a>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <h6>Information</h6>
          <p>搜索彰化致力於推廣在地深度旅遊，帶您走訪山海田園，發掘彰化最真實的生活風景與文化底蘊。</p>
        </div>
        <div class="col-md-4">
          <h6>Navigation</h6>
          <ul>
            <li><a href="#">首頁</a></li>
            <li><a href="#">關於我們</a></li>
            <li><a href="#">景點介紹</a></li>
            <li><a href="#">聯絡我們</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h6>Contact Us</h6>
          <p>info@changhuaexplore.tw</p>
          <div class="social mb-3">
            <a href="#">f</a><a href="#">i</a><a href="#">y</a>
          </div>
          <div class="input-group">
            <input type="email" class="form-control" placeholder="訂閱電子報">
            <button class="btn btn-leaf" type="button">SUBSCRIBE</button>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        &copy; 2026 搜索彰化. All Rights Reserved. | Privacy Policy | Terms of Use
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    window.addEventListener("scroll", function() {
      const navbar = document.querySelector(".navbar-custom");

      if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
  </script>
</body>

</html>