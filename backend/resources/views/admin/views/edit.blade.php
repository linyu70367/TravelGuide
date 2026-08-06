@extends('admin.layout')
@section('title','景點修改')
@section('content')
<div class="card shadow-sm border-0" id="app">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 display-6 fw-bold text-primary text-center">修改景點資料</h5>
    </div>
    <div class="card-body p-4">
        <!-- ⚠️ 注意：檔案上傳必須加上 enctype="multipart/form-data" -->
        <form action="" method="POST" enctype="multipart/form-data" id="form_views">
            <input type="hidden" name="id" v-model="id">
            @csrf

            <!-- 1. 基本資訊列 (名稱、分類、電話) -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="name" class="form-label fw-bold">景點名稱 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="請輸入景點名稱" required v-model="viewsname">
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
                    <input type="text" class="form-control" id="tel" name="tel" placeholder="例如：04-12345678" v-model="viewstel">
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
                    <input type="text" class="form-control" name="address" placeholder="例如：中山路 123 號" required v-model="viewsaddress">
                </div>
            </div>

            <!-- 3. 圖片上傳 -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">現有景點圖</label>
                    <div class="d-flex flex-wrap gap-3">
                        <template v-if="imgs.length > 0">
                            <template v-for="img in imgs">
                                <div class="text-center border p-2" :id="'img' + img.id">
                                    <a :href="'/images/views/' + img.imgSrc" data-lightbox="景點圖" :data-title="viewsname">
                                        <img :src="'/images/views/S/' + img.imgSrc" style="width:100px;height:50px">
                                    </a>
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-danger" @click="delImg(img.id)">刪除</button>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-else>
                            <div class="text-danger">目前沒有景點圖</div>
                        </template>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="imgs" class="form-label fw-bold">景點圖片 (可多選)</label>
                    <input type="file" class="form-control" name="imgs[]" id="imgs" accept="image/*" multiple>
                    <div class="form-text">支援 jpg, png, webp 等圖片格式，按住 Ctrl/Cmd 可一次選擇多張。</div>
                </div>
            </div>

            <!-- 4. 簡介與詳細內容 -->
            <div class="mb-3">
                <label for="brief" class="form-label fw-bold">景點簡介</label>
                <textarea class="form-control" name="brief" rows="2" placeholder="請輸入一兩句景點簡短介紹..." v-model="viewsbrief"></textarea>
            </div>

            <div class="mb-4">
                <label for="content" class="form-label fw-bold">詳細內容</label>
                <textarea class="form-control" name="content" rows="6" placeholder="請輸入景點詳細介紹、開放時間或注意事項..." v-model="viewscontent"></textarea>
            </div>

            <!-- 5. 操作按鈕 -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="/admin/views/list" class="btn btn-outline-secondary">取消</a>
                <button type="button" class="btn btn-primary px-4" @click="SubmitForm">儲存修改</button>
            </div>
        </form>
    </div>
</div>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>


<script>
    // Vue
    Vue.createApp({
        data() {
            return {
                id: '',
                viewsname: '',
                viewstel: '',
                selectedType: '',
                types: [],
                cityData: [],
                selectedCity: '',
                AreaListData: [],
                selectedArea: '',
                viewsaddress: '',
                viewsbrief: '',
                viewscontent: '',
                imgs: []
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

                axios.patch('/admin/views/update', formData)
                    .then(function(response) {
                        console.log(response);

                        Swal.fire({
                            title: response.data.message,
                            icon: "success",
                            confirmButtonText: "確認",
                        }).then((result) => {
                            location.href = "/admin/views/list";
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
            },
            setView() {
                const vm = this;
                const pathSegments = window.location.pathname.split('/');

                // 抓取路徑最後一段id
                vm.id = pathSegments[pathSegments.length - 1];
                console.log(vm.id);

                // 將原本資料加入data變數
                axios.get('/api/views/' + vm.id)
                    .then(function(response) {
                        console.log(response.data.data);

                        let view = response.data.data;

                        vm.viewsname = view.name;
                        vm.selectedType = view.typeId;
                        vm.viewstel = view.tel;
                        vm.selectedCity = view.city;
                        vm.getAreaList();
                        vm.selectedArea = view.town;
                        vm.viewsaddress = view.address;
                        vm.viewsbrief = view.brief;
                        vm.viewscontent = view.content;
                        vm.imgs = view.imgs ?? [];
                        console.log(vm.imgs);
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        // always executed
                    });
            },
            delImg(imgId) {
                let imgDiv = $("#img" + imgId);
                Swal.fire({
                    title: "確定刪除?",
                    icon: "question",
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: "確定",
                    denyButtonText: "取消"
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('/api/imgs/' + imgId)
                            .then(function(response) {
                                imgDiv.remove();
                                Swal.fire({
                                    title: response.data.message,
                                    icon: 'success',
                                    confirmButtonText: "確定",

                                });
                            })
                            .catch(function(error) {
                                console.log(error);
                            })
                            .finally(function() {
                                // always executed
                            });
                    }
                });
            }

        },
        computed: {

        },
        mounted() {
            const vm = this;
            vm.getCityData();
            vm.getType();
            vm.setView();
        }
    }).mount('#app');
</script>
@endsection