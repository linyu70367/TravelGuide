@extends("front.layout")

@section("title")
地方美食(opendata)
@endsection

@push("style")
<link rel="stylesheet" href="{{ asset('css/front/travelfood.css') }}">
@endpush

@section("content")
<div id="app" class="container my-4" v-cloak>
    <div class="display-3 text-center fw-bold">地方美食(opendata)</div>

    <!-- 地區篩選按鈕列 -->
    <div class="d-flex gap-2 justify-content-center my-3">
        <button
            type="button"
            class="btn btn-lg"
            :class="selectedRegion === 'all' ? 'btn-primary' : 'btn-outline-primary'"
            @click="setRegion('all')">
            全部美食 (@{{ foodsData.length }})
        </button>
        <button
            v-for="region in ['北部', '中部', '南部', '東部']"
            :key="region"
            type="button"
            class="btn btn-lg"
            :class="selectedRegion === region ? 'btn-primary' : 'btn-outline-primary'"
            @click="setRegion(region)">
            @{{ region }}
        </button>
    </div>

    <!-- 每頁顯示筆數與排序設定 -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4 mb-2">
        <div class="d-flex align-items-center">
            <label for="dataPerPage" class="fw-bold me-2">每頁顯示</label>

            <select
                id="dataPerPage"
                v-model.number="pageSize"
                class="form-select form-select-sm me-2"
                style="width: 100px;">
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="50">50</option>
            </select>

            <span>筆（共 @{{ sortedFoods.length }} 筆資料）</span>
        </div>

        <!-- 排序功能 -->
        <div class="d-flex align-items-center gap-2">
            <label for="sortField" class="fw-bold">排序欄位</label>

            <select
                id="sortField"
                v-model="sortField"
                class="form-select form-select-sm"
                style="width: 130px;">
                <option value="">原始順序</option>
                <option value="Name">名稱</option>
                <option value="City">城市</option>
            </select>

            <select
                v-model="sortDirection"
                class="form-select form-select-sm"
                style="width: 120px;"
                :disabled="sortField === ''">
                <option value="asc">升冪排序</option>
                <option value="desc">降冪排序</option>
            </select>
        </div>
    </div>

    <!-- 美食表格 -->
    <table class="table mt-3 table-bordered table-rwd">
        <thead class="table-dark">
            <tr>
                <th width="5%">編號</th>
                <th width="25%">名稱</th>
                <th width="20%">圖片</th>
                <th width="20%">地址</th>
                <th width="30%">簡介</th>
            </tr>
        </thead>
        <tbody>
            <!-- Vue 動態渲染列表 -->
            <tr v-for="(item, index) in pageData" :key="index">
                <td data-th="編號"><span>@{{ (currentPage - 1) * pageSize + index + 1 }}</span></td>
                <td data-th="名稱"><span><a :href="'/travelfood/' + item.id">@{{ item.Name || '未提供名稱' }}</a></span></td>
                <td data-th="圖片">
                    <span>
                        <img v-if="item.PicURL" :src="item.PicURL" class="img-fluid" :alt="item.Name">
                        <span v-else class="text-muted">未提供圖片</span>
                    </span>
                </td>
                <td data-th="地址">
                    <span>
                        @{{ item.City || '' }}<br v-if="item.City">
                        @{{ item.Town || '' }}<br v-if="item.Town">
                        @{{ item.Address || '未提供地址' }}
                    </span>
                </td>
                <td data-th="簡介"><span>@{{ item.HostWords || '未提供簡介' }}</span></td>
            </tr>

            <!-- 無資料時顯示 -->
            <tr v-if="pageData.length === 0">
                <td colspan="5" class="text-center py-4">目前沒有相關資料</td>
            </tr>
        </tbody>
    </table>

    <!-- 頁碼 Pagination -->
    <nav v-if="totalPages > 1" class="d-flex justify-content-center mt-4">
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="changePage(currentPage - 1)">上一頁</button>
            </li>
            <li
                v-for="page in totalPages"
                :key="page"
                class="page-item"
                :class="{ active: currentPage === page }">
                <button class="page-link" @click="changePage(page)">@{{ page }}</button>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button class="page-link" @click="changePage(currentPage + 1)">下一頁</button>
            </li>
        </ul>
    </nav>
