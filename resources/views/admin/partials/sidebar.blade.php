<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/AdminLTE-3.2.0/dist/img/user0-160x160.png" class="img-circle elevation-2" alt="User Image">
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
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Dashboard and Analytics -->
            @if($settings->firstWhere('key', 'is-website-links-active')?->value === "true")
                <li class="nav-item">
                    <a href="{{ App\Modules\Shared\Helpers\UrlHelper::getFullUrlBySlug('/') }}" class="nav-link" target="_blank">
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
                @can('view url logs')
                    <li class="nav-item">
                        <a href="/admin/url-logs/statistics" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                URL Logs Statistics
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view url logs')
                    <li class="nav-item">
                        <a href="/admin/url-logs" class="nav-link">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>
                                URL Logs
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-menu-active')?->value === "true")
                @if(auth()->user()->can('view menus') || auth()->user()->can('view own menus'))
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/menus" class="nav-link">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p>
                                Menus
                            </p>
                        </a>
                    </li>
                @endif
            @endif
            @if($settings->firstWhere('key', 'is-page-active')?->value === "true")
                @if(auth()->user()->can('view pages') || auth()->user()->can('view own pages'))
                    <div style="border: 1px rgb(55, 67, 71) solid"></div>
                    <li class="nav-item">
                        <a href="/admin/pages" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Pages
                            </p>
                        </a>
                    </li>
                @endif
            @endif
            @if($settings->firstWhere('key', 'is-article-active')?->value === "true")
                @if(auth()->user()->can('view categories') || auth()->user()->can('view own categories'))
                    <div style="border: 1px rgb(55, 67, 71) solid"></div>
                    <li class="nav-item">
                        <a href="/admin/categories" class="nav-link">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>
                                Articles Categories
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view tags') || auth()->user()->can('view own tags'))
                    <li class="nav-item">
                        <a href="/admin/tags" class="nav-link">
                            <i class="nav-icon fas fa-tag"></i>
                            <p>
                                Articles Tags
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view articles') || auth()->user()->can('view own articles'))
                    <li class="nav-item">
                        <a href="/admin/articles" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Articles
                            </p>
                        </a>
                    </li>
                @endif
            @endif
            @if($settings->firstWhere('key', 'is-product-active')?->value === "true")
                @if(auth()->user()->can('view product categories') || auth()->user()->can('view own product categories'))
                    <div style="border: 1px rgb(55, 67, 71) solid"></div>
                    <li class="nav-item">
                        <a href="/admin/product-categories" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Product Categories
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view product tags') || auth()->user()->can('view own product tags'))
                    <li class="nav-item">
                        <a href="/admin/product-tags" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Product Tags
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view product authors') || auth()->user()->can('view own product authors'))
                    <li class="nav-item">
                        <a href="/admin/product-authors" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Product Authors
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view products') || auth()->user()->can('view own products'))
                    <li class="nav-item">
                        <a href="/admin/products" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Products
                            </p>
                        </a>
                    </li>
                @endif
            @endif
            @if($settings->firstWhere('key', 'is-news-active')?->value === "true")
                @if(auth()->user()->can('view news categories') || auth()->user()->can('view own news categories'))
                    <div style="border: 1px rgb(55, 67, 71) solid"></div>
                    <li class="nav-item">
                        <a href="/admin/news-categories" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                News Categories
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view news tags') || auth()->user()->can('view own news tags'))
                    <li class="nav-item">
                        <a href="/admin/news-tags" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                News Tags
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view news authors') || auth()->user()->can('view own news authors'))
                    <li class="nav-item">
                        <a href="/admin/news-authors" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                News Authors
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view news') || auth()->user()->can('view own news'))
                    <li class="nav-item">
                        <a href="/admin/news" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                News
                            </p>
                        </a>
                    </li>
                @endif
            @endif
            @if($settings->firstWhere('key', 'is-book-active')?->value === "true")
                @if(auth()->user()->can('view book genres') || auth()->user()->can('view own book genres'))
                    <div style="border: 1px rgb(55, 67, 71) solid"></div>
                    <li class="nav-item">
                        <a href="/admin/book_genres" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Books Genres
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view book authors') || auth()->user()->can('view own book authors'))
                    <li class="nav-item">
                        <a href="/admin/book-authors" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Writers
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('view books') || auth()->user()->can('view own books'))
                    <li class="nav-item">
                        <a href="/admin/books" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Books
                            </p>
                        </a>
                    </li>
                @endif
            @endif
            @if($settings->firstWhere('key', 'is-comments-active')?->value === "true")
                @can('view commentables')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/comments" class="nav-link">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Comments
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-reservation-active')?->value === "true")
                @can('view booking reservations')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/booking-reservations/calendar?view=today_tomorrow" class="nav-link">
                            <i class="nav-icon far fa-calendar"></i>
                            <p>
                                Booking calendar
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/booking-reservations" class="nav-link">
                            <i class="nav-icon far fa-calendar"></i>
                            <p>
                                Booking reservation
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view booking time slots')
                    <li class="nav-item">
                        <a href="/admin/booking-time-slots" class="nav-link">
                            <i class="nav-icon far fa-calendar"></i>
                            <p>
                                Booking time slots
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view booking slot templates')
                    <li class="nav-item">
                        <a href="/admin/booking-slot-templates" class="nav-link">
                            <i class="nav-icon far fa-calendar"></i>
                            <p>
                                Booking slot templates
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-redirect-active')?->value === "true")
                @can('view redirects')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/redirects" class="nav-link">
                            <i class="nav-icon fas fa-directions"></i>
                            <p>
                                Redirects
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-widgets-active')?->value === "true")
                @can('view widgets')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/widgets" class="nav-link">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p>
                                Widgets
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-fields-active')?->value === "true")
                @can('view fields')
                    <li class="nav-item">
                        <a href="/admin/fields" class="nav-link">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                Fields
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-translation-texts-active')?->value === "true")
                @can('view translation texts')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/translation-texts" class="nav-link">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>
                                Translation Texts
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-users-active')?->value === "true")
                @can('view users')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/users" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view users')
                    <li class="nav-item">
                        <a href="/admin/roles" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Roles
                            </p>
                        </a>
                    </li>
                @endcan
                @can('view users')
                    <li class="nav-item">
                        <a href="/admin/permissions" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Permissions
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @can('view settings')
                <li class="nav-item">
                    <a href="/admin/settings" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
            @endcan
            @if($settings->firstWhere('key', 'is-language-active')?->value === "true")
                @can('view languages')
                    <li class="nav-item">
                        <a href="/admin/languages" class="nav-link">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>
                                Languages
                            </p>
                        </a>
                    </li>
                @endcan
            @endcan
            @if($settings->firstWhere('key', 'is-upload-active')?->value === "true")
                @can('view uploads')
                    <div style="border: 1px gray solid"></div>
                    <li class="nav-item">
                        <a href="/admin/upload" class="nav-link">
                            <i class="nav-icon fas fa-upload"></i>
                            <p>
                                Upload
                            </p>
                        </a>
                    </li>
                @endcan 
            @endcan
        </ul>
    </nav>

    <!-- /.sidebar-menu -->
</div>
