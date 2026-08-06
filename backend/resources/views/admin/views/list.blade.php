@extends('admin.layout')
@section('title','景點管理')
@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
<style>
    table {
        table-layout: fixed;
        width: 100%;
    }

    .scroll-y {
        max-height: 120px;
        /* 設定你希望的最大高度 */
        overflow-y: auto;
        /* 內容超出高度時顯示垂直滾動條 */
        word-break: break-all;
        /* 避免連續英文/數字撐破容器 */
    }

    .table td img {
        max-width: 100%;
        /* 寬度不超過表格欄位 */
        max-height: 100px;
        /* 限制最大高度，避免把表格列 (tr) 撐得太高 */
        width: auto;
        height: auto;
        object-fit: contain;
        /* 確保圖片維持原本比例 */
        border-radius: 4px;
        /* (可選) 加上微圓角更美觀 */
    }
</style>
<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="display-5  fw-900 text-center">景點管理</div>
            </div>

            <div class="row mt-3 d-flex justify-content-center align-items-center">
                <div class="col-2">
                    <div class="text-center">
                        <select id="typeId" class="form-control border border-dark mt-3" onchange="getList()">
                            <option value="">類別</option>
                            @if (!empty($types))
                            @foreach($types as $data)
                            <option value="{{ $data->id }}">{{ $data->typeName }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <!-- id for js, name for backend -->
                    <div class="text-center">
                        <input type="text" id="keyword"
                            class="form-control mt-3 border border-dark" placeholder="關鍵字..." onkeyup="getList()">
                    </div>
                </div>
                <div class="col-4 text-center">
                    <a href="add" class="btn btn-success">新增</a>
                </div>
                <div class="col-4 text-center">
                    <a href="#" class="btn btn-danger" onclick="doDelete()">刪除</a>
                </div>
            </div>
            <div class="card-body">
                <form action="/admin/views/delete" method="post" name="form1" id="form1">
                    @csrf
                    <table class="table bolder border-dark">
                        <tr class="table table-info">
                            <td class="col-1 text-center border border-dark">
                                <input type="checkbox" id="all" class="form-check-input border border-dark">
                            </td>
                            <td class="col-1 text-center border border-dark">景點名稱</td>
                            <td class="col-1 text-center border border-dark">景點類別</td>
                            <td class="col-1 text-center border border-dark">地址</td>
                            <td class="col-2 text-center border border-dark">簡介</td>
                            <td class="col-2 text-center border border-dark">內容</td>
                            <td class="col-1 text-center border border-dark">電話</td>
                            <td class="col-1 text-center border border-dark">圖片</td>
                            <td class="col-1 text-center border border-dark">瀏覽數</td>
                            <td class="col-1 text-center border border-dark">修改/刪除</td>
                        </tr>
                        <tbody id="list">
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="card-footer d-flex justify-content-center mt-3">
            </div>
            <div class="p-1 mt-1">
            </div>
        </div>
    </div>
</div>
<script>
    // 頁面載入完成時
    $(function() {
        $("#keyword").autocomplete({
            source: []
        });

        // 呼叫api,取得最新消息
        getList();
    });


    function renderList(list) {
        let html = "";
        // length:資料集長度(大小)
        if (list && list.length > 0) {
            list.forEach(function(view) {
                html += `
                    <tr>
                        <td class="col-1 text-center border border-dark align-middle">
                            <input type="checkbox" name="id[]" value="${ view.id }" class="form-check-input border border-dark">
                        </td>
                        <td class="col-1 text-center border border-dark align-middle">${ view.name }</td>
                        <td class="col-1 text-center border border-dark align-middle">${ view.types.typeName }</td>
                        <td class="col-1 text-center border border-dark align-middle">${ view.city }${ view.town }${ view.address }</td>
                        <td class="col-2 text-center border border-dark align-middle scroll-y">
                            <div class="scroll-y">
                                ${ view.brief }
                            </div>
                        </td>
                        <td class="col-2 text-center border border-dark align-middle">
                            <div class="scroll-y">
                                ${ view.content }
                            </div>
                        </td>
                        <td class="col-1 text-center border border-dark align-middle">${ view.tel }</td>
                        <td class="col-1 text-center border border-dark align-middle">
                        ${ view.imgs && view.imgs.length > 0 ?
                        `
                        <a href="/images/views/${view.imgs[0].imgSrc}" data-lightbox="景點" data-title="${view.name}">
                            <img src="/images/views/S/${view.imgs[0].imgSrc}">
                        </a>
                        ` : "無圖片"
                        }
                        </td>
                        <td class="col-1 text-center border border-dark align-middle">${ Number(view.like).toLocaleString() }</td>
                        <td class="col-1 text-center border border-dark align-middle">
                            <a href="edit/${ view.id }" class="btn btn-warning">修改</a>
                        </td>
                    </tr>
                `;
            });
        } else {
            html = `<tr><td colspan='9' class='text-center text-danger'>查無資料</td></tr>`;
        }
        $("#list").html(html);
    }

    function getList() {
        //document:本文(這個網頁), getElementById: 以id取得元素，value:所輸入(或選取)的值
        // var years = document.getElementById("years").value;
        // let keyword = document.getElementById("keyword").value;
        let typeId = $('#typeId').val();
        let keyword = $('#keyword').val();
        axios.get('/api/views/getView', {
            params: {
                typeId: typeId,
                keywords: keyword
            }
        }).then(response => {
            console.log(response);
            let list = response.data.success ? response.data.list : [];
            renderList(list);

            let name = list.map(function(t) {
                return t.name;
            });
            $("#keyword").autocomplete("option", "source", name);

        }).catch(error => {
            console.error(error);
        });

    }

    function doDelete() {
        // 所有name=id[]有被選取
        let ids = $("input[name='id[]']:checked").map(function() {
            /* 
                this: 被選取的checkbox
                val(): 值(jquery取值的方法)
            */
            return $(this).val();
        }).get(); // 轉成javascript的陣列

        // 陣列長度小於等於0(沒有任何資料被選取)
        if (ids.length <= 0) {
            Swal.fire("請選取要刪除的資料");
            return;
        }

        Swal.fire({
            title: "確定刪除?",
            icon: "question",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "確定",
            denyButtonText: "取消"
        }).then((result) => {
            console.log(ids);
            if (result.isConfirmed) {
                // let formElement = document.getElementById('form1');
                // let formData = new FormData(formElement);
                axios.delete('/admin/views/delete', {
                        data: {
                            ids: ids
                        }
                    })
                    .then(function(response) {
                        Swal.fire({
                            title: response.data.message,
                            icon: 'success',
                            confirmButtonText: "確定",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "/admin/views/list";
                            };
                        });
                    })
                    .catch(function(error) {
                        console.log(error);
                        Swal.fire({
                            title: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: "確定",
                        })
                    })
                    .finally(function() {
                        // always executed
                    });
            }
        });
    };
</script>
@endsection