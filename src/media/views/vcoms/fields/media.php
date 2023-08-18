<?php if ($this->field->showLabel) : ?>
    <label for="<?php echo $this->field->name ?>"><?php echo $this->field->label ?></label>
<?php endif; ?>
<div class="d-flex mt-1 align-items-center">
    <div>
        <button class="btn btn-outline-success open-media-popup" data-id="<?php echo $this->field->id ?>"><i class="me-2 fa-solid fa-camera"></i>Media</button>
    </div>
    <div class="ms-2">
        <input type="hidden" name="<?php echo $this->field->name ?>" id="<?php echo $this->field->id ?>">
        <span id="value-<?php echo $this->field->id ?>">
            <?php echo basename($this->field->value); ?>
        </span>
    </div>
</div>

<?php if (!defined('MEDIA_POPUP')) :
    define('MEDIA_POPUP', 'popup');
?>
    <div class="modal fade" id="mediaPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mediaPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPopupLabel">Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="mediaTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="libraries-tab" data-bs-toggle="tab" data-bs-target="#media-libraries" type="button" role="tab" aria-controls="media-libraries" aria-selected="true">Libraries</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="media-upload-tab" data-bs-toggle="tab" data-bs-target="#media-upload" type="button" role="tab" aria-controls="media-upload" aria-selected="false">Upload</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="mediaTabContent">
                        <div class="tab-pane fade show active" id="media-libraries" role="tabpanel" aria-labelledby="libraries-tab">
                            <div class="d-flex mt-2">
                                <input placeholder="Search..." style="max-width:300px;" type="text" class="form-control me-2" id="media-search">
                                <button id="btn-search-media" class="btn btn-outline-success">Search</button>
                            </div>
                            <div class="list">

                            </div>
                        </div>
                        <div class="tab-pane fade" id="media-upload" role="tabpanel" aria-labelledby="media-upload-tab">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-lg-4 mt-5 col-md-6 col-12 text-center">
                                    <input multiple type="file" id="upload_files" class="form-control" required multiple name="file[]">
                                    <button class="mt-2 btn btn-outline-success" id="upload-image-button">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadMedia()
        {
            var search = $('#media-search').val();
            var form = new FormData();
            form.append('search', search);
            form.append('page', page_media);
            $.ajax({
                url: `<?php echo $this->url('admin/media/list'); ?>`,
                type: 'POST',
                data: form,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.status != 'done') {
                        alert(result.message);
                        return;
                    }

                    $("#upload_files").val(null);
                    alert(result.message);
                    return;
                },
                error : function(result){
                    alert('Can`t load media libraries');
                    return;
                }
            });
            content = '';
            list = [];
            $('#media-libraries .list').html(content);
        }
        var page_media = 1;

        $(document).ready(function() {
            $('#btn-search-media').on('click', function(e){
                e.preventDefault();
                loadMedia();
            });

            $('.open-media-popup').on('click', function(e) {
                e.preventDefault();
                $('#mediaPopup').modal('show');
            })

            $('#upload-image-button').on('click', function(e){
                e.preventDefault();
                var form = new FormData();
                $('#upload-image-button').attr('disabled', 'disabled');
                for (var i = 0; i < $("#upload_files").prop("files").length; i++) {
                    form.append('file[]', $("#upload_files").prop("files")[i]);
                }

                $.ajax({
                    url: '<?php echo $this->url('admin/media/ajax-upload'); ?>',
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $('#upload-image-button').removeAttr('disabled');
                        if (result.status != 'done') {
                            alert(result.message);
                            return;
                        }
                        
                        $("#upload_files").val(null);
                        alert(result.message);
                        return;
                    },
                    error : function(result){
                        $('#upload-image-button').removeAttr('disabled');
                        alert('Upload Failed');
                        return;
                    }
                });
            })
        });
    </script>
<?php endif; ?>