@if(!empty($members))
@foreach($members as $member)
<tr>
    <td class="col-1 text-center border border-dark align-middle">
        <input type="checkbox" name="id[]" value="{{ $member->id }}" class="form-check-input border border-dark">
    </td>
    <td class="col-1 text-center border border-dark align-middle">{{ $member->id }}</td>
    <td class="col-1 text-center border border-dark align-middle">{{ $member->memberName }}</td>
    <td class="col-2 text-center border border-dark align-middle">{{ $member->email }}</td>
    <td class="col-1 text-center border border-dark align-middle">{{ $member->status }}</td>
    <td class="col-2 text-center border border-dark align-middle">{{ $member->created_at }}</td>
    <td class="col-2 text-center border border-dark align-middle">{{ $member->updated_at }}</td>
    <td class="col-1 text-center border border-dark align-middle">
        <a href="edit/{{ $member->id }}" class="btn btn-success">修改</a>
        <a href="#" class="btn btn-danger" onclick="doDelete('form1')">刪除</a>
    </td>
</tr>
@endforeach
@endif