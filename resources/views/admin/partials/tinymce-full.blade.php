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
        selector: '.tinymce',
        height: 500,
        menubar: true,
        plugins: 'advlist autolink lists link image media charmap preview anchor ' + 
        'searchreplace visualblocks code fullscreen insertdatetime table code help wordcount',
        toolbar: 'undo redo | ' +
            'bold italic underline strikethrough forecolor backcolor | blocks fontfamily fontsize | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | ' +
            'link image media | code removeformat ' + 
            'searchreplace table | hr subscript superscript | charmap emoticons | codesample',
        image_advtab: true,
        imagetools_cors_hosts: ['picsum.photos']
    });
</script>