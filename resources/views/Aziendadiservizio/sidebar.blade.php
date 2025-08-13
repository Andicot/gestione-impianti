<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
         data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
         data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
         data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
         data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">

            <div class="menu-item pt-5">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">AZIENDA SERVIZIO
                    @if(env('APP_ENV')=='local')
                        {{\App\Models\AziendaServizio::first()->id}}
                        @endif
                    </span>
                </div>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\DashboardController::class,'show'])}}">
                  <span class="menu-icon">
												<i class="ki-duotone ki-element-11 fs-2">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
													<span class="path4"></span>
												</i>
											</span>
                    <span class="menu-title">Dashboards</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{action([\App\Http\Controllers\Aziendadiservizio\ImpiantoController::class,'index'])}}">
                    <span class="menu-icon">
                              <i class="ki-duotone ki-menu fs-2">
 <span class="path1"></span>
 <span class="path2"></span>
 <span class="path3"></span>
 <span class="path4"></span>
</i>
                            </span>
                    <span class="menu-title">Impianti</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{action([\App\Http\Controllers\Aziendadiservizio\AmministratoreController::class,'index'])}}">
                    <span class="menu-icon">
                                <i class="ki-duotone ki-profile-user fs-2">
                                 <span class="path1"></span>
                                 <span class="path2"></span>
                                 <span class="path3"></span>
                                 <span class="path4"></span>
                                </i>
                            </span>
                    <span class="menu-title">Amministratori</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{action([\App\Http\Controllers\Aziendadiservizio\ConcentratoreController::class,'index'])}}">
                    <span class="menu-icon">
                               <i class="ki-duotone ki-technology fs-2">
                                 <span class="path1"></span>
                                 <span class="path2"></span>
                                 <span class="path3"></span>
                                 <span class="path4"></span>
                                 <span class="path5"></span>
                                 <span class="path6"></span>
                                 <span class="path7"></span>
                                 <span class="path8"></span>
                                 <span class="path9"></span>
                                </i>
                            </span>
                    <span class="menu-title">Concentratori</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{action([\App\Http\Controllers\Aziendadiservizio\DispositivoMisuraController::class,'index'])}}">
                    <span class="menu-icon">
                             <i class="ki-duotone ki-celsius fs-2">
 <span class="path1"></span>
 <span class="path2"></span>
</i>
                            </span>
                    <span class="menu-title">Dispositivi Misura</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link" href="{{action([\App\Http\Controllers\Aziendadiservizio\ResponsabileImpiantoController::class,'index'])}}">
                    <span class="menu-icon">
<i class="ki-duotone ki-badge fs-2">
 <span class="path1"></span>
 <span class="path2"></span>
 <span class="path3"></span>
 <span class="path4"></span>
 <span class="path5"></span>
</i>
                            </span>
                    <span class="menu-title">Responsabili Impianto</span>
                </a>
            </div>

        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
@if(false)
<!--begin::Footer-->
<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
    <a href="https://preview.keenthemes.com/html/metronic/docs"
       class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100"
       data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
       title="200+ in-house components and 3rd-party plugins">
        <span class="btn-label">Docs & Components</span>
        <i class="ki-duotone ki-document btn-icon fs-2 m-0">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </a>
</div>
<!--end::Footer-->
    @endif
