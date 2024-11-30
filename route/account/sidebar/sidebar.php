<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <img src="logo/main.png" class="logo-src" />
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <?php 
                        $role = $account[0]['role_id'];
                        $view = $_GET['view'];
                        if($role == 1) {
                 ?>
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="?view=HOME" class="mm-active">
                        <i class="fa fa-tachometer"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="?view=PATIENT">
                        <i class="fa fa-calendar"></i>
                        Patient Management
                    </a>
                </li>
                <li>
                    <a href="?view=SCHEDULING">
                        <i class="fa fa-calendar"></i>
                        Appointment Scheduling
                    </a>
                </li>
                <li>
                    <a href="?view=REPORTS">
                        <i class="fa fa-calendar"></i>
                        Reports
                    </a>
                </li>
                <?php } else if($role == 2) { ?>
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="?view=HOME" class="mm-active">
                        <i class="fa fa-tachometer"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="?view=PATIENT">
                        <i class="fa fa-calendar"></i>
                        Patient Management
                    </a>
                </li>
                <li>
                    <a href="?view=SCHEDULING">
                        <i class="fa fa-calendar"></i>
                        Appointment Scheduling
                    </a>
                </li>
                <li>
                    <a href="?view=REPORTS">
                        <i class="fa fa-calendar"></i>
                        Reports
                    </a>
                </li>
                <?php } else { ?>
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="?view=HOME" class="mm-active">
                        <i class="fa fa-tachometer"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="?view=BOOK">
                        <i class="fa fa-calendar"></i>
                        Book Appointment
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>