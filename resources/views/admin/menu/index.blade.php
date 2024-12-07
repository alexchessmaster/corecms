@extends('admin.partials.app')
@section('content-card-title', 'Menu')
@section('content-card-body')

    <a class="btn btn-success" href="{{ route('admin.menus.create') }}">Create</a>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">NAME</th>
                <th scope="col">LINK</th>
                <th scope="col">PARENT_NAME</th>
                <th scope="col">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
                <tr>
                    <td>
                        @php
                            if(!empty($menu->name)){
                                echo $menu->name;
                            }else{
                                echo '--Not Translated-- ' . '<br>';
                                echo(json_encode($menu->getTranslations('name')));
                            }
                        @endphp
                    </td>
                    <td>{{ $menu->link }}</td>
                    <td>{{ !empty($menu->parent) ? $menu->parent->name : '-' }}</td>
                    <td>
                        <a class="btn btn-info" href="{{ route('admin.menus.edit', $menu->id) }}"><i class="fa fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu->id) }}" id="" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="display: inline"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    </script>

@endsection
