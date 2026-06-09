<?php
$menu_fetch= [];
foreach($PermittedMenuList as $menuList){
  $menu_fetch[] = $menuList->menu_id;
}
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme bg-gradient-start-2">
    <div class="app-brand demo">
        <a href="{{ url('production-lineup/dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ url('assets_new/img/branding/crm_logo.png') }}" width="60px" alt="">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Factory</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6" />
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38" />
            </svg>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item  {{ $menu == 'dashboard' ? 'active' : '' }}">
            <a href="{{ URL('production-lineup/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="Dashboard">Dashboard </div>
            </a> 
        </li>
        
        @php
            $menuArr1 = ['production-setup', 'production-setup-all', 'production-setup-approval'];
            $menuSubArr1 = ['cell-cutting', 'cell-cutting-all', 'cell-cutting-approval-list', 'cell-cutting-detailed'];
            $menuSubArr2 = ['stringer-op', 'stringer-op-all', 'stringer-op-approval-list', 'stringer-op-detailed'];
            $menuSubArr3 = ['stringer-qc', 'stringer-qc-all', 'stringer-qc-approval-list', 'stringer-qc-detailed'];
            $menuSubArr4 = ['stringer-rework', 'stringer-rework-all', 'stringer-rework-approval-list', 'stringer-rework-detailed'];
            $menuSubArr5 = ['glass-feeding', 'glass-feeding-all', 'glass-feeding-approval-list', 'glass-feeding-detailed'];
            
            $menuArr = array_merge($menuArr1,$menuSubArr1,$menuSubArr2,$menuSubArr3,$menuSubArr4,$menuSubArr5)
        @endphp
        <?php if(count(array_intersect($menuArr, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr) ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                <div data-i18n="Production Set up">Production Set up</div>
            </a>

            <ul class="menu-sub">
                <?php if(in_array('production-setup',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'production-setup' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/production-setup') }}" class="menu-link">
                        <div data-i18n="Production Set up">Production Set up</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('production-setup-all',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'production-setup-all' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/production-setup-all') }}" class="menu-link">
                        <div data-i18n="All Batch Production Data">All Batch Production Data</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('production-setup-approval',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'production-setup-approval' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/production-setup/approval-list') }}" class="menu-link">
                        <div data-i18n="Production Approval">Production Approval</div>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(count(array_intersect($menuSubArr1, $menu_fetch))): ?>
                <li class="menu-item {{ in_array($menu, $menuSubArr1) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                        <div data-i18n="Cell Cutting">Cell Cutting</div>
                    </a>
                    <ul class="menu-sub">
                        <?php if(in_array('cell-cutting',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'cell-cutting' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/cell-cutting') }}" class="menu-link">
                                <div data-i18n="Request Page"> Request Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('cell-cutting-all',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'cell-cutting-all' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/cell-cutting-all') }}" class="menu-link">
                                <div data-i18n="All List Page"> All List Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('cell-cutting-approval-list',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'cell-cutting-approval-list' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/cell-cutting-approval-list') }}" class="menu-link">
                                <div data-i18n="Approval Page">Approval Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(in_array('cell-cutting-detailed',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'cell-cutting-detailed' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/cell-cutting-detailed') }}" class="menu-link">
                                <div data-i18n="Cell cutting Detailed Report">Cell cutting Detailed Report</div>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                
                <?php if(count(array_intersect($menuSubArr2, $menu_fetch))): ?>
                <li class="menu-item {{ in_array($menu, $menuSubArr2) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                        <div data-i18n="Stringer OP">Stringer OP</div>
                    </a>
                    <ul class="menu-sub">
                        <?php if(in_array('stringer-op',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-op' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-op') }}" class="menu-link">
                                <div data-i18n="Request Page"> Request Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('stringer-op-all',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-op-all' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-op-all') }}" class="menu-link">
                                <div data-i18n="All List Page"> All List Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('stringer-op-approval-list',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-op-approval-list' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-op-approval-list') }}" class="menu-link">
                                <div data-i18n="Approval Page">Approval Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(in_array('stringer-op-detailed',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-op-detailed' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-op-detailed') }}" class="menu-link">
                                <div data-i18n="Detailed Report">Detailed Report</div>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if(count(array_intersect($menuSubArr3, $menu_fetch))): ?>
                <li class="menu-item {{ in_array($menu, $menuSubArr3) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                        <div data-i18n="Stringer QC">Stringer QC</div>
                    </a>
                    <ul class="menu-sub">
                        <?php if(in_array('stringer-qc',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-qc' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-qc') }}" class="menu-link">
                                <div data-i18n="Request Page"> Request Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('stringer-qc-all',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-qc-all' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-qc-all') }}" class="menu-link">
                                <div data-i18n="All List Page"> All List Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('stringer-qc-approval-list',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-qc-approval-list' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-qc-approval-list') }}" class="menu-link">
                                <div data-i18n="Approval Page">Approval Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(in_array('stringer-qc-detailed',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-qc-detailed' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-qc-detailed') }}" class="menu-link">
                                <div data-i18n="Detailed Report">Detailed Report</div>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if(count(array_intersect($menuSubArr4, $menu_fetch))): ?>
                <li class="menu-item {{ in_array($menu, $menuSubArr4) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                        <div data-i18n="String Re-Work">String Re-Work</div>
                    </a>
                    <ul class="menu-sub">
                        <?php if(in_array('stringer-rework',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-rework' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-rework') }}" class="menu-link">
                                <div data-i18n="Request Page"> Request Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('stringer-rework-all',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-rework-all' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-rework-all') }}" class="menu-link">
                                <div data-i18n="All List Page"> All List Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('stringer-rework-approval-list',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-rework-approval-list' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-rework-approval-list') }}" class="menu-link">
                                <div data-i18n="Approval Page">Approval Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(in_array('stringer-rework-detailed',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'stringer-rework-detailed' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/stringer-rework-detailed') }}" class="menu-link">
                                <div data-i18n="Detailed Report">Detailed Report</div>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if(count(array_intersect($menuSubArr5, $menu_fetch))): ?>
                <li class="menu-item {{ in_array($menu, $menuSubArr5) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                        <div data-i18n="Glass Feeding">Glass Feeding</div>
                    </a>
                    <ul class="menu-sub">
                        <?php if(in_array('glass-feeding',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'glass-feeding' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/glass-feeding') }}" class="menu-link">
                                <div data-i18n="Request Page"> Request Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('glass-feeding-all',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'glass-feeding-all' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/glass-feeding-all') }}" class="menu-link">
                                <div data-i18n="All List Page"> All List Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('glass-feeding-approval-list',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'glass-feeding-approval-list' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/glass-feeding-approval-list') }}" class="menu-link">
                                <div data-i18n="Approval Page">Approval Page</div>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if(in_array('glass-feeding-detailed',$menu_fetch)): ?>
                        <li class="menu-item {{ $menu == 'glass-feeding-detailed' ? 'active' : '' }}">
                            <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/glass-feeding-detailed') }}" class="menu-link">
                                <div data-i18n="Detailed Report">Detailed Report</div>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </li>
        <?php endif; ?>
         @php
            $menuArr1 = ['bushing-setup', 'bushing-setup-all', 'bushing-details', 'bushing-damage-report'];
        @endphp
        
        <?php if(count(array_intersect($menuArr1, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-account-tie-outline"></i>
                <div data-i18n="Layout Set up">Layout Set up</div>
            </a>
            <ul class="menu-sub">
                <?php if(in_array('bushing-setup',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'bushing-setup' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/bushing-setup') }}" class="menu-link">
                        <div data-i18n="Layout Operator">Layout Operator</div>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if(in_array('bushing-details',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'bushing-details'   ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/bushing-setup/bushing-details') }}" class="menu-link">
                        <div data-i18n="Layout Details">Layout Details</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('bushing-setup-all',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'bushing-setup-all' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/bushing-setup/all-list') }}" class="menu-link">
                        <div data-i18n="Layout All Lists">Layout All Lists</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('bushing-damage-report',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'bushing-damage-report'   ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/bushing-setup/bushing-damage-report') }}" class="menu-link">
                        <div data-i18n="Damage Report">Damage Report</div>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>


        @php
            $menuArr1 = ['elqc-setup','elqc-all','elqc-rework','elqc-damage'];
        @endphp
        
        <?php if(count(array_intersect($menuArr1, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-file-outline"></i>
                <div data-i18n="EL & QC">EL & QC</div>
            </a>
            <?php if(in_array('elqc-setup',$menu_fetch)): ?><ul class="menu-sub">
                
                <li class="menu-item {{ $menu == 'elqc-setup' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/el_qc') }}" class="menu-link">
                        <div data-i18n="EL & QC Request">EL & QC Request</div>
                    </a>
                </li>
            </ul><?php endif; ?>
            <?php if(in_array('elqc-all',$menu_fetch)): ?><ul class="menu-sub">
                <li class="menu-item {{ $menu == 'elqc-all' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/el_qc-all') }}" class="menu-link">
                        <div data-i18n="EL & QC All Request">EL & QC All Request</div>
                    </a>
                </li>
            </ul><?php endif; ?>
            <?php if(in_array('elqc-rework',$menu_fetch)): ?><ul class="menu-sub">
                <li class="menu-item {{ $menu == 'elqc-rework' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/el_qc_rework') }}" class="menu-link">
                        <div data-i18n="EL & QC Rework">EL & QC Rework</div>
                    </a>
                </li>
            </ul><?php endif; ?>
            <?php if(in_array('elqc-damage',$menu_fetch)): ?><ul class="menu-sub">
                <li class="menu-item {{ $menu == 'elqc-damage' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/el-qc/el_qc_damage') }}" class="menu-link">
                        <div data-i18n="EL & QC Damage">EL & QC Damage</div>
                    </a>
                </li>
            </ul><?php endif; ?>
        </li>
        <?php endif; ?>
        
        
        @php
         $menuArr1 = ['laminator-op','laminator-rework', 'laminator-damage-report'];
        @endphp

        <?php if(count(array_intersect($menuArr1, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="Laminator OP">Laminator OP</div>
            </a>
            <ul class="menu-sub">
                <?php if(in_array('laminator-op',$menu_fetch)): ?><li class="menu-item {{ $menu == 'laminator-op' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/laminator-op') }}" class="menu-link">
                        <div data-i18n="Laminator OP">Laminator OP</div>
                    </a>
                </li><?php endif; ?>
                <?php if(in_array('laminator-rework',$menu_fetch)): ?><li class="menu-item {{ $menu == 'laminator-rework' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/laminator-op-rework') }}" class="menu-link">
                        <div data-i18n="Laminator ReWork">Laminator ReWork</div>
                    </a>
                </li><?php endif; ?>
                <?php if(in_array('laminator-damage-report',$menu_fetch)): ?><li class="menu-item {{ $menu == 'laminator-damage-report'   ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/laminator-op/laminator-damage-report') }}" class="menu-link">
                        <div data-i18n="Damage Report">Damage Report</div>
                    </a>
                </li><?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
        
        
        @php
         $menuArr1 = ['90deg-qc','90deg-qc-all', '90deg-qc-damage-report'];
        @endphp
        <?php if(count(array_intersect($menuArr1, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="90Degree QC">90Degree QC </div>
            </a>
            
            <ul class="menu-sub">
                <?php if(in_array('90deg-qc',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == '90deg-qc' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/90deg-qc') }}" class="menu-link">
                        <div data-i18n="90Degree QC" >90Degree QC </div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('90deg-qc-all',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == '90deg-qc-all' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/90deg-qc-all') }}" class="menu-link">
                        <div data-i18n="90Degree QC All" >90Degree QC All</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('90deg-qc-damage-report',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == '90deg-qc-damage-report'   ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/90deg-qc/damage-report') }}" class="menu-link">
                        <div data-i18n="Damage Report">Damage Report</div>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
        
        @php
         $menuArr1 = ['dlamination', 'dlamination-damage-report'];
        @endphp
        <?php if(count(array_intersect($menuArr1, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="De-Lamination">De-Lamination</div>
            </a>
            <ul class="menu-sub">
                <?php if(in_array('dlamination',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'dlamination' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/dlamination') }}" class="menu-link">
                        <div data-i18n="De-Lamination Rework" >De-Lamination Rework</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('dlamination-damage-report',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'dlamination-damage-report'   ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/dlamination/damage-report') }}" class="menu-link">
                        <div data-i18n="Damage Report">Damage Report</div>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
        
         @php
         $menuArr1 = ['junctionbox'];
        @endphp
        <?php if(in_array('junctionbox',$menu_fetch)): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active' : '' }}">
            <a href="{{ URL('production-lineup/junctionbox') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="Junction Box">Junction Box </div>
            </a>
        </li>
        <?php endif; ?>
        
        
        
        @php
         $menuArr1 = ['junctionbox-all'];
        @endphp
        <?php if(in_array('junctionbox-all',$menu_fetch)): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active' : '' }}">
            <a href="{{ URL('production-lineup/junctionbox-all') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="Junction Box All">Junction Box All</div>
            </a>
        </li>
        <?php endif; ?>
        
        
        
        @php
         $menuArr1 = ['final-qc'];
        @endphp
        <?php if(in_array('final-qc',$menu_fetch)): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active' : '' }}">
            <a href="{{ URL('production-lineup/final-qc') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="Final QC">Final QC</div>
            </a>
        </li>
        <?php endif; ?>
        
        
        @php
         $menuArr1 = ['final-qc-all'];
        @endphp
        <?php if(in_array('final-qc-all',$menu_fetch)): ?>
        <li class="menu-item {{ in_array($menu, $menuArr1) ? 'active' : '' }}">
            <a href="{{ URL('production-lineup/final-qc-all') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-view-dashboard-outline"></i>
                <div data-i18n="Final QC All">Final QC All</div>
            </a>
        </li>
        <?php endif; ?>

        
        @php
            $menuArr2 = ['approval-module', 'approval-master'];
        @endphp
        <?php if(count(array_intersect($menuArr2, $menu_fetch))): ?>
        <li class="menu-item {{ in_array($menu, $menuArr2) ? 'active open' : '' }}"><!--active open -->
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-database-cog-outline"></i>
                <div>Approval Matrix</div>
                <div class="badge bg-primary rounded-pill ms-auto"></div>
            </a>
            <ul class="menu-sub">
                <?php if(in_array('approval-module',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'approval-module' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ url('approval-matrix/approval-module') }}" class="menu-link">
                        <div>Approval Modules</div>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(in_array('approval-master',$menu_fetch)): ?>
                <li class="menu-item {{ $menu == 'approval-master' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ url('approval-matrix/approval-master') }}" class="menu-link">
                        <div>Approval Master</div>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </li>
        <?php endif; ?>
        
        
        
        @php
            $menuArr3 = ['elqc-availRawMat', 'elqc-consumeRawMat', '90deg-availRawMat', '90deg-consumeRawMat', 'jb-availRawMat', 'jb-consumeRawMat', 'fqc-availRawMat', 'fqc-consumeRawMat'];
        @endphp 

        <li class="menu-item {{ in_array($menu, $menuArr3) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                <i class="menu-icon tf-icons mdi mdi-office-building"></i>
                <div data-i18n="Raw Material Report">Raw Material Report</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ $menu == 'elqc-availRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/pending-raw-material-elqc') }}" class="menu-link">
                        <div data-i18n="ELQC Pending Raw Material">ELQC Pending Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == 'elqc-consumeRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/consumed-raw-material-elqc') }}" class="menu-link">
                        <div data-i18n="ELQC Consumed Raw Material">ELQC Consumed Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == '90deg-availRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/pending-raw-material-90deg') }}" class="menu-link">
                        <div data-i18n="90 Degree QC Pending Raw Material">90 Degree QC Pending Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == '90deg-consumeRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/consumed-raw-material-90deg') }}" class="menu-link">
                        <div data-i18n="90 Degree QC Consumed Raw Material">90 Degree QC Consumed Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == 'jb-availRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/pending-raw-material-jb') }}" class="menu-link">
                        <div data-i18n="Junction Box Pending Raw Material">Junction Box Pending Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == 'jb-consumeRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/consumed-raw-material-jb') }}" class="menu-link">
                        <div data-i18n="Junction Box Consumed Raw Material">Junction Box Consumed Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == 'fqc-availRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/pending-raw-material-fqc') }}" class="menu-link">
                        <div data-i18n="Final QC Pending Raw Material">Final QC Pending Raw Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == 'fqc-consumeRawMat' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/raw-material-report/consumed-raw-material-fqc') }}" class="menu-link">
                        <div data-i18n="Final QC Consumed Raw Material">Final QC Consumed Raw Material</div>
                    </a>
                </li>
                {{-- <li class="menu-item {{ $menu == 'uoms' ? 'active' : '' }}">
                            <a href="{{ URL('production-lineup/uoms') }}" class="menu-link">
                                <div data-i18n="UOM">UOM</div>
                            </a>
                        </li> --}}
            </ul>
        </li>
        
        

        {{-- master menus start --}}
        
        @php
            $menuArr3 = ['material', 'uoms', 'pallete'];
        @endphp

        <?php if(in_array('material',$menu_fetch)): ?>
        <li class="menu-item {{ in_array($menu, $menuArr3) ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle waves-effect">
                <i class="menu-icon tf-icons mdi mdi-office-building"></i>
                <div data-i18n="Master">Master</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ $menu == 'material' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/material') }}" class="menu-link">
                        <div data-i18n="Material"> Material</div>
                    </a>
                </li>
                <li class="menu-item {{ $menu == 'pallete' ? 'active' : '' }}">
                    <a onclick="localStorage.removeItem('activeTab');" href="{{ URL('production-lineup/pallete') }}" class="menu-link">
                        <div data-i18n="Pallete"> Pallete</div>
                    </a>
                </li>
                {{-- <li class="menu-item {{ $menu == 'uoms' ? 'active' : '' }}">
                            <a href="{{ URL('production-lineup/uoms') }}" class="menu-link">
                                <div data-i18n="UOM">UOM</div>
                            </a>
                        </li> --}}
            </ul>
        </li>
        <?php endif; ?>

        {{-- master menus end --}}
    </ul>
</aside>
