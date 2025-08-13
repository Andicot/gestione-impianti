<div class="d-none d-md-block">
    <button class="btn btn-sm btn-icon btn-secondary btn-color-gray-700 btn-active-primary"
            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
            data-kt-menu-flip="top-end">
        <i class="bi bi-sort-down fs-3"></i>
    </button>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Ordinamento</div>
        </div>
        @foreach($ordinamenti as $key=>$ordinamento)
            <div class="menu-item px-3">
                <a href="{{Request::url()}}?orderBy={{$key}}" class="menu-link flex-stack px-3">
                    {{$ordinamento['testo']}}
                    @if($key==$orderBy)
                        <i class="fas fa-check ms-2 fs-7"></i>
                    @endif
                </a>
            </div>
        @endforeach
    </div>
</div>
