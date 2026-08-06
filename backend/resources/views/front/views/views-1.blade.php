@extends("front.layout")

@section("title")
旅遊景點指南
@endsection

@push("style")
<link rel="stylesheet" href="{{ asset('css/front/travelfood.css') }}">
@endpush

@section("content")
<div id="app" class="container my-4" v-cloak>
    <div class="display-3 text-center fw-bold mb-4">旅遊景點指南</div>

    <!-- 1. 地區篩選按鈕列 -->
    <div class="d-flex align-items-center justify-content-start gap-2 my-2 flex-wrap">
        <span class="filter-label fw-bold">地區：</span>
        <button
            type="button"
            class="btn"
            :class="selectedRegion === 'all' ? 'btn-primary' : 'btn-outline-primary'"
            @click="setRegion('all')">
            全部地區
        </button>
        <button
            v-for="region in ['北部', '中部', '南部', '東部']"
            :key="region"
            type="button"
            class="btn"
            :class="selectedRegion === region ? 'btn-primary' : 'btn-outline-primary'"
            @click="setRegion(region)">
            @{{ region }}
        </button>
    </div>

    <!-- 2. 景點類型篩選按鈕列 -->
    <div class="d-flex align-items-center justify-content-start gap-2 my-2 flex-wrap">
        <span class="filter-label fw-bold">類型：</span>
        <button
            type="button"
            class="btn"
            :class="selectedType === 'all' ? 'btn-primary' : 'btn-outline-primary'"
            @click="setType('all')">
            全部類型
        </button>
        <button
            v-for="type in types"
            :key="type.id"
            type="button"
            class="btn"
            :class="selectedType === type.id ? 'btn-primary' : 'btn-outline-primary'"
            @click="setType(type.id)">
            @{{ type.typeName }}
        </button>
    </div>

    <!-- 每頁顯示筆數設定 -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
        <div class="d-flex align-items-center">
            <label for="dataPerPage" class="fw-bold me-2">每頁顯示</label>
            <select id="dataPerPage" v-model.number="pageSize" class="form-select form-select-sm me-2" style="width: 100px;">
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="50">50</option>
            </select>
            <span>筆 (共 @{{ filteredFoods.length }} 筆資料)</span>
        </div>
    </div>

    <!-- 表格區域 (新增切版規格：編號 / 名稱 / 圖片 / 地址 / 簡介) -->
    <div class="table-responsive">
        <table class="table mt-3 table-bordered align-middle text-center table-rwd">
            <thead class="table-dark">
                <tr>
                    <th style="width: 8%;">編號</th>
                    <th style="width: 20%;">名稱</th>
                    <th style="width: 15%;">圖片</th>
                    <th style="width: 22%;">地址</th>
                    <th style="width: 35%;">簡介</th>
                </tr>
            </thead>

            <tbody>
                <!-- Vue 動態渲染列表 -->
                <tr v-for="(item, index) in pageData" :key="item.id || index">
                    <!-- 1. 編號 -->
                    <td data-th="編號">
                        <div class="td-content fw-bold text-brand">
                            @{{ (currentPage - 1) * pageSize + index + 1 }}
                        </div>
                    </td>

                    <!-- 2. 名稱 (點擊跳轉) -->
                    <td data-th="名稱">
                        <div class="td-content">
                            <a :href="'/views/' + item.id" class="view-title-link">
                                @{{ item.name || '未提供名稱' }}
                            </a>
                            <div class="mt-1">
                                <span class="type-badge">
                                    @{{ getTypeName(item.typeId) }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- 3. 圖片 (6:4 橫向黃金比例) -->
                    <td data-th="圖片">
                        <div class="td-content">
                            <template v-if="item.imgs && item.imgs.length > 0 && item.imgs[0].imgSrc">
                                <img
                                    :src="'/images/views/S/' + item.imgs[0].imgSrc"
                                    :alt="item.name"
                                    class="img-aspect-6-4 shadow-sm border"
                                    title="點擊檢視大圖"
                                    @click="openLightbox(item.imgs)">
                            </template>
                            <template v-else>
                                <div class="no-img-box img-aspect-6-4">
                                    暫無圖片
                                </div>
                            </template>
                        </div>
                    </td>

                    <!-- 4. 地址 -->
                    <td data-th="地址">
                        <div class="td-content">
                            <div class="address-text">
                                <template v-if="item.city || item.town">
                                    @{{ item.city || '' }} @{{ item.town || '' }}<br>
                                </template>
                                @{{ item.address || '未提供地址' }}
                            </div>
                            <div v-if="item.tel" class="tel-text mt-1">
                                📞 @{{ item.tel }}
                            </div>
                        </div>
                    </td>

                    <!-- 5. 簡介 -->
                    <td data-th="簡介">
                        <div class="td-content brief-text">
                            @{{ item.brief || item.content || '未提供簡介' }}
                        </div>
                    </td>
                </tr>

                <!-- 無資料時顯示 -->
                <tr v-if="pageData.length === 0">
                    <td colspan="5" class="text-center py-4 text-muted">目前沒有符合條件的相關資料</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 頁碼 Pagination -->
    <nav v-if="totalPages > 1" class="d-flex justify-content-center mt-4">
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


    <!-- 6. Lightbox Modal (彈窗展示所有圖片，位置在 /images/views/) -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true" ref="lightboxModalRef">
        <!-- modal-dialog-centered 負責整個彈窗的垂直置中 -->
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white border-0 shadow-lg">
                <div class="modal-header border-secondary p-2 px-3">
                    <h6 class="modal-title fst-italic">圖片檢視</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- 移除 p-0，讓內容滿版 -->
                <div class="modal-body p-0 bg-black rounded-bottom">
                    <div id="lightboxCarousel" class="carousel slide" data-bs-ride="false">

                        <!-- 圖片主體區域 -->
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

                        <!-- 切換按鈕 (僅在多張圖片時顯示) -->
                        <template v-if="activeImages.length > 1">
                            <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                                <span class="visually-hidden">上一張</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                                <span class="visually-hidden">下一張</span>
                            </button>

                            <!-- 選項：加入圖片指示器 (Indicators) -->
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

<!-- 載入 Bootstrap 5 JS (確保 Modal 正常運作) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- 載入 Vue 3 CDN -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
    // 取得後端傳入資料
    const viewsData = @json($views ?? []);
    const typesData = @json($viewstype ?? []);

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

            const selectedRegion = ref('all');
            const selectedType = ref('all');

            const pageSize = ref(10);
            const currentPage = ref(1);

            // Lightbox 相關變數
            const activeImages = ref([]);
            const lightboxModalRef = ref(null);
            let bsModal = null;

            onMounted(() => {
                // 安全初始化 Bootstrap Modal
                if (typeof bootstrap !== 'undefined' && lightboxModalRef.value) {
                    bsModal = new bootstrap.Modal(lightboxModalRef.value);
                }
            });

            // 台灣縣市與分區對照表
            const regionMap = {
                '北部': ['臺北市', '台北市', '新北市', '基隆市', '宜蘭縣', '桃園市', '新竹市', '新竹縣'],
                '中部': ['苗栗縣', '臺中市', '台中市', '彰化縣', '南投縣', '雲林縣'],
                '南部': ['嘉義市', '嘉義縣', '臺南市', '台南市', '高雄市', '屏東縣', '澎湖縣'],
                '東部': ['花蓮縣', '臺東縣', '台東縣']
            };

            // 雙重條件過濾
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

            const setRegion = (region) => {
                selectedRegion.value = region;
                currentPage.value = 1;
            };

            const setType = (typeId) => {
                selectedType.value = typeId;
                currentPage.value = 1;
            };

            const changePage = (page) => {
                if (page >= 1 && page <= totalPages.value) {
                    currentPage.value = page;
                }
            };

            // 安全開啟 Lightbox 彈窗
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