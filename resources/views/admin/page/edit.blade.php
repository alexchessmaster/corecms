@extends('admin.partials.app')
@section('content-card-title', ucfirst($pageType) . 's')
@section('content-card-body')

    @push('styles')
        <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
        <style>
            .tox-promotion {
                visibility: hidden;
            }

            .tox .tox-editor-container {
                border: 1px solid #d2d2d2 !important;
                /* Set your desired color */
            }

            .tox .tox-edit-area iframe {
                border: 1px solid #e7e7e7 !important;
                /* Set your desired color */
            }
        </style>
    @endpush


    {{-- <div class="form-group">
        <label>Color picker with addon:</label>

        <div class="input-group my-colorpicker2d colorpicker-element" data-colorpicker-id="2">
            <input type="text" class="form-control" data-original-title="" title="">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-square" style="color: rgb(119, 27, 27);"></i>
                </span>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-sm-12">
            <form id="page_form">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="hidden" name="page_id" id="page_id" value="{{ !empty($page) ? $page->id : '' }}">
                        <div class="form-group">
                            <label for="page_title">{{ ucfirst($pageType) }} title</label>
                            <input type="text" class="form-control" id="page_title" aria-describedby=""
                                placeholder="Page title"
                                value="{{ !empty($page->getTranslation('title', app()->getLocale(), false)) ? $page->getTranslation('title', app()->getLocale(), false) : '' }}">
                            <small id="" class="form-text text-muted">The title of the page.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="page_slug">{{ ucfirst($pageType) }} slug</label>
                            <input type="text" class="form-control" id="page_slug" aria-describedby=""
                                placeholder="Page URL"
                                value="{{ !empty($page->getTranslation('slug', app()->getLocale(), false)) ? $page->getTranslation('slug', app()->getLocale(), false) : '' }}">
                        </div>
                    </div>
                    <div class="col-sm-12">

                        @include('admin.partials.sitemap-form')

                        <br>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>


    @include('admin.partials.add-widget-form')


    <script>
        const slugify = str => {
            console.log('slugify function', str)
            if (str === '/') {
                return '/';
            }

            return str.toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        const titleInput = document.getElementById('page_title');
        const slugInput = document.getElementById('page_slug');
        const sitemapPriorityInput = document.getElementById('sitemap_priority');
        const sitemapChangeFrequencyInput = document.getElementById('sitemap_change_frequency');
        const primaryLanguageInput = document.getElementById('primary_language');
        const sitemapExcludeInput = document.getElementById('sitemap_exclude');
        const currentLanguage = '{!! App::currentLocale() !!}';

        const updatePostInputs = () => {
            console.log('changed 12111111');

            const title = document.getElementById('page_title');
            const slug = document.getElementById('page_slug');
            const sitemapPriority = document.getElementById('sitemap_priority');
            const sitemapChangeFrequency = document.getElementById('sitemap_change_frequency');
            const primaryLanguage = document.getElementById('primary_language');
            const sitemapExclude = document.getElementById('sitemap_exclude');
            const sitemapExcludeValue = sitemapExclude.checked ? true : false;

            fetch('/api/pages/{!! $page->id !!}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        title: title.value,
                        slug: slug.value,
                        lang: currentLanguage,
                        sitemap_priority: sitemapPriority.value,
                        sitemap_change_frequency: sitemapChangeFrequency.value,
                        primary_language: primaryLanguage.value,
                        sitemap_exclude: sitemapExcludeValue,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('page updated!', data);
                    document.getElementById('page_title').value = data.page.title[currentLanguage];
                    document.getElementById('page_slug').value = data.page.slug[currentLanguage];

                    toastr.success('Your change successfully saved!', 'Success');
                });
        };

        titleInput.addEventListener('focusout', updatePostInputs);
        slugInput.addEventListener('focusout', updatePostInputs);
        sitemapPriorityInput.addEventListener('change', updatePostInputs);
        sitemapChangeFrequencyInput.addEventListener('change', updatePostInputs);
        primaryLanguageInput.addEventListener('change', updatePostInputs);
        sitemapExcludeInput.addEventListener('change', updatePostInputs);
    </script>

    @push('scripts')
        <script src="/AdminLTE-3.2.0/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script>
            $('.my-colorpicker2').colorpicker()
        </script>

        <script src="/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    @endpush
@endsection
