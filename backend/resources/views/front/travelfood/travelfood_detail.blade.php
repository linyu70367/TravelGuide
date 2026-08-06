@extends('front.layout')

@php
    // 只取得網址中的 ID，不在 Blade 後端呼叫 API。
    $foodId = (int) (
        $id
        ?? request()->route('id')
        ?? request()->route('ID')
        ?? 0
    );
@endphp

@section('title')
    在地美食 - 台灣旅遊與美食指南
@endsection

@push('style')
    <link
        rel="stylesheet"
        href="{{ asset('css/front/views_detail.css') }}?v={{ time() }}"
    >

    {{-- Fancybox 5 CSS --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
    >

    <style>
        .food-loading {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .food-loading-spinner {
            width: 42px;
            height: 42px;
            margin-bottom: 16px;
            border: 4px solid #e5e7eb;
            border-top-color: var(--brand, #1b2e5e);
            border-radius: 50%;
            animation: food-loading-rotate 0.8s linear infinite;
        }

        @keyframes food-loading-rotate {
            to {
                transform: rotate(360deg);
            }
        }

        .food-error {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            text-align: center;
            color: #842029;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            border-radius: 8px;
        }

        .food-error i {
            margin-bottom: 15px;
            font-size: 42px;
        }

        #food-main-content {
            display: none;
        }

        .article-nav-prev,
        .article-nav-next {
            width: 48%;
        }

        .sidebar-empty {
            padding: 10px 0;
            font-size: 13px;
            color: #777;
        }
    </style>
@endpush

@section('content')

    {{-- Hero 頂部區塊 --}}
    <div class="page-hero">
        <div class="container">
            <h1 id="hero-food-name">在地美食</h1>

            <div class="bc">
                <a href="{{ url('/') }}">首頁</a>

                <i
                    class="fa fa-chevron-right"
                    style="font-size: 10px;"
                ></i>

                <a href="{{ url('/travelfood') }}">在地美食</a>

                <i
                    class="fa fa-chevron-right"
                    style="font-size: 10px;"
                ></i>

                <span>美食內容</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="layout-2col">

                {{-- 主要內容 --}}
                <main>
                    <article class="article-card">

                        {{-- AJAX 載入畫面 --}}
                        <div id="food-loading" class="food-loading">
                            <div class="food-loading-spinner"></div>
                            <div>美食資料載入中...</div>
                        </div>

                        {{-- AJAX 錯誤畫面 --}}
                        <div id="food-error" class="food-error" style="display: none;">
                            <i class="fa fa-exclamation-circle"></i>

                            <div id="food-error-message">
                                美食資料載入失敗
                            </div>

                            <button
                                type="button"
                                id="reload-food-button"
                                class="btn btn-outline-danger mt-3"
                            >
                                <i class="fa fa-redo me-1"></i>
                                重新載入
                            </button>
                        </div>

                        {{-- 美食主要資料 --}}
                        <div id="food-main-content">

                            {{-- 美食圖片 --}}
                            <div id="food-image-section" class="views-gallery mb-4">
                                <div class="main-cover-box mb-3">
                                    <a
                                        id="food-image-link"
                                        href="#"
                                        data-fancybox="gallery"
                                        data-caption=""
                                    >
                                        <img
                                            id="food-image"
                                            src=""
                                            class="article-cover rounded shadow-sm w-100"
                                            alt="在地美食"
                                            loading="lazy"
                                            style="max-height: 450px; object-fit: cover;"
                                        >
                                    </a>
                                </div>
                            </div>

                            {{-- 無圖片 --}}
                            <div
                                id="food-no-image"
                                class="article-cover d-flex align-items-center justify-content-center bg-light rounded mb-4"
                                style="display: none !important; height: 250px;"
                            >
                                <i class="fa fa-utensils fa-3x text-secondary"></i>
                            </div>

                            {{-- 文章內容 --}}
                            <div class="article-body">
                                <h2
                                    id="food-name"
                                    class="article-title fw-bold my-3"
                                    style="color: var(--brand);"
                                >
                                    在地美食
                                </h2>

                                {{-- 地址、電話、Email、網站 --}}
                                <div class="d-flex align-items-center flex-wrap gap-3 text-muted small mb-3">

                                    <div id="food-address-box" style="display: none;">
                                        <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                        <span id="food-address"></span>
                                    </div>

                                    <div id="food-tel-box" style="display: none;">
                                        <i class="fa fa-phone text-primary me-1"></i>

                                        <a
                                            id="food-tel"
                                            href="#"
                                            class="text-decoration-none"
                                        ></a>
                                    </div>

                                    <div id="food-email-box" style="display: none;">
                                        <i class="fa fa-envelope text-primary me-1"></i>

                                        <a
                                            id="food-email"
                                            href="#"
                                            class="text-decoration-none"
                                        ></a>
                                    </div>

                                    <div id="food-url-box" style="display: none;">
                                        <i class="fa fa-globe text-primary me-1"></i>

                                        <a
                                            id="food-url"
                                            href="#"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-decoration-none"
                                        >
                                            官方網站
                                        </a>
                                    </div>
                                </div>

                                {{-- 美食介紹 --}}
                                <div
                                    id="food-feature"
                                    class="article-content mt-3"
                                >
                                    目前暫無詳細介紹
                                </div>
                            </div>

                            {{-- 上一則、下一則 --}}
                            <nav
                                class="article-nav d-flex justify-content-between align-items-center pt-3 mt-4 border-top"
                                aria-label="美食文章導覽"
                            >
                                <div
                                    id="previous-food"
                                    class="article-nav-prev"
                                ></div>

                                <div
                                    id="next-food"
                                    class="article-nav-next text-end"
                                ></div>
                            </nav>
                        </div>
                    </article>

                    {{-- 返回按鈕 --}}
                    <a
                        href="{{ url('/travelfood') }}"
                        class="back-btn mt-3 mb-3 d-inline-block text-decoration-none"
                    >
                        <i class="fa fa-arrow-left me-1"></i>
                        返回列表
                    </a>
                </main>

                {{-- 側邊欄 --}}
                <aside>

                    {{-- 熱門美食 --}}
                    <div class="sidebar-card">
                        <div class="sidebar-title fw-bold mb-3">
                            <i class="fa fa-fire text-danger me-1"></i>
                            熱門美食
                        </div>

                        <div id="recent-food-list">
                            <div class="sidebar-empty">
                                美食資料載入中...
                            </div>
                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- 如果 front.layout 已經有載入 jQuery，可以刪除這一行 --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Fancybox 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script>
        $(function () {
            const foodId = @json($foodId);

            const foodApiUrl = @json(url('/api/travelfoods'));
            const foodPageUrl = @json(url('/travelfood'));

            /**
             * 避免將 API 資料直接插入 HTML 造成 XSS。
             */
            function escapeHtml(value) {
                return $('<div>')
                    .text(value === null || value === undefined ? '' : String(value))
                    .html();
            }

            /**
             * 將純文字換行轉成 <br>。
             */
            function formatMultilineText(value) {
                const text = value || '目前暫無詳細介紹';

                return escapeHtml(text).replace(/\r?\n/g, '<br>');
            }

            /**
             * 只允許 http 或 https 網址。
             */
            function getSafeHttpUrl(value) {
                if (!value) {
                    return '';
                }

                try {
                    const parsedUrl = new URL(value, window.location.origin);

                    if (
                        parsedUrl.protocol !== 'http:' &&
                        parsedUrl.protocol !== 'https:'
                    ) {
                        return '';
                    }

                    return parsedUrl.href;
                } catch (error) {
                    return '';
                }
            }

            /**
             * 相容 API 回傳格式
             */
            function getApiData(response) {
                if (
                    response &&
                    Object.prototype.hasOwnProperty.call(response, 'data')
                ) {
                    return response.data;
                }

                return response;
            }

            /**
             * 顯示載入畫面。
             */
            function showLoading() {
                $('#food-loading').show();
                $('#food-error').hide();
                $('#food-main-content').hide();
            }

            /**
             * 顯示詳細資料錯誤。
             */
            function showDetailError(message) {
                $('#food-loading').hide();
                $('#food-main-content').hide();

                $('#food-error-message').text(
                    message || '美食資料載入失敗'
                );

                $('#food-error').show();
            }

            /**
             * 顯示單筆詳細資料。
             */
            function renderFoodDetail(food) {
                if (!food || typeof food !== 'object') {
                    showDetailError('找不到指定的美食資料');
                    return;
                }

                const name = food.Name || '在地美食';

                document.title = name + ' - 台灣旅遊與美食指南';

                $('#hero-food-name').text(name);
                $('#food-name').text(name);

                /*
                 * 美食圖片
                 */
                const imageUrl = getSafeHttpUrl(food.PicURL);

                if (imageUrl) {
                    $('#food-image')
                        .attr('src', imageUrl)
                        .attr('alt', name);

                    $('#food-image-link')
                        .attr('href', imageUrl)
                        .attr('data-thumb', imageUrl)
                        .attr('data-caption', name);

                    $('#food-image-section').show();

                    $('#food-no-image')
                        .attr('style', 'display: none !important; height: 250px;');
                } else {
                    $('#food-image-section').hide();

                    $('#food-no-image')
                        .attr(
                            'style',
                            'display: flex !important; height: 250px;'
                        );
                }

                /*
                 * 地址
                 */
                if (food.Address) {
                    $('#food-address').text(food.Address);
                    $('#food-address-box').show();
                } else {
                    $('#food-address-box').hide();
                }

                /*
                 * 電話
                 */
                if (food.Tel) {
                    const telHref = String(food.Tel)
                        .replace(/[^0-9+]/g, '');

                    $('#food-tel')
                        .text(food.Tel)
                        .attr('href', 'tel:' + telHref);

                    $('#food-tel-box').show();
                } else {
                    $('#food-tel-box').hide();
                }

                /*
                 * Email
                 */
                if (food.Email) {
                    $('#food-email')
                        .text(food.Email)
                        .attr('href', 'mailto:' + food.Email);

                    $('#food-email-box').show();
                } else {
                    $('#food-email-box').hide();
                }

                /*
                 * 官方網站
                 */
                const officialUrl = getSafeHttpUrl(food.Url);

                if (officialUrl) {
                    $('#food-url').attr('href', officialUrl);
                    $('#food-url-box').show();
                } else {
                    $('#food-url-box').hide();
                }

                /*
                 * 美食介紹
                 */
                $('#food-feature').html(
                    formatMultilineText(food.FoodFeature)
                );

                $('#food-loading').hide();
                $('#food-error').hide();
                $('#food-main-content').show();

                /*
                 * 詳細資料載入完成後啟用 Fancybox。
                 */
                if (
                    imageUrl &&
                    typeof Fancybox !== 'undefined'
                ) {
                    Fancybox.bind("[data-fancybox='gallery']", {
                        Navigation: true,

                        Thumbs: {
                            type: 'classic',
                            autoStart: true,
                        },

                        Toolbar: {
                            display: {
                                left: ['infobar'],
                                middle: [
                                    'zoomIn',
                                    'zoomOut',
                                    'toggle1to1',
                                    'rotateCCW',
                                    'rotateCW',
                                    'flipX',
                                    'flipY',
                                ],
                                right: [
                                    'slideshow',
                                    'thumbs',
                                    'close',
                                ],
                            },
                        },

                        infinite: false,
                        backdropClick: 'close',
                    });
                }
            }

            /**
             * 顯示上一則、下一則。
             */
            function renderFoodNavigation(foodList) {
                const currentIndex = foodList.findIndex(function (food) {
                    return Number(food.id) === Number(foodId);
                });

                $('#previous-food').empty();
                $('#next-food').empty();

                if (currentIndex === -1) {
                    return;
                }

                const previousFood = currentIndex > 0
                    ? foodList[currentIndex - 1]
                    : null;

                const nextFood = currentIndex < foodList.length - 1
                    ? foodList[currentIndex + 1]
                    : null;

                if (
                    previousFood &&
                    previousFood.id !== undefined
                ) {
                    const previousUrl =
                        foodPageUrl + '/' +
                        encodeURIComponent(previousFood.id);

                    const previousName =
                        escapeHtml(previousFood.Name || '上一則');

                    $('#previous-food').html(
                        '<a href="' + previousUrl + '"' +
                        ' class="btn-nav-prev text-decoration-none">' +
                            '<i class="fa fa-chevron-left me-1"></i>' +
                            '上一則：' + previousName +
                        '</a>'
                    );
                }

                if (
                    nextFood &&
                    nextFood.id !== undefined
                ) {
                    const nextUrl =
                        foodPageUrl + '/' +
                        encodeURIComponent(nextFood.id);

                    const nextName =
                        escapeHtml(nextFood.Name || '下一則');

                    $('#next-food').html(
                        '<a href="' + nextUrl + '"' +
                        ' class="btn-nav-next text-decoration-none">' +
                            '下一則：' + nextName +
                            '<i class="fa fa-chevron-right ms-1"></i>' +
                        '</a>'
                    );
                }
            }

            /**
             * 顯示側邊欄美食。
             */
            function renderRecentFoods(foodList) {
                const recentFoods = foodList
                    .filter(function (food) {
                        return (
                            food &&
                            food.id !== undefined &&
                            Number(food.id) !== Number(foodId)
                        );
                    })
                    .slice(0, 5);

                const $recentList = $('#recent-food-list');
                $recentList.empty();

                if (recentFoods.length === 0) {
                    $recentList.html(
                        '<div class="sidebar-empty">' +
                            '暫無其他美食資料' +
                        '</div>'
                    );

                    return;
                }

                recentFoods.forEach(function (food) {
                    const name = food.Name || '未命名美食';

                    const detailUrl =
                        foodPageUrl + '/' +
                        encodeURIComponent(food.id);

                    const imageUrl = getSafeHttpUrl(food.PicURL);

                    const $item = $('<div>', {
                        class: 'recent-item d-flex align-items-center mb-3',
                    });

                    const $imageLink = $('<a>', {
                        href: detailUrl,
                        class: 'me-3 text-decoration-none',
                    });

                    if (imageUrl) {
                        const $image = $('<img>', {
                            src: imageUrl,
                            alt: name,
                            class: 'recent-item-img border shadow-sm',
                            loading: 'lazy',
                        }).css({
                            width: '60px',
                            height: '60px',
                            objectFit: 'cover',
                        });

                        $imageLink.append($image);
                    } else {
                        const $noImage = $('<div>', {
                            class: 'recent-no-img border d-flex align-items-center justify-content-center bg-light',
                        }).css({
                            width: '60px',
                            height: '60px',
                        });

                        $noImage.append(
                            $('<i>', {
                                class: 'fa fa-image text-secondary',
                            })
                        );

                        $imageLink.append($noImage);
                    }

                    const $body = $('<div>', {
                        class: 'recent-body',
                    });

                    const $title = $('<div>', {
                        class: 'rt mb-1',
                    });

                    const $titleLink = $('<a>', {
                        href: detailUrl,
                        class: 'text-decoration-none text-dark fw-bold hover-gold',
                        text: name,
                    });

                    $title.append($titleLink);
                    $body.append($title);

                    $item.append($imageLink);
                    $item.append($body);

                    $recentList.append($item);
                });
            }

            /**
             * 呼叫單筆詳細 API。
             *
             * GET /api/travelfoods/{id}
             */
            function loadFoodDetail() {
                if (!foodId || Number(foodId) <= 0) {
                    showDetailError('美食 ID 不正確');
                    return;
                }

                showLoading();

                $.ajax({
                    url: foodApiUrl + '/' + encodeURIComponent(foodId),
                    method: 'GET',
                    dataType: 'json',
                    timeout: 10000,

                    success: function (response) {
                        const food = getApiData(response);

                        renderFoodDetail(food);
                    },

                    error: function (xhr, status) {
                        let message = '美食資料載入失敗';

                        if (xhr.status === 404) {
                            message = '找不到指定的美食資料';
                        } else if (xhr.status === 0) {
                            message = '無法連線至美食 API';
                        } else if (status === 'timeout') {
                            message = '美食 API 回應逾時';
                        } else if (xhr.status >= 500) {
                            message = '伺服器暫時無法取得美食資料';
                        }

                        showDetailError(message);
                    },
                });
            }

            /**
             * 呼叫全部美食 API（用於導覽與熱門美食）。
             *
             * GET /api/travelfoods
             */
            function loadFoodList() {
                $.ajax({
                    url: foodApiUrl,
                    method: 'GET',
                    dataType: 'json',
                    timeout: 10000,

                    success: function (response) {
                        const responseData = getApiData(response);

                        const foodList = Array.isArray(responseData)
                            ? responseData
                            : [];

                        renderFoodNavigation(foodList);
                        renderRecentFoods(foodList);
                    },

                    error: function () {
                        $('#recent-food-list').html(
                            '<div class="sidebar-empty">' +
                                '其他美食載入失敗' +
                            '</div>'
                        );

                        $('#previous-food').empty();
                        $('#next-food').empty();
                    },
                });
            }

            /**
             * 重新載入按鈕。
             */
            $('#reload-food-button').on('click', function () {
                loadFoodDetail();
                loadFoodList();
            });

            /*
             * 頁面第一次載入。
             */
            loadFoodDetail();
            loadFoodList();
        });
    </script>
@endpush