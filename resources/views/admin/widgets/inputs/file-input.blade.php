<div class="form-group">
    <label for="">{!! $data[1] !!}</label>
    <input type="file" class="form-control" id="file-{{ $data[0] }}" aria-describedby=""
        placeholder="" name="file-{{ $data[0] }}">
    <small id="" class="form-text text-muted">{!! $data[2] !!}</small>
    @if (isset($widget->fields[$data[0]]) && !empty($widget->fields[$data[0]]->value))
        <div id="current-image-{{ $data[0] }}">
            <div>Corrent image:</div>
            <img id="" width="300" src="{!! isset($widget->fields[$data[0]]) ? $widget->fields[$data[0]]->value : '' !!}" alt="">
            <i id="delete-the-file-{{ $data[0] }}" class="fa fa-solid fa-trash" style="color: rgb(255, 103, 103)"></i>
        </div>
    @endif

</div>
<script>
    @if (isset($widget->fields[$data[0]]) && !empty($widget->fields[$data[0]]->value))
        document.getElementById('delete-the-file-{{ $data[0] }}').addEventListener('click', ()=>{
            if(confirm("The action is permenent. Are you sure yo want to delete the image?")){
                fetch("{{ route('admin.fields.destroy', $widget->fields[$data[0]]) }}", {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": "{{ csrf_token() }}"
                    }
                }).then(response => {
                    console.log('delete response:', response)
                    return response.json();
                }).then(data => {
                    console.log('delete response data:', data)
                    document.getElementById('current-image-{{ $data[0] }}').innerHTML = '';
                })
            }
        })
    @endif
</script>
