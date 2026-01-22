var myeditor;
ClassicEditor.create(document.querySelector("#editor"),{
    ckfinder: {
        uploadUrl: '/sudut-panel/admin/common/ckeditor/upload'+'?_token='+document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
}).then(editor => {
    myeditor = editor;
}).catch(error => {
    console.error(error);
});
