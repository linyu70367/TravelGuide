<!-- ===== Topbar ===== -->
<div id="topbar" class="d-top">
  <div class="container-fluid d-flex justify-content-end align-items-center flex-wrap">
    @auth
    <a href="/member/home">
      <i class="fa fa-user-circle"></i>
      <span>會員中心</span>
    </a>

    <span class="sep">|</span>

    <a href="#" id="logout_btn"
      onclick="return confirm('確定要登出？')">
      <i class="fa fa-sign-out-alt"></i>
      <span>登出</span>
    </a>
    @else
    <span class="sep">|</span>

    <a href="/member/register">
      <i class="fa fa-user-plus"></i>
      <span>加入會員</span>
    </a>

    <span class="sep">|</span>

    <a href="/member/login">
      <i class="fa fa-sign-in-alt"></i>
      <span>登入</span>
    </a>
    @endauth
  </div>
</div>

<!-- ===== Navbar ===== -->
<nav class="navbar navbar-expand-lg d-nav">
  <div class="container-fluid">

    <!-- Logo -->
    <a href="/" class="lb">
      Logo
    </a>

    <!-- 品牌名稱 -->
    <a href="/" class="navbar-brand brand">
      島嶼漫遊
    </a>

    <!-- 手機版漢堡按鈕 -->
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#mainNavbar"
      aria-controls="mainNavbar"
      aria-expanded="false"
      aria-label="開啟導覽選單">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- 可收合內容 -->
    <div class="collapse navbar-collapse" id="mainNavbar">
      <div class="navbar-nav links">
        <a
          href="/views"
          class="nav-link {{ Request::is('views*') ? 'active' : '' }}">
          景點探索
        </a>

        <a
          href="/travelfood"
          class="nav-link {{ Request::is('travelfood*') ? 'active' : '' }}">
          地方美食
        </a>

        <a
          href="#"
          class="nav-link {{ Request::is('events*') ? 'active' : '' }}">
          旅遊靈感
        </a>

        <a
          href="/about"
          class="nav-link {{ Request::is('about*') ? 'active' : '' }}">
          認識台灣
        </a>

        @if (!empty(session()->get("memberId")))
        <a
          href="/member/home"
          class="nav-link {{ Request::is('member*') ? 'active' : '' }}">
          會員中心
        </a>
        @endif
      </div>

      <a href="/cart" class="cart">
        <i class="fa fa-shopping-cart"></i>
        <span>收藏清單</span>
        <span class="cart-badge" id="cart">3</span>
      </a>
    </div>
  </div>
</nav>