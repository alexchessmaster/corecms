@extends('admin.partials.app')

@if(isset($pageType) && $pageType === 'template')
@section('content-card-title', 'Templates')
@else
@section('content-card-title', 'Pages')
@endif

@section('content-card-body')

    <div class="row">
        <div class="col-sm-12">
            <form id="page_form" action="{{ $pageType === 'page' ? route('admin.pages.store') : route('admin.templates.store') }}" method="POST" >
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="page_title">{{ ucfirst($pageType) }} Title</label>
                            <input type="text" name="title" class="form-control" id="page_title" aria-describedby=""
                                placeholder="Page title" value="">
                            <small id="" class="form-text text-muted">The title of the {{ $pageType }}.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="page_slug">{{ ucfirst($pageType) }} Slug</label>
                            <input type="text" name="slug" class="form-control" id="page_slug" aria-describedby=""
                                placeholder="Page URL" value="">
                            {{-- <small class="form-text text-muted"><a id="visit-page" target="_blank"
                                    href="{{ !empty($page) ? $page->slug : '' }}">{{ !empty($page) ? $page->slug : '' }}</a></small> --}}
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Create
                </button>
            </form>
        </div>
    </div>

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
        const titleInput = document.getElementById('page_title'); // ID of the title input
        const slugInput = document.getElementById('page_slug'); // ID of the slug input

        // Listen for input changes in the title field
        titleInput.addEventListener('focusout', (event) => {
            if(event.target.value !== ''){
                let slug = '';
                if (slugInput.value.trim() === '') {
                    slug = slugify(titleInput.value);
                    slugInput.value = slug;
                }  
                // fetch('/api/pages', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //     },
                //     body: JSON.stringify({
                //         title: event.target.value,
                //         slug: slug 
                //     })
                // })
                // .then(response => {
                //     if(!response.ok){
                //         throw new Error('Network response was not ok');
                //     }

                //     return response.json();
                // })
                // .then(data => {
                //     console.log('page created!', data)
                //     console.log('data.page', data.page)
                //     console.log('data.page.slug', data.page.slug)
                //     console.log('data.page.id', data.page.id)

                //     window.location.href('/admin/pages/' + data.page.id . '/edit');
                // });
            }
        });

    
    </script>

@endsection
