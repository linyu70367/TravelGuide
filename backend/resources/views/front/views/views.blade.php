@extends("front.layout")

@section("title")
旅遊景點指南
@endsection

@push("style")
<link rel="stylesheet" href="{{ asset('css/front/views.css') }}">

@endpush

@section("content")
<!-- 1. 滿版視覺 Hero Header -->
<div class="travel-hero text-center mb-4">
    <h1 class="title mb-2">旅遊景點指南</h1>
    <p class="mb-0 text-white-50">探索台灣在地風光、熱門景點與絕美好去處</p>
</div>

<div id="app" class="container my-4" v-cloak>



    <!-- 2. 篩選與控制面板 -->
    <div class="filter-card mb-4">
        <!-- 地區篩選按鈕列 -->
        <div class="d-flex align-items-center justify-content-start gap-2 my-2 flex-wrap">
            <span class="filter-label fw-bold text-dark me-2">地區：</span>
            <button
                type="button"
                class="btn filter-btn"
                :class="selectedRegion === 'all' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setRegion('all')">
                全部地區
            </button>
            <button
                v-for="region in ['北部', '中部', '南部', '東部']"
                :key="region"
                type="button"
                class="btn filter-btn"
                :class="selectedRegion === region ? 'btn-primary' : 'btn-outline-primary'"
                @click="setRegion(region)">
                @{{ region }}
            </button>
        </div>

        <hr class="my-3 border-light-subtle">

        <!-- 景點類型篩選按鈕列 -->
        <div class="d-flex align-items-center justify-content-start gap-2 my-2 flex-wrap">
            <span class="filter-label fw-bold text-dark me-2">類型：</span>
            <button
                type="button"
                class="btn filter-btn"
                :class="selectedType === 'all' ? 'btn-primary' : 'btn-outline-primary'"
                @click="setType('all')">
                全部類型
            </button>
            <button
                v-for="type in types"
                :key="type.id"
                type="button"
                class="btn filter-btn"
                :class="selectedType !== 'all' && Number(selectedType) === Number(type.id) ? 'btn-primary' : 'btn-outline-primary'"
                @click="setType(type.id)">
                @{{ type.typeName }}
            </button>
        </div>
    </div>

    <!-- 3. 工具列（每頁筆數與統計） -->
    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <div class="text-secondary small fw-bold">
            找到 <span class="text-primary fs-6">@{{ filteredFoods.length }}</span> 個符合條件的景點
        </div>
        <div class="d-flex align-items-center">
            <label for="dataPerPage" class="small text-secondary fw-bold me-2">每頁顯示</label>
            <select id="dataPerPage" v-model.number="pageSize" class="form-select form-select-sm" style="width: 90px;">
                <option :value="6">6</option>
                <option :value="9">9</option>
                <option :value="12">12</option>
                <option :value="24">24</option>
            </select>
        </div>
    </div>

    <!-- 4. 景點卡片圖文網格 (Grid Layout) -->
    <div class="row g-4">
        <!-- 景點動態卡片 -->
        <div v-for="(item, index) in pageData" :key="item.id || index" class="col-12 col-md-6 col-lg-4">
            <div class="view-card">

                <!-- 圖片封面 (6:4 比例，點擊開 Lightbox) -->
                <div class="view-card-img-wrapper" @click="openLightbox(item.imgs)">
                    <template v-if="item.imgs && item.imgs.length > 0 && item.imgs[0].imgSrc">
                        <img
                            :src="'/images/views/S/' + item.imgs[0].imgSrc"
                            :alt="item.name"
                            class="view-card-img"
                            title="點擊放大檢視相簿">
                    </template>
                    <template v-else>
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted small">
                            暫無圖片
                        </div>
                    </template>

                    <!-- 分類 Badge -->
                    <span class="view-card-badge">
                        @{{ getTypeName(item.typeId) }}
                    </span>
                </div>

                <!-- 卡片內文資訊 -->
                <div class="view-card-body">
                    <a :href="'/views/' + item.id" class="view-card-title text-truncate" :title="item.name">
                        @{{ item.name || '未提供名稱' }}
                    </a>

                    <div class="view-card-address">
                        <i class="fa fa-map-marker-alt text-danger me-1"></i>
                        <template v-if="item.city || item.town">
                            @{{ item.city || '' }} @{{ item.town || '' }}
                        </template>
                        @{{ item.address || '' }}
                    </div>

                    <div class="view-card-desc">
                        @{{ item.brief || item.content || '未提供景點簡介資訊。' }}
                    </div>

                    <!-- 卡片底部導航頁 -->
                    <div class="view-card-footer">
                        <span v-if="item.tel" class="small text-muted">
                            📞 @{{ item.tel }}
                        </span>
                        <span v-else class="small text-muted">📍 景點資訊</span>

                        <a :href="'/views/' + item.id" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            查看詳情 <i class="fa fa-chevron-right ms-1" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- 無資料時提示 -->
        <div v-if="pageData.length === 0" class="col-12 text-center py-5">
            <div class="p-5 bg-white rounded-3 border text-muted shadow-sm">
                <i class="fa fa-search fa-3x mb-3 text-secondary"></i>
                <h5 class="fw-bold">找不到符合條件的景點</h5>
                <p class="small text-secondary mb-0">嘗試切換地區或景點類型看看吧！</p>
            </div>
        </div>
    </div>

    <!-- 5. 頁碼 Pagination -->
    <nav v-if="totalPages > 1" class="d-flex justify-content-center mt-5">
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button type="button" class="page-link" @click="changePage(currentPage - 1)">上一頁</button>
            </li>
            <li
                v-for="page in totalPages"
                :key="page"
                class="page-item"
                :class="{ active: currentPage === page }">
                <button type="button" class="page-link" @click="changePage(page)">@{{ page }}</button>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button type="button" class="page-link" @click="changePage(currentPage + 1)">下一頁</button>
            </li>
        </ul>
    </nav>

    <!-- 6. Lightbox Modal 彈窗 -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true" ref="lightboxModalRef">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white border-0 shadow-lg">
                <div class="modal-header border-secondary p-2 px-3">
                    <h6 class="modal-title fst-italic">景點圖片展演</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-black rounded-bottom">
                    <div id="lightboxCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner">
                            <div
                                v-for="(img, idx) in activeImages"
                                :key="img.id || idx"
                                class="carousel-item"
                                :class="{ active: idx === 0 }">
                                <div class="d-flex justify-content-center align-items-center w-100" style="min-height: 50vh; max-height: 80vh; padding: 20px;">
                                    <img
                                        :src="'/images/views/' + img.imgSrc"
                                        class="img-fluid rounded shadow lightbox-img"
                                        :alt="'圖片 ' + (idx + 1)"
                                        style="object-fit: contain;">
                                </div>
                            </div>
                        </div>

                        <template v-if="activeImages.length > 1">
                            <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                                <span class="visually-hidden">上一張</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                                <span class="visually-hidden">下一張</span>
                            </button>
                            <div class="carousel-indicators">
                                <button
                                    v-for="(img, idx) in activeImages"
                                    :key="'ind-'+idx"
                                    type="button"
                                    data-bs-target="#lightboxCarousel"
                                    :data-bs-slide-to="idx"
                                    :class="{ active: idx === 0 }"
                                    aria-current="true"
                                    :aria-label="'Slide ' + (idx + 1)">
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- 腳本引入 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
    const viewsData = @json($views ?? []);
    const typesData = @json($viewstype ?? []);

    const defaultType = @json($selectedType ?? 'all');
    const defaultRegion = @json($selectedRegion ?? 'all');

    const {
        createApp,
        ref,
        computed,
        watch,
        onMounted
    } = Vue;

    createApp({
        setup() {
            const views = ref(viewsData);
            const types = ref(typesData);

            const selectedRegion = ref(defaultRegion);
            const selectedType = ref(defaultType !== 'all' ? Number(defaultType) : 'all');

            // 調整預設每頁顯示筆數為 9 (3x3 網格視覺最協調)
            const pageSize = ref(9);
            const currentPage = ref(1);

            const activeImages = ref([]);
            const lightboxModalRef = ref(null);
            let bsModal = null;

            onMounted(() => {
                if (typeof bootstrap !== 'undefined' && lightboxModalRef.value) {
                    bsModal = new bootstrap.Modal(lightboxModalRef.value);
                }
            });

            const regionMap = {
                '北部': ['臺北市', '台北市', '新北市', '基隆市', '宜蘭縣', '桃園市', '新竹市', '新竹縣'],
                '中部': ['苗栗縣', '臺中市', '台中市', '彰化縣', '南投縣', '雲林縣'],
                '南部': ['嘉義市', '嘉義縣', '臺南市', '台南市', '高雄市', '屏東縣', '澎湖縣'],
                '東部': ['花蓮縣', '臺東縣', '台東縣']
            };

            const filteredFoods = computed(() => {
                return views.value.filter(item => {
                    let matchRegion = true;
                    if (selectedRegion.value !== 'all') {
                        const cities = regionMap[selectedRegion.value] || [];
                        const fullAddress = ((item.city || '') + (item.town || '') + (item.address || ''));
                        matchRegion = cities.some(city => fullAddress.includes(city));
                    }

                    let matchType = true;
                    if (selectedType.value !== 'all') {
                        matchType = Number(item.typeId) === Number(selectedType.value);
                    }

                    return matchRegion && matchType;
                });
            });

            const getTypeName = (typeId) => {
                const target = types.value.find(t => Number(t.id) === Number(typeId));
                return target ? target.typeName : '未分類';
            };

            const totalPages = computed(() => {
                return Math.ceil(filteredFoods.value.length / pageSize.value) || 1;
            });

            const pageData = computed(() => {
                const start = (currentPage.value - 1) * pageSize.value;
                const end = start + pageSize.value;
                return filteredFoods.value.slice(start, end);
            });

            const updateUrlQuery = () => {
                const params = new URLSearchParams();
                if (selectedRegion.value !== 'all') {
                    params.set('region', selectedRegion.value);
                }
                if (selectedType.value !== 'all') {
                    params.set('type', selectedType.value);
                }
                const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.history.replaceState({}, '', newUrl);
            };

            const setRegion = (region) => {
                selectedRegion.value = region;
                currentPage.value = 1;
                updateUrlQuery();
            };

            const setType = (typeId) => {
                selectedType.value = typeId;
                currentPage.value = 1;
                updateUrlQuery();
            };

            const changePage = (page) => {
                if (page >= 1 && page <= totalPages.value) {
                    currentPage.value = page;
                }
            };

            const openLightbox = (imgs) => {
                if (imgs && imgs.length > 0) {
                    activeImages.value = imgs;
                    if (!bsModal && typeof bootstrap !== 'undefined' && lightboxModalRef.value) {
                        bsModal = new bootstrap.Modal(lightboxModalRef.value);
                    }
                    if (bsModal) {
                        bsModal.show();
                    }
                }
            };

            watch(pageSize, () => {
                currentPage.value = 1;
            });

            return {
                views,
                types,
                selectedRegion,
                selectedType,
                pageSize,
                currentPage,
                filteredFoods,
                totalPages,
                pageData,
                activeImages,
                lightboxModalRef,
                getTypeName,
                setRegion,
                setType,
                changePage,
                openLightbox
            };
        }
    }).mount('#app');
</script>
@endsection