@extends("front.layout")

@section("title")
旅遊景點指南
@endsection

@push("style")
<link rel="stylesheet" href="{{ asset('css/front/travelfood.css') }}">
@endpush

@section("content")
<div id="app" class="container my-4" v-cloak>
    <div class="display-3 text-center fw-bold">旅遊景點指南</div>

    <!-- 1. 地區篩選按鈕列 (靠左對齊 + 樣式統一) -->
    <div class="d-flex align-items-center justify-content-start gap-2 my-2 flex-wrap">
        <span class="filter-label">地區：</span>
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

    <!-- 2. 景點類型篩選按鈕列 (靠左對齊 + 樣式統一) -->
    <div class="d-flex align-items-center justify-content-start gap-2 my-2 flex-wrap">
        <span class="filter-label">類型：</span>
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

    <!-- 美食表格 -->
    <table class="table mt-3 table-bordered table-rwd">
        <thead class="table-dark">
            <tr>
                <th width="5%">編號</th>
                <th width="15%">名稱</th>
                <th width="12%">類型</th>
                <th width="23%">地址</th>
                <th width="15%">電話</th>
                <th width="30%">簡介</th>
            </tr>
        </thead>
        <tbody>
            <!-- Vue 動態渲染列表 -->
            <tr v-for="(item, index) in pageData" :key="item.id || index">
                <td data-th="編號"><span>@{{ (currentPage - 1) * pageSize + index + 1 }}</span></td>
                <td data-th="名稱"><span>@{{ item.name || '未提供名稱' }}</span></td>
                <!-- 顯示對應的類型名稱 -->
                <td data-th="類型">
                    <span class="badge bg-secondary">
                        @{{ getTypeName(item.typeId) }}
                    </span>
                </td>
                <td data-th="地址">
                    <span>
                        <template v-if="item.city || item.town">
                            @{{ item.city || '' }} @{{ item.town || '' }}<br>
                        </template>
                        @{{ item.address || '未提供地址' }}
                    </span>
                </td>
                <td data-th="電話"><span>@{{ item.tel || '未提供電話' }}</span></td>
                <td data-th="簡介"><span>@{{ item.brief || item.content || '未提供簡介' }}</span></td>
            </tr>

            <!-- 無資料時顯示 -->
            <tr v-if="pageData.length === 0">
                <td colspan="6" class="text-center py-4">目前沒有符合條件的相關資料</td>
            </tr>
        </tbody>
    </table>

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
</div>

<!-- 載入 Vue 3 (CDN) -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
    // 讀取 Controller 傳進來的 $views 與 $viewstype 陣列
    const viewsData = @json($views ?? []);
    const typesData = @json($viewstype ?? []);

    const {
        createApp,
        ref,
        computed,
        watch
    } = Vue;

    createApp({
        setup() {
            const views = ref(viewsData); // 景點列表
            const types = ref(typesData); // 景點類型列表

            const selectedRegion = ref('all'); // 當前選擇地區
            const selectedType = ref('all'); // 當前選擇類型 (新增)

            const pageSize = ref(10); // 每頁顯示筆數
            const currentPage = ref(1); // 當前頁碼

            // 台灣縣市與分區對照表
            const regionMap = {
                '北部': ['臺北市', '台北市', '新北市', '基隆市', '宜蘭縣', '桃園市', '新竹市', '新竹縣'],
                '中部': ['苗栗縣', '臺中市', '台中市', '彰化縣', '南投縣', '雲林縣'],
                '南部': ['嘉義市', '嘉義縣', '臺南市', '台南市', '高雄市', '屏東縣', '澎湖縣'],
                '東部': ['花蓮縣', '臺東縣', '台東縣']
            };

            // 雙重條件搜尋：同時過濾「地區」與「類型 (typeId)」
            const filteredFoods = computed(() => {
                return views.value.filter(item => {
                    // 1. 地區判斷
                    let matchRegion = true;
                    if (selectedRegion.value !== 'all') {
                        const cities = regionMap[selectedRegion.value] || [];
                        const fullAddress = ((item.city || '') + (item.town || '') + (item.address || ''));
                        matchRegion = cities.some(city => fullAddress.includes(city));
                    }

                    // 2. 類型判斷 (typeId 對應 id)
                    let matchType = true;
                    if (selectedType.value !== 'all') {
                        matchType = Number(item.typeId) === Number(selectedType.value);
                    }

                    return matchRegion && matchType;
                });
            });

            // 根據 typeId 取得對應的 typeName
            const getTypeName = (typeId) => {
                const target = types.value.find(t => Number(t.id) === Number(typeId));
                return target ? target.typeName : '未分類';
            };

            // 計算總頁數
            const totalPages = computed(() => {
                return Math.ceil(filteredFoods.value.length / pageSize.value) || 1;
            });

            // 當前頁資料裁切
            const pageData = computed(() => {
                const start = (currentPage.value - 1) * pageSize.value;
                const end = start + pageSize.value;
                return filteredFoods.value.slice(start, end);
            });

            // 切換地區
            const setRegion = (region) => {
                selectedRegion.value = region;
                currentPage.value = 1;
            };

            // 切換類型
            const setType = (typeId) => {
                selectedType.value = typeId;
                currentPage.value = 1;
            };

            // 切換頁碼
            const changePage = (page) => {
                if (page >= 1 && page <= totalPages.value) {
                    currentPage.value = page;
                }
            };

            // 修改每頁筆數時自動回第 1 頁
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
                getTypeName,
                setRegion,
                setType,
                changePage
            };
        }
    }).mount('#app');
</script>
@endsection