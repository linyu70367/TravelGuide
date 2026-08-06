@extends("front.layout")

@section("title")
景點類型管理
@endsection

@push("style")
<style>
    [v-cloak] {
        display: none;
    }

    .view-type-page {
        padding: 32px 0;
    }

    .page-title {
        color: #1b2e5e;
        font-weight: 700;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .date-column {
        min-width: 175px;
        white-space: nowrap;
    }

    .action-column {
        min-width: 170px;
    }

    .auto-id-input {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
</style>
@endpush

@section("content")

<div
    id="viewTypeApp"
    class="container view-type-page"
    v-cloak>
    <h1 class="page-title text-center mb-4">
        @{{ title }}
    </h1>

    <!-- 成功訊息 -->
    <div
        v-if="successMessage"
        class="alert alert-success alert-dismissible fade show"
        role="alert">
        @{{ successMessage }}

        <button
            type="button"
            class="btn-close"
            v-on:click="successMessage = ''"></button>
    </div>

    <!-- 錯誤訊息 -->
    <div
        v-if="errorMessage"
        class="alert alert-danger alert-dismissible fade show"
        role="alert">
        @{{ errorMessage }}

        <button
            type="button"
            class="btn-close"
            v-on:click="errorMessage = ''"></button>
    </div>

    <!-- 新增表單 -->
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">
            新增景點類型
        </div>

        <div class="card-body">
            <form v-on:submit.prevent="createType">
                <div class="row g-3 align-items-end">

                    <!-- 自動產生的 ID -->
                    <div class="col-md-3">
                        <label for="newId" class="form-label">
                            新增 ID
                        </label>

                        <input
                            id="newId"
                            type="number"
                            class="form-control auto-id-input"
                            v-bind:value="nextAvailableId"
                            readonly>

                    </div>

                    <!-- 類型名稱 -->
                    <div class="col-md-6">
                        <label for="newTypeName" class="form-label">
                            類型名稱
                        </label>

                        <input
                            id="newTypeName"
                            v-model.trim="createForm.typeName"
                            type="text"
                            class="form-control"
                            maxlength="10"
                            placeholder="例如：國家公園"
                            v-bind:disabled="creating"
                            required>
                    </div>

                    <div class="col-md-3">
                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                            v-bind:disabled="creating || loading">
                            <span
                                v-if="creating"
                                class="spinner-border spinner-border-sm me-1"></span>

                            @{{ creating ? '新增中' : '新增類型' }}
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- 類型列表 -->
    <div class="card shadow-sm">
        <div
            class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">
                景點類型列表
            </span>

            <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                v-on:click="fetchTypes"
                v-bind:disabled="loading">
                <span
                    v-if="loading"
                    class="spinner-border spinner-border-sm me-1"></span>

                @{{ loading ? '讀取中' : '重新整理' }}
            </button>
        </div>

        <div class="card-body">

            <!-- 載入中 -->
            <div
                v-if="loading && types.length === 0"
                class="text-center py-5">
                <div class="spinner-border text-primary"></div>

                <p class="text-muted mt-3 mb-0">
                    資料載入中
                </p>
            </div>

            <!-- 資料表格 -->
            <div
                v-else-if="types.length > 0"
                class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 100px;">ID</th>
                            <th>類型名稱</th>
                            <th class="date-column">建立時間</th>
                            <th class="date-column">更新時間</th>
                            <th class="action-column">操作</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template
                            v-for="item in types"
                            v-bind:key="item.id">
                            <!-- 編輯狀態 -->
                            <tr v-if="editingId === Number(item.id)">
                                <!-- ID 只能顯示 -->
                                <td class="fw-bold">
                                    @{{ item.id }}
                                </td>

                                <!-- 只能修改 typeName -->
                                <td>
                                    <input
                                        v-model.trim="editForm.typeName"
                                        type="text"
                                        class="form-control"
                                        maxlength="100"
                                        v-bind:disabled="updating"
                                        v-on:keyup.enter="updateType"
                                        v-on:keyup.esc="cancelEdit"
                                        required>
                                </td>

                                <td class="date-column">
                                    @{{ formatDate(item.created_at) }}
                                </td>

                                <td class="date-column">
                                    @{{ formatDate(item.updated_at) }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-success"
                                            v-on:click="updateType"
                                            v-bind:disabled="updating">
                                            <span
                                                v-if="updating"
                                                class="spinner-border spinner-border-sm me-1"></span>

                                            @{{ updating ? '儲存中' : '儲存' }}
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-secondary"
                                            v-on:click="cancelEdit"
                                            v-bind:disabled="updating">
                                            取消
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- 一般顯示狀態 -->
                            <tr v-else>
                                <td>
                                    @{{ item.id }}
                                </td>

                                <td>
                                    @{{ item.typeName }}
                                </td>

                                <td class="date-column">
                                    @{{ formatDate(item.created_at) }}
                                </td>

                                <td class="date-column">
                                    @{{ formatDate(item.updated_at) }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            v-on:click="startEdit(item)"
                                            v-bind:disabled="deletingId !== null">
                                            編輯
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            v-on:click="deleteType(item)"
                                            v-bind:disabled="deletingId !== null">
                                            <span
                                                v-if="deletingId === Number(item.id)"
                                                class="spinner-border spinner-border-sm me-1"></span>

                                            @{{ deletingId === Number(item.id)
                                                ? '刪除中'
                                                : '刪除' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- 沒有資料 -->
            <div
                v-else
                class="alert alert-secondary text-center mb-0">
                目前沒有景點類型資料，第一筆資料的 ID 將使用 1。
            </div>

        </div>
    </div>
</div>

<!-- Vue 3 -->
<script src="{{ asset('js/vue.global.js') }}"></script>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    /*
     * 如果路由寫在 routes/web.php：
     * /viewstype
     *
     * 如果路由寫在 routes/api.php：
     * 改成 /api/viewstype
     */
    const viewTypeApi = @json(url('/viewstype'));

    /*
     * 若路由寫在 web.php，POST、PUT、DELETE 需要 CSRF Token。
     */
    const csrfToken = @json(csrf_token());

    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

    const App = {
        data() {
            return {
                title: '景點類型管理',

                types: [],

                loading: false,
                creating: false,
                updating: false,
                deletingId: null,

                editingId: null,

                successMessage: '',
                errorMessage: '',

                createForm: {
                    typeName: ''
                },

                editForm: {
                    typeName: ''
                }
            };
        },

        computed: {
            /*
             * 找出目前最小的可用正整數 ID。
             *
             * 現有 ID：1、2、4、5
             * 下一個 ID：3
             *
             * 現有 ID：2、3、4
             * 下一個 ID：1
             */
            nextAvailableId() {
                const usedIds = new Set(
                    this.types
                    .map(function(item) {
                        return Number(item.id);
                    })
                    .filter(function(id) {
                        return Number.isInteger(id) && id > 0;
                    })
                );

                let newId = 1;

                while (usedIds.has(newId)) {
                    newId++;
                }

                return newId;
            }
        },

        methods: {
            clearMessages() {
                this.successMessage = '';
                this.errorMessage = '';
            },

            /*
             * 支援兩種 API 格式：
             *
             * {
             *     status: "success",
             *     data: []
             * }
             *
             * 或直接回傳：
             *
             * []
             */
            getResponseData(response) {
                if (
                    response.data &&
                    Object.prototype.hasOwnProperty.call(
                        response.data,
                        'data'
                    )
                ) {
                    return response.data.data;
                }

                return response.data;
            },

            getErrorMessage(error, defaultMessage = '操作失敗') {
                const responseData = error.response?.data;

                /*
                 * Laravel validate() 回傳格式：
                 *
                 * {
                 *     message: "...",
                 *     errors: {
                 *         typeName: ["錯誤內容"]
                 *     }
                 * }
                 */
                if (responseData?.errors) {
                    return Object.values(responseData.errors)
                        .flat()
                        .join('、');
                }

                if (responseData?.message) {
                    return responseData.message;
                }

                if (error.message) {
                    return error.message;
                }

                return defaultMessage;
            },

            /*
             * GET /viewstype
             */
            async fetchTypes() {
                this.loading = true;
                this.errorMessage = '';

                try {
                    const response = await axios.get(viewTypeApi);

                    const data = this.getResponseData(response);

                    if (!Array.isArray(data)) {
                        throw new Error('API 回傳格式錯誤，data 必須是陣列');
                    }

                    /*
                     * 使用展開運算子複製資料，
                     * 避免 sort() 直接修改 API 原始陣列。
                     */
                    this.types = [...data].sort(function(a, b) {
                        return Number(a.id) - Number(b.id);
                    });
                } catch (error) {
                    console.error('取得景點類型失敗：', error);

                    this.errorMessage = this.getErrorMessage(
                        error,
                        '無法取得景點類型列表'
                    );
                } finally {
                    this.loading = false;
                }
            },

            /*
             * POST /viewstype
             */
            async createType() {
                this.clearMessages();

                const typeName = this.createForm.typeName.trim();

                if (!typeName) {
                    this.errorMessage = '請輸入類型名稱';
                    return;
                }

                /*
                 * 前端先檢查 typeName 是否重複。
                 * 後端仍然必須使用 unique 驗證。
                 */
                const isDuplicate = this.types.some(function(item) {
                    return String(item.typeName)
                        .trim()
                        .toLowerCase() === typeName.toLowerCase();
                });

                if (isDuplicate) {
                    this.errorMessage = '類型名稱不能重複';
                    return;
                }

                this.creating = true;

                try {
                    const newId = this.nextAvailableId;

                    const response = await axios.post(
                        viewTypeApi, {
                            id: newId,
                            typeName: typeName
                        }
                    );

                    console.log('新增結果：', response.data);

                    this.createForm.typeName = '';

                    this.successMessage =
                        `新增成功，新的 ID 為 ${newId}`;

                    await this.fetchTypes();
                } catch (error) {
                    console.error('新增景點類型失敗：', error);

                    /*
                     * 發生錯誤時重新取得資料，
                     * 避免其他使用者剛好新增相同 ID。
                     */
                    await this.fetchTypes();

                    this.errorMessage = this.getErrorMessage(
                        error,
                        '新增景點類型失敗'
                    );
                } finally {
                    this.creating = false;
                }
            },

            startEdit(item) {
                this.clearMessages();

                /*
                 * ID 只用來指定更新哪一筆資料，
                 * 不提供 ID 編輯欄位。
                 */
                this.editingId = Number(item.id);

                this.editForm = {
                    typeName: item.typeName ?? ''
                };
            },

            cancelEdit() {
                this.editingId = null;

                this.editForm = {
                    typeName: ''
                };
            },

            /*
             * PUT /viewstype/{id}
             *
             * Request Body 只傳 typeName，
             * 不傳送也不修改 id。
             */
            async updateType() {
                this.clearMessages();

                if (this.editingId === null) {
                    this.errorMessage = '找不到要更新的資料';
                    return;
                }

                const typeName = this.editForm.typeName.trim();

                if (!typeName) {
                    this.errorMessage = '請輸入類型名稱';
                    return;
                }

                /*
                 * 檢查其他資料是否已有相同 typeName。
                 */
                const isDuplicate = this.types.some((item) => {
                    return (
                        Number(item.id) !== this.editingId &&
                        String(item.typeName)
                        .trim()
                        .toLowerCase() === typeName.toLowerCase()
                    );
                });

                if (isDuplicate) {
                    this.errorMessage = '類型名稱不能重複';
                    return;
                }

                this.updating = true;

                try {
                    const response = await axios.put(
                        `${viewTypeApi}/${encodeURIComponent(this.editingId)}`, {
                            typeName: typeName
                        }
                    );

                    console.log('更新結果：', response.data);

                    this.successMessage = '景點類型更新成功';

                    this.cancelEdit();

                    await this.fetchTypes();
                } catch (error) {
                    console.error('更新景點類型失敗：', error);

                    this.errorMessage = this.getErrorMessage(
                        error,
                        '更新景點類型失敗'
                    );
                } finally {
                    this.updating = false;
                }
            },

            /*
             * DELETE /viewstype/{id}
             */
            async deleteType(item) {
                this.clearMessages();

                const confirmed = window.confirm(
                    `確定要刪除 ID ${item.id}「${item.typeName}」嗎？`
                );

                if (!confirmed) {
                    return;
                }

                this.deletingId = Number(item.id);

                try {
                    const response = await axios.delete(
                        `${viewTypeApi}/${encodeURIComponent(item.id)}`
                    );

                    console.log('刪除結果：', response.data);

                    this.successMessage = '景點類型刪除成功';

                    if (this.editingId === Number(item.id)) {
                        this.cancelEdit();
                    }

                    await this.fetchTypes();
                } catch (error) {
                    console.error('刪除景點類型失敗：', error);

                    this.errorMessage = this.getErrorMessage(
                        error,
                        '刪除景點類型失敗'
                    );
                } finally {
                    this.deletingId = null;
                }
            },

            formatDate(dateString) {
                if (!dateString) {
                    return '—';
                }

                const date = new Date(dateString);

                if (Number.isNaN(date.getTime())) {
                    return dateString;
                }

                return new Intl.DateTimeFormat('zh-TW', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).format(date);
            }
        },

        mounted() {
            /*
             * Blade 頁面載入完成後，
             * 使用 Axios 呼叫 GET API。
             */
            this.fetchTypes();
        }
    };

    Vue.createApp(App).mount('#viewTypeApp');
</script>

@endsection