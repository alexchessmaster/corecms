<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/AdminLTE-3.2.0/dist/img/user0-160x160.png" class="img-circle elevation-2"
                alt="User Image">
        </div>
        <div class="info">
            <a href="/" class="d-block">{{ Auth::check() ? Auth::user()->email : '' }}</a>
        </div>
    </div>

    {{-- <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div> --}}

    <!-- Sidebar Menu -->
    
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            
            <!-- Dashboard and Analytics -->
            <li class="nav-item">
                <a href="{{ env('APP_URL') }}" class="nav-link" target="_blank">
                    <i class="nav-icon fas fa-desktop"></i>
                    <p>
                        Website
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/url-logs/statistics" class="nav-link">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p>
                        URL Logs Statistics
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/url-logs" class="nav-link">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>
                        URL Logs
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <!-- Content Management -->
            <li class="nav-item">
                <a href="/admin/books" class="nav-link">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Books
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/book_genres" class="nav-link">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Books Genres
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/articles" class="nav-link">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p>
                        Articles
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/categories" class="nav-link">
                    <i class="nav-icon fas fa-folder"></i>
                    <p>
                        Articles Categories
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/tags" class="nav-link">
                    <i class="nav-icon fas fa-tag"></i>
                    <p>
                       Articles Tags
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/menus" class="nav-link">
                    <i class="nav-icon fas fa-list-ul"></i>
                    <p>
                        Menus
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/pages" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>
                        Pages
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <!-- Design and Customization -->
            <li class="nav-item">
                <a href="/admin/widgets" class="nav-link">
                    <i class="nav-icon fas fa-th-large"></i>
                    <p>
                        Widgets
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/fields" class="nav-link">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>
                        Fields
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <!-- Localization -->
            <li class="nav-item">
                <a href="/admin/translation-texts" class="nav-link">
                    <i class="nav-icon fas fa-globe"></i>
                    <p>
                        Translation Texts
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <!-- Site Administration -->
            <li class="nav-item">
                <a href="/admin/users" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Users
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/settings" class="nav-link">
                    <i class="nav-icon fas fa-cog"></i>
                    <p>
                        Settings
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/redirects" class="nav-link">
                    <i class="nav-icon fas fa-directions"></i>
                    <p>
                        Redirects
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <!-- File Management -->
            <li class="nav-item">
                <a href="/admin/upload" class="nav-link">
                    <i class="nav-icon fas fa-upload"></i>
                    <p>
                        Upload
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- /.sidebar-menu -->
</div>