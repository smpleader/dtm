<?php if ($this->field->showLabel) : ?>
    <div>
        <img class="img-fluid mb-2" id="preview-<?php echo $this->field->id ?>" src="<?php echo $this->url($this->field->value); ?>" alt="">
    </div>
    <label for="<?php echo $this->field->name ?>"><?php echo $this->field->label ?></label>
<?php endif; ?>
<div class="d-flex mt-1 align-items-center">
    <div>
        <button class="btn text-nowrap btn-outline-success open-media-popup" data-id="<?php echo $this->field->id ?>"><i class="me-2 fa-solid fa-camera"></i>Media</button>
    </div>
    <div class="ms-2">
        <input type="hidden" name="<?php echo $this->field->name ?>" id="<?php echo $this->field->id ?>" value="<?php echo $this->field->value ?>">
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
                                <div class="row">
                                    <div class="col-12 footer">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="pagination-infor">
                                            </div>
                                            <ul class="pagination d-flex justify-content-end mg-0 mb-0">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="media-upload" role="tabpanel" aria-labelledby="media-upload-tab">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-lg-4 mt-5 col-md-6 col-12 text-center">
                                    <input multiple type="file" id="upload_files" class="form-control" multiple name="file[]">
                                    <button class="mt-2 btn btn-outline-success" id="upload-image-button">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <input type="hidden" id="path-select-item">
                    <input type="hidden" id="field-media-id">
                    <button type="button" id="clear-media-item" class="btn btn-secondary">Clear</button>
                    <button type="button" id="select-media-item" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadMedia(refresh = false) {
            if (refresh) {
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
                        $('#media-libraries .list').html(content);

                        loadPaginationMedia(result.total_page, page_media, result.total_media);
                    } else {
                        alert(result.message);
                    }
                    
                    loadEventMedia();

                    return;
                },
                error: function(result) {
                    alert('Can`t load media libraries');
                    return;
                }
            });
        }

        function loadPaginationMedia(total_page, current_page, total_media){
            var pagination = `<li class="page-item ${current_page == 1 ? 'disabled' : '' } m-0 first-page">
                    <a class="page-link" data-page="1">First</a>
                </li>
                <li class="page-item ${current_page == 1 ? 'disabled' : '' } m-0 previous-page">
                    <a class="page-link" data-page="${current_page - 1}">Previous</a>
                </li>`;
            var pages = [];
            if (total_page > 4) {
                if (current_page == 1) {
                    pages = [1, 2, 3, 0, total_page];
                } else if (current_page == 2 && total_page > 5) {
                    pages = [1, 2, 3, 4, 0, total_page];
                } else if (current_page == 2 && total_page == 5) {
                    pages = [1, 2, 3, 4, total_page];
                } else if (current_page == 3 && total_page == 5) {
                    pages = [1, 2, 3, 4, total_page];
                } else if (current_page == 3 && total_page > 5) {
                    pages = [1, 2, 3, 4, 0, total_page];
                } else if (current_page == (total_page - 2)) {
                    pages = [1, 0, current_page - 1, current_page, total_page];
                } else if (current_page == (total_page - 1)) {
                    pages = [1, 0, current_page - 1, current_page, total_page];
                } else if (current_page == total_page) {
                    pages = [1, 0, current_page - 1, current_page];
                } else {
                    pages = [1, 0, current_page - 1, current_page, current_page + 1, 0, total_page];
                }
            } else {
                for (let index = 0; index < total_page; index++) {
                    pages.push(index+1);
                }
            }      
            pages.forEach(function(item, index){
                pagination += `<li class="page-item ${item == current_page ? 'active' : ''} ${!item ? 'disabled' : ''}">
                    <a class="page-link " data-page="${item}">
                        ${item}
                    </a>
                </li>`;
            }); 
            
            pagination += `<li class="page-item next-page ${current_page == total_page ? 'disabeld' : ''}">
                    <a class="page-link" data-page="${current_page - 1}">Next</a>
                </li>
                <li class="page-item last-page">
                    <a class="page-link" data-page="${total_page}">Last</a>
                </li>`;
            $('#mediaPopup .pagination').html(pagination);

            var result = `Showing ${(current_page - 1) * 18 + 1} to ${current_page * 18 > total_media ? total_media : (current_page * 18) } of ${total_media} entries`;
            $('#mediaPopup .pagination-infor').html(result);
        }

        function loadEventMedia() {
            $('#select-media-item').attr('disabled', 'disabled');

            $('.item-media').off('click').on('click', function() {
                $('.item-media').removeClass('active');
                $(this).addClass('active');
                var path = $(this).data('path');

                $('#path-select-item').val(path);
                $('#select-media-item').removeAttr('disabled');
            });

            $('#mediaPopup .pagination .page-link').on('click', function() {
                var page = $(this).data('page');
                page_media = parseInt(page);
                loadMedia();
            })
        }
        var page_media = 1;

        $(document).ready(function() {
            $('#libraries-tab').on('show.bs.tab', function() {
                $('#mediaPopup .modal-footer').removeClass('d-none');
            });
            $('#libraries-tab').on('hide.bs.tab', function() {
                $('#mediaPopup .modal-footer').addClass('d-none');
            });
            $('#select-media-item').on('click', function() {
                var path = $('#path-select-item').val();
                var id = $('#field-media-id').val();
                var parts = path.split('/');
                var filename = parts[parts.length - 1];

                $(`#${id}`).val(path);
                $(`#value-${id}`).text(filename);
                $(`#preview-${id}`).attr('src', '/' + path);
                $('#mediaPopup').modal('hide');
            })

            $('#clear-media-item').on('click', function() {
                var id = $('#field-media-id').val();
                $(`#${id}`).val('');
                $(`#value-${id}`).text('');
                $(`#preview-${id}`).attr('src', '');
                $('#mediaPopup').modal('hide');
            });

            $('#btn-search-media').on('click', function(e) {
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

            $('#upload-image-button').on('click', function(e) {
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
                    error: function(result) {
                        $('#upload-image-button').removeAttr('disabled');
                        alert('Can`t Upload File');
                        return;
                    }
                });
            })
        });
    </script>
<?php endif; ?>