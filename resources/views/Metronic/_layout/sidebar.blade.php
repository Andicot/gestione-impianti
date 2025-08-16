<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true"
     data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
     data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="../../demo1/dist/index.html">
            <img alt="Logo" src="assets/media/logos/default-dark.svg"
                 class="h-25px app-sidebar-logo-default"/>
            <img alt="Logo" src="assets/media/logos/default-small.svg"
                 class="h-20px app-sidebar-logo-minimize"/>
        </a>
        <!--end::Logo image-->

        <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate {{Auth::user()->getExtra('aside')=='on'?'active':''}}"
             data-kt-toggle="true"
             @if(Auth::user()->getExtra('aside')=='on')
                 data-kt-toggle-state="active"
             @endif
             data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
             data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
             data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            @php($ruoloOperatore=\App\Enums\RuoliOperatoreEnum::tryFrom(Auth::user()->ruolo))
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                 data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">{{$ruoloOperatore->testo()}}</span>
                    </div>
                    <!--end:Menu content-->
                </div>

                <!-- Dashboard - Sempre visibile -->
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
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                @if(in_array('azienda_servizio',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\AziendaServizioController::class,'index'])}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                            <span class="menu-title">{{ucwords(\App\Models\AziendaServizio::NOME_PLURALE)}}</span>
                        </a>
                    </div>
                @endif

                @if(in_array('amministratore',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\AmministratoreController::class,'index'])}}">
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
                @endif

                @if(in_array('impianto',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\ImpiantoController::class,'index'])}}">
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
                @endif

                @if(in_array('concentratore',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\ConcentratoreController::class,'index'])}}">
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
                @endif

                @if(in_array('dispositivo_misura',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\DispositivoMisuraController::class,'index'])}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-celsius fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Dispositivi Misura</span>
                        </a>
                    </div>
                @endif

                @if(in_array('responsabile_impianto',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\ResponsabileImpiantoController::class,'index'])}}">
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
                @endif

                @if(in_array('mio_condominio',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-home fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Il Mio Condominio</span>
                        </a>
                    </div>
                @endif

                @if(in_array('bollettino',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-bill fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                            <span class="menu-title">Bollettini</span>
                        </a>
                    </div>
                @endif

                @if(in_array('pagamento',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-euro fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                            <span class="menu-title">Pagamenti</span>
                        </a>
                    </div>
                @endif

                @if(in_array('lettura',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart-line fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Letture</span>
                        </a>
                    </div>
                @endif

                @if(in_array('miei_impianti',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-menu fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                            <span class="menu-title">I Miei Impianti</span>
                        </a>
                    </div>
                @endif

                @if(in_array('concentratore_gestiti',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
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
                            <span class="menu-title">Concentratori Gestiti</span>
                        </a>
                    </div>
                @endif

                @if(in_array('anomalia',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-warning-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                            <span class="menu-title">Anomalie</span>
                        </a>
                    </div>
                @endif

                @if(in_array('manutenzione',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-wrench fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Manutenzioni</span>
                        </a>
                    </div>
                @endif

                @if(in_array('mie_bollette',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-bill fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                            <span class="menu-title">Le Mie Bollette</span>
                        </a>
                    </div>
                @endif

                @if(in_array('miei_consumi',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart-line fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">I Miei Consumi</span>
                        </a>
                    </div>
                @endif

                @if(in_array('storico_letture',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{route('lettura-consumo.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-text-align-left fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                             <span class="path4"></span>
                            </i>
                        </span>
                            <span class="menu-title">Storico Letture</span>
                        </a>
                    </div>
                @endif

                @if(in_array('grafici',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="#">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-graph-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Grafici</span>
                        </a>
                    </div>
                @endif

                @if(in_array('documento',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\DocumentoController::class,'index'])}}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-file fs-2">
                             <span class="path1"></span>
                             <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Documenti</span>
                        </a>
                    </div>
                @endif

                @if(in_array('importazione',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{action([\App\Http\Controllers\Backend\ImportazioneController::class,'index'])}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-file-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                            <span class="menu-title">Importazioni</span>
                        </a>
                    </div>
                @endif

                @if(in_array('ticket',$ruoloOperatore->menu_navigazione()) || in_array('ticket_tecnico',$ruoloOperatore->menu_navigazione()) || in_array('comunicazione',$ruoloOperatore->menu_navigazione()))
                    <div class="menu-item">
                        <a class="menu-link" href="{{route('tickets.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-message-text fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                            <span class="menu-title">
                            @if(in_array('ticket_tecnico',$ruoloOperatore->menu_navigazione()))
                                    Tickets Tecnici
                                @elseif(in_array('comunicazione',$ruoloOperatore->menu_navigazione()))
                                    Comunicazioni
                                @else
                                    Tickets
                                @endif
                        </span>
                        </a>
                    </div>
                @endif

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
</div>
<!--end::Sidebar-->
