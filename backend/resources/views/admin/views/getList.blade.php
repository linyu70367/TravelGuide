@if(!empty($views))
@foreach($views as $view)
<tr>
    <td class="col-1 text-center border border-dark align-middle">
        <input type="checkbox" name="id[]" value="{{ $view->id }}" class="form-check-input border border-dark">
    </td>
    <td class="col-1 text-center border border-dark align-middle">{{ $view->name }}</td>
    <td class="col-1 text-center border border-dark align-middle">{{ $view->types->typeName }}</td>
    <td class="col-1 text-center border border-dark align-middle">{{ $view->city }}{{ $view->town }}{{ $view->address }}</td>
    <td class="col-2 text-center border border-dark align-middle scroll-y">
        <div class="scroll-y">
            {{ $view->brief }}
        </div>
    </td>
    <td class="col-2 text-center border border-dark align-middle">
        <div class="scroll-y">
            {{ $view->content }}
        </div>
    </td>
    <td class="col-1 text-center border border-dark align-middle">{{ $view->tel }}</td>
    <td class="col-1 text-center border border-dark align-middle">
        @if(!empty($view->imgs))
        <a href="/images/views/{{  $view->imgs[0]->imgSrc }}" data-lightbox="景點" data-title="{{ $view->name }}">
            <img src="/images/views/S/{{ $view->imgs[0]->imgSrc }}">
        </a>
        @endif
    </td>
    <td class="col-1 text-center border border-dark align-middle">{{ $view->like }}</td>
    <td class="col-1 text-center border border-dark align-middle">
        <a href="edit/{{ $view->id }}" class="btn btn-warning">修改</a>
    </td>
</tr>
@endforeach
@endif