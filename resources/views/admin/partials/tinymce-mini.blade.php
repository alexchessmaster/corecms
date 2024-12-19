<script src="/tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/image/plugin.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/media/plugin.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/table/plugin.min.js" referrerpolicy="origin"></script>
<script src="/tinymce/js/tinymce/plugins/anchor/plugin.min.js" referrerpolicy="origin"></script>

<style>
    div.tox-promotion{
        display: none;
    }
    span.tox-statusbar__branding{
        display: none;
    }
    .tox .tox-editor-container {
        border: 1px solid #d2d2d2 !important;  /* Set your desired color */
    }
    .tox .tox-edit-area iframe {
        border: 1px solid #e7e7e7 !important;  /* Set your desired color */
    }
</style>

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