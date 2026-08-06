@extends('admin.layout')
@section('title','會員管理')
@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="card">

            <div class="card-header">
                <div class="display-5  fw-900 text-center">會員管理</div>
            </div>

            <div class="card-body">
                <form action="delete" method="post" name="form1" id="form1">
                    @csrf
                    <table class="table bolder border-dark">
                        <tr class="table table-info">
                            <td class="col-1 text-center border border-dark">
                                <input type="checkbox" id="all" class="form-check-input border border-dark">
                            </td>
                            <td class="col-1 text-center border border-dark">id</td>
                            <td class="col-2 text-center border border-dark">會員名稱</td>
                            <td class="col-2 text-center border border-dark">電子信箱</td>
                            <td class="col-1 text-center border border-dark">狀態</td>
                            <td class="col-2 text-center border border-dark">建立時間</td>
                            <td class="col-2 text-center border border-dark">更新時間</td>
                            <td class="col-1 text-center border border-dark">修改/刪除</td>
                        </tr>
                        <tbody id="list">
                            @include("admin.member.getList")
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="card-footer d-flex justify-content-center mt-3">
                {{ $members->links() }}
            </div>
        </div>
    </div>
</div>

@endsection