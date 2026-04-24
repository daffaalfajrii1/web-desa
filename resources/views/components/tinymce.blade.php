@push('scripts')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (document.querySelector('#editor')) {
        tinymce.init({
            selector: '#editor',
            license_key: 'gpl',
            height: 520,
            menubar: true,
            branding: false,
            promotion: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                'bold italic underline strikethrough | forecolor backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'link image media table | removeformat code fullscreen preview',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            image_advtab: true,
            image_caption: true,
            image_dimensions: true,
            images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('admin.editor.upload') }}");
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function () {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }

                    let json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (err) {
                        reject('Response upload tidak valid');
                        return;
                    }

                    if (!json || typeof json.location !== 'string') {
                        reject('Format response upload tidak valid');
                        return;
                    }

                    resolve(json.location);
                };

                xhr.onerror = function () {
                    reject('Upload gambar gagal.');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }),
            content_style: `
                body { font-family:Helvetica,Arial,sans-serif; font-size:16px; line-height:1.7; }
                img { max-width:100%; height:auto; }
            `
        });
    }
});
</script>
@endpush