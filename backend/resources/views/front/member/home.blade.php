@extends("front.layout")
@section("title", "會員中心")

@push("style")
<link rel="stylesheet" href="/css/front/index.css">
<link rel="stylesheet" href="/css/front/member/home.css">
<link rel="stylesheet" href="/css/front/member/changePwd.css">
<link rel="stylesheet" href="{{ asset('css/front/views.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css">
@endpush

@section("content")
<div id="app" class="container py-4" v-cloak>

    <!-- 頂部會員基本資訊區塊 -->
    <div class="row mb-4" id="member-info">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="member-profile">
                <div class="member-profile-body">
                    <img :src="member.avatarUrl" class="member-avatar" id="info_avatar">
                    <h5 class="member_name">@{{ member.memberName }}</h5>
                    <p id="member_email">@{{ member.email }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="member-banner">
                <h2>歡迎回來，<span class="member_name">@{{ member.memberName }}</span></h2>
                <p>管理您的會員資料、收藏景點與帳號安全。</p>
            </div>
        </div>
    </div>

    <!-- 1. 會員主選單頁面 (v-if="currentPage === 'menu'") -->
    <div v-if="currentPage === 'menu'" class="row g-4" id="member-menu">
        <div class="col-md-6">
            <div class="card member-card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                    <h5 class="mt-3">會員資料</h5>
                    <p class="text-muted">查看您的基本資料、Email、加入日期等資訊。</p>
                    <button class="btn btn-outline-primary" @click="currentPage = 'profile'">查看資料</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card member-card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-heart-fill fs-1 text-danger"></i>
                    <h5 class="mt-3">收藏景點</h5>
                    <p class="text-muted">管理您收藏的景點與旅遊行程。</p>
                    <button class="btn btn-outline-danger" @click="currentPage = 'wishlist'">查看收藏</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card member-card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-pencil-square fs-1 text-success"></i>
                    <h5 class="mt-3">修改會員資料</h5>
                    <p class="text-muted">更新姓名、電話等個人資訊。</p>
                    <button class="btn btn-outline-success" @click="openEditPage">前往修改</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card member-card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-shield-lock fs-1 text-warning"></i>
                    <h5 class="mt-3">修改密碼</h5>
                    <p class="text-muted">定期更新密碼，提升帳號安全性。</p>
                    <button class="btn btn-outline-warning" @click="currentPage = 'pwd'">修改密碼</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 查看會員資料頁面 -->
    <div v-if="currentPage === 'profile'" class="card member-page">
        <div class="row my-5 justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h2 class="display-6 fw-bold result-title">📋 會員資料</h2>
                </div>
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">會員名稱</div>
                            <div class="col-8">@{{ member.memberName }}</div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">電子信箱</div>
                            <div class="col-8">@{{ member.email }}</div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">電話</div>
                            <div class="col-8">@{{ member.tel }}</div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">生日</div>
                            <div class="col-8">@{{ member.birthday }}</div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">地址</div>
                            <div class="col-8">@{{ member.address }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-4 fw-bold">會員狀態</div>
                            <div class="col-8">@{{ member.status }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4">
                        <button class="btn btn-secondary w-100 rounded-5 d-flex justify-content-center align-items-center" @click="backToMenu">返回</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. 修改會員資料頁面 -->
    <div v-if="currentPage === 'edit'" class="card member-page">
        <div class="row my-5 justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h2 class="display-6 fw-bold result-title">📝 會員修改</h2>
                </div>
                <div class="card shadow-sm rounded-4">
                    <form @submit.prevent="handleEditSubmit" enctype="multipart/form-data">
                        <input type="hidden" name="edit_id" :value="member.id">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <input type="file" id="avatar" class="d-none" accept="image/*" @change="onAvatarChange">
                                    <label for="avatar">
                                        <img :src="editForm.avatarPreview || editForm.avatarUrl" class="member-edit-avatar">
                                    </label>
                                </div>
                                <div class="col-md-7 mb-3">
                                    <label class="col-4 fw-bold form-label">會員名稱</label>
                                    <input type="text" class="form-control" v-model="editForm.memberName" required>
                                    <label class="col-4 fw-bold form-label mt-2">生日</label>
                                    <input type="date" class="form-control" v-model="editForm.birthday">
                                </div>
                                <hr>
                                <div class="col-10 mb-3 d-flex align-items-end gap-2">
                                    <div class="flex-grow-1">
                                        <label class="fw-bold form-label">電子信箱</label>
                                        <small :class="emailMsgClass" class="d-block mb-1">
                                            @{{ emailMsg }}
                                        </small>
                                        <input type="text" class="form-control" v-model="editForm.email" placeholder="請輸入電子信箱" required>
                                    </div>
                                    <button type="button" class="btn btn-success" :disabled="isCheckingEmail" @click="checkEmail">檢查信箱</button>
                                </div>
                                <hr>
                                <div class="col-8 mb-3">
                                    <label class="col-4 fw-bold form-label">電話</label>
                                    <input type="text" class="form-control"
                                        :class="{'is-valid': isTelValid === true, 'is-invalid': isTelValid === false}"
                                        v-model="editForm.tel"
                                        @input="validateTel"
                                        placeholder="請輸入電話號碼">
                                    <div class="valid-feedback">電話格式符合</div>
                                    <div class="invalid-feedback">請輸入正確的電話格式</div>
                                </div>
                                <hr>
                                <div class="col-12 mb-3">
                                    <label class="col-4 fw-bold form-label">地址</label>
                                    <input type="text" class="form-control" v-model="editForm.address">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="bg-transparent p-4 d-flex justify-content-around">
                                <button type="submit" class="btn btn-success rounded-5" :disabled="!isEmailChecked">
                                    <i class="fa fa-save"></i> 儲存修改
                                </button>
                                <button type="button" class="btn btn-secondary rounded-5" @click="backToMenu">
                                    <i class="fa fa-times"></i> 返回
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. 修改密碼頁面 -->
    <div v-if="currentPage === 'pwd'" class="card member-page">
        <div class="row my-5 justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h2 class="display-6 fw-bold result-title">
                        <i class="fa fa-shield-alt" style="color:var(--gold)"></i> 修改密碼
                    </h2>
                </div>
                <div class="card shadow-sm rounded-4">
                    <form @submit.prevent="handlePwdSubmit">
                        @csrf
                        <div class="card-body p-4">
                            <!-- 目前密碼 -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="oldpwd">
                                    目前密碼 <span style="color:var(--danger)">*</span>
                                </label>
                                <div class="icon-wrap">
                                    <i class="fa fa-lock icon"></i>
                                    <input :type="showOldPwd ? 'text' : 'password'" id="oldpwd" v-model="pwdForm.oldpwd" class="form-control" placeholder="請輸入目前密碼" required>
                                    <span class="pw-toggle" @click="showOldPwd = !showOldPwd">
                                        <i class="fa" :class="showOldPwd ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- 新密碼 -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="newpwd">
                                    新密碼 <span style="color:var(--danger)">*</span>
                                </label>
                                <div class="icon-wrap">
                                    <i class="fa fa-key icon"></i>
                                    <input :type="showNewPwd ? 'text' : 'password'" id="newpwd" v-model="pwdForm.newpwd" class="form-control" placeholder="至少 8 個字元" required @input="checkStrength">
                                    <span class="pw-toggle" @click="showNewPwd = !showNewPwd">
                                        <i class="fa" :class="showNewPwd ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </span>
                                </div>
                                <div class="pw-strength mt-2">
                                    <div class="pw-strength-bar" :style="{ width: pwStrength.width, background: pwStrength.color }"></div>
                                </div>
                                <p class="field-hint">@{{ pwStrength.hint }}</p>
                            </div>

                            <!-- 確認新密碼 -->
                            <div class="form-group mb-4">
                                <label class="form-label fw-bold" for="newpwd_confirmation">
                                    確認新密碼 <span style="color:var(--danger)">*</span>
                                </label>
                                <div class="icon-wrap">
                                    <i class="fa fa-key icon"></i>
                                    <input :type="showConfirmPwd ? 'text' : 'password'" id="newpwd_confirmation" v-model="pwdForm.newpwd_confirmation" class="form-control" placeholder="再次輸入新密碼" required @keyup="checkPwdMatch">
                                    <span class="pw-toggle" @click="showConfirmPwd = !showConfirmPwd">
                                        <i class="fa" :class="showConfirmPwd ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </span>
                                </div>
                                <p class="field-hint" :style="{ color: pwdMatch.color }">@{{ pwdMatch.hint }}</p>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="bg-transparent p-4 d-flex justify-content-around">
                                <button type="submit" class="btn btn-warning btn-lg w-30 rounded-5">
                                    <i class="fa fa-save"></i> 確認修改
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg w-30 rounded-5" @click="backToMenu()">
                                    <i class="fa fa-times"></i> 返回
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. 收藏景點頁面 -->
    <div v-if="currentPage === 'wishlist'" class="card member-page">
        <div class="row my-5 justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h2 class="display-6 fw-bold result-title">🏞️ 收藏景點</h2>
                </div>
                <div id="favorite-list">
                    <!-- 沒有收藏景點時的頁面 -->
                    <div v-if="favorites.length === 0" class="card shadow-sm rounded-4 mb-3 mx-3 favorite-card">
                        <div class="row g-0">
                            <div class="col-12">
                                <div class="card-body p-5 text-center">
                                    <div class="mb-4">
                                        <i class="bi bi-heart text-secondary" style="font-size: 5rem;"></i>
                                    </div>

                                    <h3 class="fw-bold mb-3">
                                        尚未收藏任何景點
                                    </h3>

                                    <p class="text-muted mb-4 fs-5">
                                        快到 <span class="fw-bold text-primary">景點探索</span> 找尋更多值得收藏的景點吧！
                                    </p>

                                    <button
                                        class="btn btn-primary rounded-pill px-5"
                                        @click="goViews">
                                        <i class="bi bi-compass me-2"></i>
                                        前往景點探索
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 示範寫法：可以用 v-for 循環渲染真正的收藏列表 -->
                    <div v-for="(item, index) in favorites" :key="index" class="card shadow-sm rounded-4 mb-3 mx-3 favorite-card">
                        <div class="row g-0">
                            <!-- 左側圖片區 -->
                            <div class="col-md-5 p-4">
                                <div class="d-flex justify-content-start align-items-center gap-2 mb-3">
                                    <h4 class="fw-bold mb-0">@{{ item.views.name }}</h4>
                                    <button type="button" class="btn heart_btn p-0" @click="toggleHeart(item)">
                                        <i class="fa-solid fa-heart" :style="{ color:item.isLiked ? 'rgb(233, 122, 122)' : '#ccc' }"></i>
                                    </button>
                                </div>
                                <a :href="'/views/' + item.views.id" class="text-center">
                                    <img :src="'/images/views/' + item.views.imgs[0].imgSrc" class="favorite-img w-75 rounded-4" :alt="item.name">
                                </a>
                            </div>
                            <!-- 右側資料區 -->
                            <div class="col-md-7 text-center">
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <h5 class="fw-bold d-flex justify-content-start">景點簡介</h5>
                                        <p class="text-muted mt-3">@{{ item.views.brief }}</p>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-4 fw-bold">
                                            <i class="bi bi-telephone-fill"></i> 電話
                                        </div>
                                        <div class="col-8">@{{ item.views.tel }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 fw-bold">
                                            <i class="bi bi-geo-alt-fill"></i> 地址
                                        </div>
                                        <div class="col-8">@{{ item.views.city + item.views.town + item.views.address }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-transparent border-0 p-4 d-flex justify-content-center">
                        <button class="btn btn-secondary w-50 rounded-5 d-flex justify-content-center align-items-center" @click="backToMenu(),loadWishlist()">
                            返回
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<!-- 引入 Vue 3 CDN (若 layout 已全域引入可省略此行) -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<script>
    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;

    const {
        createApp
    } = Vue;

    createApp({
        data() {
            return {
                // 頁面控制狀態: 'menu' | 'profile' | 'edit' | 'pwd' | 'wishlist'
                currentPage: 'menu',

                // 會員基本資料
                member: {
                    id: '',
                    memberName: '',
                    email: '',
                    tel: '',
                    birthday: '',
                    address: '',
                    status: '',
                    avatar: '',
                    avatarUrl: ''
                },

                // 修改會員資料表單
                editForm: {
                    memberName: '',
                    email: '',
                    tel: '',
                    birthday: '',
                    address: '',
                    avatarFile: null,
                    avatarPreview: '',
                    avatarUrl: ''
                },
                isTelValid: null,
                isEmailChecked: false,
                isCheckingEmail: false,
                emailMsg: '信箱未通過檢查',
                emailMsgClass: 'text-danger',

                // 修改密碼表單
                pwdForm: {
                    oldpwd: '',
                    newpwd: '',
                    newpwd_confirmation: ''
                },
                showOldPwd: false,
                showNewPwd: false,
                showConfirmPwd: false,
                pwStrength: {
                    width: '0%',
                    color: '#ef4444',
                    hint: '請輸入至少 8 個字元，包含英文與數字'
                },
                pwdMatch: {
                    hint: '',
                    color: '#dc2626',
                    isValid: false
                },

                // 收藏景點 
                favorites: [],
            };
        },
        mounted() {
            this.loadMember();
            this.loadWishlist();
        },
        methods: {
            // 載入會員資料
            async loadMember() {
                try {
                    const response = await axios.get('/api/member/profile');
                    this.member = response.data;
                    this.member.avatarUrl = '/images/member/' + this.member.avatar;
                } catch (error) {
                    console.error("載入會員資料失敗：", error);
                }
            },

            // 開啟修改頁面時初始化表單
            openEditPage() {
                this.editForm = {
                    memberName: this.member.memberName,
                    email: this.member.email,
                    tel: this.member.tel,
                    birthday: this.member.birthday,
                    address: this.member.address,
                    avatarFile: null,
                    avatarPreview: '',
                    avatarUrl: '/images/member/' + this.member.avatar
                };
                this.isEmailChecked = false;
                this.emailMsg = '信箱未通過檢查';
                this.emailMsgClass = 'text-danger';
                this.validateTel();
                this.currentPage = 'edit';
            },

            // 返回主選單
            backToMenu() {
                this.currentPage = 'menu';
            },

            // 大頭貼預覽
            onAvatarChange(e) {
                const file = e.target.files[0];
                if (file) {
                    this.editForm.avatarFile = file;
                    this.editForm.avatarPreview = URL.createObjectURL(file);
                }
            },

            // 電話格式驗證
            validateTel() {
                const reg = /^[0-9-]*[0-9][0-9-]*$/;
                if (!this.editForm.tel || !reg.test(this.editForm.tel)) {
                    this.isTelValid = false;
                } else {
                    this.isTelValid = true;
                }
            },

            // 信箱重複檢查
            async checkEmail() {
                const reg = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
                if (!reg.test(this.editForm.email)) {
                    this.emailMsg = "請輸入正確的信箱格式";
                    this.emailMsgClass = "text-danger";
                    this.isEmailChecked = false;
                    return;
                }

                this.isCheckingEmail = true;
                try {
                    const response = await axios.get('/api/member/checkEmail', {
                        params: {
                            email: this.editForm.email
                        }
                    });
                    if (response.data.exist) {
                        this.emailMsg = "信箱已使用，請重新輸入!";
                        this.emailMsgClass = "text-danger";
                        this.isEmailChecked = false;
                    } else {
                        this.emailMsg = "信箱通過檢查";
                        this.emailMsgClass = "text-success";
                        this.isEmailChecked = true;
                    }
                } catch (error) {
                    this.emailMsg = "系統連線異常，請稍後再試";
                    this.emailMsgClass = "text-danger";
                    this.isEmailChecked = false;
                } finally {
                    this.isCheckingEmail = false;
                }
            },

            // 提交修改會員資料
            async handleEditSubmit() {
                if (!this.isTelValid) {
                    alert("電話格式不符");
                    return;
                }

                const formData = new FormData();
                formData.append('edit_name', this.editForm.memberName);
                formData.append('edit_email', this.editForm.email);
                formData.append('edit_tel', this.editForm.tel);
                formData.append('edit_birthday', this.editForm.birthday);
                formData.append('edit_address', this.editForm.address);
                if (this.editForm.avatarFile) {
                    formData.append('edit_avatar', this.editForm.avatarFile);
                }

                try {
                    const response = await axios.post('/api/member/update', formData);
                    if (response.data.message === '成功修改!') {
                        await this.loadMember();
                        this.currentPage = 'menu';
                        Swal.fire({
                            title: response.data.message,
                            icon: "success",
                            confirmButtonText: "確定"
                        });
                    } else {
                        Swal.fire({
                            title: "修改失敗!",
                            text: "請檢查資料格式",
                            icon: "error"
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: "修改失敗",
                        text: "系統錯誤",
                        icon: "error"
                    });
                }
            },

            // 檢查密碼強度
            checkStrength() {
                const v = this.pwdForm.newpwd;
                let s = 0;
                if (v.length >= 8) s++;
                if (/[0-9]/.test(v)) s++;
                if (/[A-Za-z]/.test(v)) s++;
                if (/[^A-Za-z0-9]/.test(v)) s++;

                const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
                const labels = ['過短', '弱', '普通', '強'];
                const idx = Math.max(0, s - 1);

                this.pwStrength = {
                    width: (s * 25) + '%',
                    color: colors[idx],
                    hint: '密碼強度：' + labels[idx]
                };
            },

            // 檢查第二次密碼是否一致
            checkPwdMatch() {
                const {
                    newpwd,
                    newpwd_confirmation
                } = this.pwdForm;
                if (newpwd_confirmation && newpwd_confirmation !== newpwd) {
                    this.pwdMatch = {
                        hint: '⚠ 兩次密碼不一致',
                        color: '#dc2626',
                        isValid: false
                    };
                } else if (newpwd_confirmation) {
                    this.pwdMatch = {
                        hint: '✓ 密碼一致',
                        color: '#16a34a',
                        isValid: true
                    };
                } else {
                    this.pwdMatch = {
                        hint: '',
                        color: '#dc2626',
                        isValid: false
                    };
                }
            },

            // 提交修改密碼
            async handlePwdSubmit() {
                if (!this.pwdMatch.isValid) {
                    alert("請檢查密碼是否相符");
                    return;
                }

                const formData = new FormData();
                formData.append('oldpwd', this.pwdForm.oldpwd);
                formData.append('newpwd', this.pwdForm.newpwd);
                formData.append('newpwd_confirmation', this.pwdForm.newpwd_confirmation);

                try {
                    const response = await axios.post('/api/member/updatePwd', formData);
                    if (response.data.success) {
                        await this.loadMember();
                        this.currentPage = 'menu';
                        Swal.fire({
                            title: response.data.message,
                            icon: "success",
                            confirmButtonText: "確定"
                        });
                        // 清空表單
                        this.pwdForm = {
                            oldpwd: '',
                            newpwd: '',
                            newpwd_confirmation: ''
                        };
                    }
                } catch (error) {
                    if (error.response) {
                        Swal.fire({
                            title: error.response.data.message,
                            icon: "error"
                        });
                    } else {
                        Swal.fire({
                            title: "系統錯誤",
                            text: "請稍後再試",
                            icon: "error"
                        });
                    }
                }
            },
            async loadWishlist() {
                try {
                    const vm = this;
                    let response = await axios.get('/api/wishlist/list');
                    if (response.data.status) {
                        console.log(response);
                        vm.favorites = response.data.data ? response.data.data : [];
                        vm.favorites = response.data.data.map(item => ({
                            ...item,
                            isLiked: true
                        }));
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            goViews() {
                window.location.href = "/views";
            },
            async toggleHeart(item) {

                if (item.isLiked) {
                    // 呼叫刪除收藏 API
                    console.log("取消收藏", item.views.id);
                    try {
                        let response = await axios.delete('/api/wishlist/delete', {
                            data: {
                                viewsId: item.views.id
                            }
                        });
                        console.log(response);
                        item.isLiked = false;
                    } catch (error) {
                        console.log(error.response);
                    }

                } else {
                    // 呼叫加入收藏 API
                    console.log("加入收藏", item.views.id);
                    try {
                        let response = await axios.post('/api/wishlist/add', {
                            viewsId: item.views.id
                        });
                        console.log(response);
                        item.isLiked = true;

                    } catch (error) {
                        console.log(error.response);
                    }
                }
            }
        }
    }).mount('#app');
</script>

<style>
    /* 避免 Vue 載入前的插值語法閃爍 (需搭配 v-cloak) */
    [v-cloak] {
        display: none;
    }
</style>
@endsection