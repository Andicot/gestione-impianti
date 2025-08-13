<div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
     id="kt_app_header_menu"
     data-kt-menu="true"
>
    <!--begin:Menu item-->
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2"><!--begin:Menu link--><span class="menu-link"><span
                    class="menu-title">Apps</span><span class="menu-arrow d-lg-none"></span></span><!--end:Menu link--><!--begin:Menu sub-->
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
            <!--begin:Menu item-->
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                <!--begin:Menu link--><span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-sms fs-2"><span class="path1"></span><span
                                    class="path2"></span></i></span><span class="menu-title">Inbox</span><span class="menu-arrow"></span></span>
                <!--end:Menu link--><!--begin:Menu sub-->
                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px"><!--begin:Menu item-->
                    <div class="menu-item"><!--begin:Menu link--><a class="menu-link" href="?page=apps/inbox/listing"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">Messages</span><span class="menu-badge"><span
                                        class="badge badge-light-success">3</span></span></a><!--end:Menu link--></div><!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item"><!--begin:Menu link--><a class="menu-link" href="?page=apps/inbox/compose"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">Compose</span></a><!--end:Menu link--></div>
                    <!--end:Menu item--><!--begin:Menu item-->
                    <div class="menu-item"><!--begin:Menu link--><a class="menu-link" href="?page=apps/inbox/reply"><span class="menu-bullet"><span
                                        class="bullet bullet-dot"></span></span><span class="menu-title">View & Reply</span></a><!--end:Menu link--></div>
                    <!--end:Menu item--></div><!--end:Menu sub--></div><!--end:Menu item-->

            <!--begin:Menu item-->
            <div class="menu-item"><!--begin:Menu link--><a class="menu-link" href="?page=apps/calendar">
                    <span class="menu-title">Calendar</span></a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
        </div>
        <!--end:Menu sub-->
    </div>
    <!--end:Menu item-->
    <div class="menu-item">
        <a class="menu-link" href="#">
        <span class="menu-icon">
            <i class="ki-duotone ki-abstract-13 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
            <span class="menu-title">Con Icona</span>
        </a>
    </div>
    <div class="menu-item">
        <a class="menu-link "
           href="#">
            <span class="menu-title">Senza Icona</span>
        </a>
    </div>
    <!--begin:Menu item-->
    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
         class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
        <!--begin:Menu link-->
        <span class="menu-link">
                <span class="menu-title">Registri</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
        <!--end:Menu link-->
        <!--begin:Menu sub-->
        <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
            <!--begin:Menu item-->
            <div class="menu-item">
                <div class="menu-item">
                    <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\RegistriController::class,'index'],'login')}}">
                        <span class="menu-title">Login</span>
                    </a>
                </div>
                @if(\Illuminate\Support\Facades\Auth::id()==1)
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\RegistriController::class,'index'],'backup-db')}}">
                            <span class="menu-title">Backup DB</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link" href="/backend/log-viewer/logs" target="_blank">
                            <span class="menu-title">Log viewer</span>
                        </a>
                    </div>
                @endif

            </div>
            <!--end:Menu item-->
        </div>
        <!--end:Menu sub-->
    </div>
    <!--end:Menu item-->

</div>
