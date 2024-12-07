<script src="/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/image/plugin.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/media/plugin.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/table/plugin.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/anchor/plugin.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: '.tinymcemini',
        height: 200,
        menubar: false,
        plugins: 'advlist autolink lists link image media charmap preview anchor ' + 
        'searchreplace visualblocks code fullscreen insertdatetime table code help wordcount',
        toolbar: 'undo redo | ' +
            'bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
            'link ' + 
            'subscript superscript ',
        image_advtab: true,
        imagetools_cors_hosts: ['picsum.photos']
    });
</script>