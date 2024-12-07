<div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name"
        name="name" value="{{ isset($menu) ? $menu->name : '' }}">
    <small id="name" class="form-text text-muted">Name of the menu</small>
</div>

<div class="group">
    



</div>

<div class="form-group">
    <label for="exampleFormControlSelect12">Position</label>
    <select class="form-control" name="order" id="exampleFormControlSelect12">
        @if (isset($menu))
            <option value="">- Keep current position</option>
        @endif
        <option value="0">- First item</option>
        @foreach ($menus as $item)
            @if (isset($menu))
                @if ($item->id !== $menu->id)
                    <option value="{{ $item->order }}">AFTER: {{ $item->name }}</option>
                @endif
            @else
                <option value="{{ $item->order }}">AFTER: {{ $item->name }}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="exampleFormControlSelect1">Parent menu</label>
    <select class="form-control" name="parent_id" id="exampleFormControlSelect1">
        <option value="">- No parent</option>
        @foreach ($menus as $item)
            @if (isset($menu))
                @if ($item->id !== $menu->id)
                    <option {{ $item->id === $menu->parent_id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                @endif
            @else
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endif
        @endforeach
    </select>
</div>
