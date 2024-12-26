<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/AdminLTE-3.2.0/dist/img/user0-160x160.png" class="img-circle elevation-2"
                alt="User Image">
        </div>
        <div class="info">
            <a href="/AdminLTE-3.2.0/#" class="d-block">{{ Auth::check() ? Auth::user()->email : '' }}</a>
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

            <li class="nav-item">
                <a href="/" class="nav-link" target="_blank">
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
            <div style="border: 1px gray solid"></div>
            <li class="nav-item">
                <a href="/admin/menus" class="nav-link">
                    <i class="nav-icon fas fa-bars"></i>
                    <p>
                        Menus
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/pages" class="nav-link">
                    <i class="nav-icon fas fa-sticky-note"></i>
                    <p>
                        Pages
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>

            <li class="nav-item">
                <a href="/admin/categories" class="nav-link">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>
                        Categories
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
                <a href="/admin/tags" class="nav-link">
                    <i class="nav-icon fas fa-tags"></i>
                    <p>
                        Tags
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <li class="nav-item">
                <a href="/admin/widgets" class="nav-link">
                    <i class="nav-icon fas fa-th-list"></i>
                    <p>
                        Widgets
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/templates" class="nav-link">
                    <i class="nav-icon fas fa-border-style"></i>
                    <p>
                        Templates
                    </p>
                </a>
            </li>
            <div style="border: 1px gray solid"></div>
            <li class="nav-item">
                <a href="/admin/redirects" class="nav-link">
                    <i class="nav-icon fas fa-directions"></i>
                    <p>
                        Redirects
                    </p>
                </a>
            </li>
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
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                        Settings
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/url-logs" class="nav-link">
                    <i class="nav-icon fas fa-history"></i>
                    <p>
                        URL logs
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/url-logs/statistics" class="nav-link">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Visit statistics
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/upload" class="nav-link" target="_blank">
                    <i class="nav-icon fas fa-upload"></i>
                    <p>
                        Upload
                    </p>
                </a>
            </li>
            
            {{-- <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                        Layout Options
                        <i class="fas fa-angle-left right"></i>
                        <span class="badge badge-info right">6</span>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/top-nav.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Top Navigation</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/top-nav-sidebar.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Top Navigation + Sidebar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/boxed.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Boxed</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/fixed-sidebar.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Fixed Sidebar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/fixed-sidebar-custom.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Fixed Sidebar <small>+ Custom Area</small></p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/fixed-topnav.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Fixed Navbar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/fixed-footer.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Fixed Footer</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/layout/collapsed-sidebar.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Collapsed Sidebar</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Charts
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/charts/chartjs.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>ChartJS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/charts/flot.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Flot</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/charts/inline.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Inline</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/charts/uplot.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>uPlot</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-tree"></i>
                    <p>
                        UI Elements
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/general.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>General</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/icons.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Icons</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/buttons.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Buttons</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/sliders.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sliders</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/modals.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Modals & Alerts</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/navbar.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Navbar & Tabs</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/timeline.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Timeline</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/UI/ribbons.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ribbons</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-edit"></i>
                    <p>
                        Forms
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/forms/general.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>General Elements</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/forms/advanced.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Advanced Elements</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/forms/editors.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Editors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/forms/validation.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Validation</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-table"></i>
                    <p>
                        Tables
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/tables/simple.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Simple Tables</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/tables/data.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>DataTables</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/tables/jsgrid.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>jsGrid</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-header">EXAMPLES</li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/pages/calendar.html" class="nav-link">
                    <i class="nav-icon far fa-calendar-alt"></i>
                    <p>
                        Calendar
                        <span class="badge badge-info right">2</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/pages/gallery.html" class="nav-link">
                    <i class="nav-icon far fa-image"></i>
                    <p>
                        Gallery
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/pages/kanban.html" class="nav-link">
                    <i class="nav-icon fas fa-columns"></i>
                    <p>
                        Kanban Board
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon far fa-envelope"></i>
                    <p>
                        Mailbox
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/mailbox/mailbox.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Inbox</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/mailbox/compose.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Compose</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/mailbox/read-mail.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Read</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Pages
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/invoice.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Invoice</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/profile.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/e-commerce.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>E-commerce</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/projects.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Projects</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/project-add.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Project Add</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/project-edit.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Project Edit</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/project-detail.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Project Detail</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/contacts.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Contacts</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/faq.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>FAQ</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/contact-us.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Contact us</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon far fa-plus-square"></i>
                    <p>
                        Extras
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Login & Register v1
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/login.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Login v1</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/register.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Register v1</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/forgot-password.html"
                                    class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Forgot Password v1</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/recover-password.html"
                                    class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Recover Password v1</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Login & Register v2
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/login-v2.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Login v2</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/register-v2.html"
                                    class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Register v2</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/forgot-password-v2.html"
                                    class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Forgot Password v2</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/pages/examples/recover-password-v2.html"
                                    class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Recover Password v2</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/lockscreen.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Lockscreen</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/legacy-user-menu.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Legacy User Menu</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/language-menu.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Language Menu</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/404.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Error 404</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/500.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Error 500</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/pace.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pace</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/examples/blank.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Blank Page</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/starter.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Starter Page</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-search"></i>
                    <p>
                        Search
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/search/simple.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Simple Search</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/pages/search/enhanced.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Enhanced</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-header">MISCELLANEOUS</li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/iframe.html" class="nav-link">
                    <i class="nav-icon fas fa-ellipsis-h"></i>
                    <p>Tabbed IFrame Plugin</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="https://adminlte.io/docs/3.1/" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Documentation</p>
                </a>
            </li>
            <li class="nav-header">MULTI LEVEL EXAMPLE</li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="fas fa-circle nav-icon"></i>
                    <p>Level 1</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                        Level 1
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Level 2</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Level 2
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Level 3</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Level 3</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>Level 3</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="/AdminLTE-3.2.0/#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Level 2</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="fas fa-circle nav-icon"></i>
                    <p>Level 1</p>
                </a>
            </li>
            <li class="nav-header">LABELS</li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon far fa-circle text-danger"></i>
                    <p class="text">Important</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon far fa-circle text-warning"></i>
                    <p>Warning</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/AdminLTE-3.2.0/#" class="nav-link">
                    <i class="nav-icon far fa-circle text-info"></i>
                    <p>Informational</p>
                </a>
            </li> --}}
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>