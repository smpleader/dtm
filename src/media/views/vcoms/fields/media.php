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
                        <div style="overflow-x: hidden !important;" class="tab-pane h-100 overflow-auto fade show active" id="media-libraries" role="tabpanel" aria-labelledby="libraries-tab">
                            <div class="d-flex mt-2">
                                <input placeholder="Search..." style="max-width:300px;" type="text" class="form-control me-2" id="media-search">
                                <button id="btn-search-media" class="btn btn-outline-success">Search</button>
                            </div>
                            <div class="container-fluid">
                                <div class="list row mt-3">
                                
                                </div>
                            </div>
                            <div class="footer">
                                <h4 class="text-center">
                                    <a href="" class="pe-none" id="loadmore-media">Load More</a>
                                </h4>
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
                <div class="modal-footer border-top-0">
                    <input type="hidden" id="path-select-item">
                    <input type="hidden" id="field-media-id">
                    <button type="button" id="select-media-item" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadMedia(refresh = false)
        {
            if (refresh)
            {
                page_media = 1;
                $('#media-libraries .list').html('');
            }

            var search = $('#media-search').val();
            var form = new FormData();
            form.append('search', search);
            form.append('page', page_media);
            $('#loadmore-media').addClass('pe-none');
            $.ajax({
                url: `<?php echo $this->url('admin/media/list'); ?>`,
                type: 'POST',
                data: form,
                contentType: false,
                processData: false,
                success: function(result) {
                    var content = '';
                    $('#loadmore-media').removeClass('pe-none');
                    if (result.status == 'done') {
                        result.list.forEach(item => {
                            content += `
                            <div class="col-lg-4 col-md-6 col-12 mb-4">
                                <div data-path="${item.path}"  class="item-media cursor-pointer card border shadow-none h-100 m-0">
                                    <div class="mt-0 d-flex flex-column justify-content-center" style="width: auto;">
                                        <div class="text-center mt-2">
                                            <img style="height: 120px; max-width: 90%;" src="/${item.path}">
                                        </div>
                                        <div class="card-body text-center">
                                            <p class="card-text fw-bold m-0">${item.name}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                        $('#media-libraries .list').append(content);
                        if (result.total_page > page_media)
                        {
                            $('#loadmore-media').removeClass('d-none');
                        }
                        else{
                            $('#loadmore-media').addClass('d-none');
                        }
                    }
                    else{
                        alert(result.message);
                    }
                    
                    $('#media-libraries .list').html(content);
                    loadEventMedia();

                    return;
                },
                error : function(result){
                    alert('Can`t load media libraries');
                    return;
                }
            });
        }

        function loadEventMedia()
        {
            $('#select-media-item').attr('disabled', 'disabled');
            
            $('.item-media').on('click', function(){
                $('.item-media').removeClass('active');
                $(this).addClass('active');
                var path = $(this).data('path');
                
                $('#path-select-item').val(path);
                $('#select-media-item').removeAttr('disabled');
            });
        }
        var page_media = 1;

        $(document).ready(function() {
            $('#select-media-item').on('click', function(){
                var path = $('#path-select-item').val();
                var id = $('#field-media-id').val();
                var parts = path.split('/');
                var filename = parts[parts.length - 1];

                $(`#${id}`).val(path);
                $(`#value-${id}`).text(filename);
                $('#mediaPopup').modal('hide');
            })

            $('#btn-search-media').on('click', function(e){
                e.preventDefault();
                loadMedia(true);
            });

            $('.open-media-popup').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#field-media-id').val(id);
                $('#mediaPopup').modal('show');
                loadMedia(true);
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
                        alert('Can`t Upload File');
                        return;
                    }
                });
            })
        });
    </script>
<?php endif; ?>