</div>

<!-- 載入 Vue 3 (CDN) -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
    // 將後端傳進來的 PHP 陣列轉給 JS
    const foodsData = @json($foods ?? []);

    const {
        createApp,
        ref,
        computed,
        watch
    } = Vue;

    createApp({
        setup() {
            const selectedRegion = ref('all');
            const pageSize = ref(10);
            const currentPage = ref(1);

            // 排序欄位：空字串、Name、City
            const sortField = ref('');

            // 排序方向：asc、desc
            const sortDirection = ref('asc');

            // 台灣縣市與分區對照表
            const regionMap = {
                '北部': [
                    '臺北市',
                    '台北市',
                    '新北市',
                    '基隆市',
                    '宜蘭縣',
                    '桃園市',
                    '新竹市',
                    '新竹縣'
                ],
                '中部': [
                    '苗栗縣',
                    '臺中市',
                    '台中市',
                    '彰化縣',
                    '南投縣',
                    '雲林縣'
                ],
                '南部': [
                    '嘉義市',
                    '嘉義縣',
                    '臺南市',
                    '台南市',
                    '高雄市',
                    '屏東縣',
                    '澎湖縣'
                ],
                '東部': [
                    '花蓮縣',
                    '臺東縣',
                    '台東縣'
                ]
            };

            // 1. 根據選擇的地區過濾資料
            const filteredFoods = computed(() => {
                if (selectedRegion.value === 'all') {
                    return foodsData;
                }

                const cities = regionMap[selectedRegion.value] || [];

                return foodsData.filter(item => {
                    const itemCity = item.City || '';
                    const itemAddress = item.Address || '';

                    return cities.some(city => {
                        return itemCity.includes(city) ||
                            itemAddress.includes(city);
                    });
                });
            });

            // 2. 根據名稱或城市排序
            const sortedFoods = computed(() => {
                // 建立副本，避免直接修改 filteredFoods 或 foodsData
                const data = [...filteredFoods.value];

                // 未選擇排序欄位時，維持原始順序
                if (!sortField.value) {
                    return data;
                }

                return data.sort((a, b) => {
                    const valueA = String(a[sortField.value] || '').trim();
                    const valueB = String(b[sortField.value] || '').trim();

                    // 空白資料排在最後
                    if (!valueA && valueB) {
                        return 1;
                    }

                    if (valueA && !valueB) {
                        return -1;
                    }

                    if (!valueA && !valueB) {
                        return 0;
                    }

                    const result = valueA.localeCompare(
                        valueB,
                        'zh-Hant', {
                            numeric: true,
                            sensitivity: 'base'
                        }
                    );

                    return sortDirection.value === 'asc' ?
                        result :
                        -result;
                });
            });

            // 3. 計算總頁數
            const totalPages = computed(() => {
                return Math.ceil(
                    sortedFoods.value.length / pageSize.value
                ) || 1;
            });

            // 4. 根據目前頁碼裁切排序後的資料
            const pageData = computed(() => {
                const start = (
                    currentPage.value - 1
                ) * pageSize.value;

                const end = start + pageSize.value;

                return sortedFoods.value.slice(start, end);
            });

            // 切換區域
            const setRegion = (region) => {
                selectedRegion.value = region;
                currentPage.value = 1;
            };

            // 切換頁碼
            const changePage = (page) => {
                if (
                    page >= 1 &&
                    page <= totalPages.value
                ) {
                    currentPage.value = page;
                }
            };

            // 每頁筆數改變時，回到第一頁
            watch(pageSize, () => {
                currentPage.value = 1;
            });

            // 排序欄位改變時，回到第一頁
            watch(sortField, () => {
                currentPage.value = 1;
            });

            // 排序方向改變時，回到第一頁
            watch(sortDirection, () => {
                currentPage.value = 1;
            });

            return {
                foodsData,
                selectedRegion,
                pageSize,
                currentPage,
                sortField,
                sortDirection,
                filteredFoods,
                sortedFoods,
                totalPages,
                pageData,
                setRegion,
                changePage
            };
        }
    }).mount('#app');
</script>
@endsection