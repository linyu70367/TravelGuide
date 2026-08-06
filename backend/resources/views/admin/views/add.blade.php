@extends('admin.layout')
@section('title','景點新增')
@section('content')
<div class="card shadow-sm border-0" id="app">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 display-6 fw-bold text-primary text-center">新增景點資料</h5>
    </div>
    <div class="card-body p-4">
        <!-- ⚠️ 注意：檔案上傳必須加上 enctype="multipart/form-data" -->
        <form action="" method="POST" enctype="multipart/form-data" id="form_views">
            @csrf

            <!-- 1. 基本資訊列 (名稱、分類、電話) -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">景點名稱 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="請輸入景點名稱" required>
                </div>
                <div class="col-md-4">
                    <label for="typeId" class="form-label fw-bold">景點分類 <span class="text-danger">*</span></label>
                    <select class="form-select" name="typeId" required v-model="selectedType">
                        <option value="" selected disabled>請選擇分類</option>
                        <option :value="type.id" v-for="type in types">
                            @{{ type.typeName }}
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tel" class="form-label fw-bold">聯絡電話</label>
                    <input type="text" class="form-control" id="tel" name="tel" placeholder="例如：04-12345678">
                </div>
            </div>

            <!-- 2. 地址 -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label for="city" class="form-label fw-bold">縣市 <span class="text-danger">*</span></label>
                    <select class="form-select" name="city" required v-model="selectedCity" @change="getAreaList">
                        <option value="" selected disabled>請選擇縣市</option>
                        <option :value="item.CityName" v-for="item in cityData">
                            @{{ item.CityName }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="town" class="form-label fw-bold">鄉鎮市區 <span class="text-danger">*</span></label>
                    <select class="form-select" name="town" required v-model="selectedArea">
                        <option value="" selected disabled>請選擇鄉鎮市區</option>
                        <option :value="item.AreaName" v-for="item in AreaListData">
                            @{{ item.AreaName }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label fw-bold">詳細地址 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="address" placeholder="例如：中山路 123 號" required>
                </div>
            </div>

            <!-- 3. 圖片上傳 -->
            <div class="row g-3 mb-3">


                <!-- 📸 新增：景點圖片多檔上傳 -->
                <div class="col-md-6">
                    <label for="imgs" class="form-label fw-bold">景點圖片 (可多選)</label>
                    <input type="file" class="form-control" name="imgs[]" id="imgs" accept="image/*" multiple>
                    <div class="form-text">支援 jpg, png, webp 等圖片格式，按住 Ctrl/Cmd 可一次選擇多張。</div>
                </div>
            </div>

            <!-- 4. 簡介與詳細內容 -->
            <div class="mb-3">
                <label for="brief" class="form-label fw-bold">景點簡介</label>
                <textarea class="form-control" name="brief" rows="2" placeholder="請輸入一兩句景點簡短介紹..."></textarea>
            </div>

            <div class="mb-4">
                <label for="content" class="form-label fw-bold">詳細內容</label>
                <textarea class="form-control" name="content" rows="6" placeholder="請輸入景點詳細介紹、開放時間或注意事項..."></textarea>
            </div>

            <!-- 5. 操作按鈕 -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="list" class="btn btn-outline-secondary">取消</a>
                <button type="button" class="btn btn-primary px-4" @click="SubmitForm">儲存景點</button>
            </div>
        </form>
    </div>
</div>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.13.2/dist/axios.min.js"></script>


<script>
    // Vue
    Vue.createApp({
        data() {
            return {
                selectedType: '',
                types: [],
                cityData: [],
                selectedCity: '',
                AreaListData: [],
                selectedArea: '',
            }
        },
        methods: {
            getType() {
                const vm = this;
                axios.get('/api/viewstype')
                    .then(function(response) {
                        console.log(response);

                        vm.types = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        // always executed
                    });
            },
            getCityData() {
                const vm = this;
                axios.get('/js/CityCountyData.json')
                    .then(function(response) {
                        console.log(response);
                        vm.cityData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        // always executed
                    });
            },
            getAreaList() {
                const vm = this;
                vm.cityData.forEach(function(item) {
                    if (item.CityName == vm.selectedCity) {
                        vm.AreaListData = item.AreaList;
                    }
                })
            },
            SubmitForm() {
                const vm = this;
                const formElement = document.getElementById('form_views');
                let formData = new FormData(formElement);

                axios.post('store', formData)
                    .then(function(response) {
                        console.log(response);

                        Swal.fire({
                            title: response.data.message,
                            icon: "success",
                            confirmButtonText: "確認",
                        }).then((result) => {
                            location.href = "list";
                        });
                    })
                    .catch(function(error) {
                        console.log(error.response);
                        Swal.fire({
                            title: error.response.data.message,
                            icon: "error",
                            confirmButtonText: "確認",
                        });
                    })
                    .finally(function() {
                        // always executed
                    });
            }
        },
        computed: {

        },
        mounted() {
            const vm = this;
            vm.getCityData();
            vm.getType();
        }
    }).mount('#app');
</script>
@endsection