<?php use SPT\Theme;
static $tinyMCE;
if(!isset($tinyMCE))
{
    $this->theme->add( $this->url.'assets/tinymce/tinymce.min.js', '', 'tinymce');
}

$js = <<<Javascript
tinymce.PluginManager.add("media_advanced", function (e, t) {
    e.addButton("media_advanced", {
            icon:"media",
            text: "",
            tooltip: "Media Libraries",
            onclick: function () {
                $('#tinymceMedia').modal('show');
            },
        });
});
tinymce.init({
    selector: '#{$this->field->id}',
    plugins: [
        "advlist autolink lists link image charmap print preview anchor media_advanced",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste imagetools wordcount"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media_advanced",
    height: '60vh',
    convert_urls: false, 
    relative_urls : false,
    remove_script_host : false,
    valid_elements : '*[*]',
});
Javascript;

$this->theme->addInline('js', $js);

if($this->field->showLabel): ?>
<label for="<?php echo $this->field->name ?>" class="form-label"><?php echo $this->field->label ?><?php echo $this->field->required ? ' * ':''?></label>
<?php endif; ?>
<textarea name="<?php echo $this->field->name ?>" id="<?php echo $this->field->id ?>"  <?php echo $this->field->required. ' '. $this->field->placeholder.' '. $this->field->autocomplete?>
    class="<?php echo $this->field->formClass?>" ><?php echo $this->field->value?></textarea>
<?php if (!defined('MEDIA_TINYMCE')) :
define('MEDIA_TINYMCE', 'on');
?>
    <div class="modal fade" id="tinymceMedia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="tinymceMediaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tinymceMediaLabel">Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="tinymceMediaTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tinymce-libraries-tab" data-bs-toggle="tab" data-bs-target="#tinymce-media-libraries" type="button" role="tab" aria-controls="tinymce-media-libraries" aria-selected="true">Libraries</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tinymce-media-upload" data-bs-toggle="tab" data-bs-target="#tinymce-media-upload-tab" type="button" role="tab" aria-controls="tinymce-media-upload-tab" aria-selected="false">Upload</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="tinymceMediaTabContent">
                        <div style="overflow-x: hidden !important;" class="tab-pane h-100 overflow-auto fade show active" id="tinymce-media-libraries" role="tabpanel" aria-labelledby="tinymce-libraries-tab">
                            <div class="d-flex mt-2">
                                <input placeholder="Search..." style="max-width:300px;" type="text" class="form-control me-2" id="tinymce-media-search">
                                <button id="btn-search-media-tinymce" class="btn btn-outline-success">Search</button>
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
                        <div class="tab-pane fade" id="tinymce-media-upload-tab" role="tabpanel" aria-labelledby="tinymce-media-upload">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-lg-4 mt-5 col-md-6 col-12 text-center">
                                    <input multiple type="file" id="tinymce_upload_files" class="form-control" multiple name="file[]">
                                    <button class="mt-2 btn btn-outline-success" id="tinymce-upload-image-button">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" id="select-media-tinymce" class="btn btn-primary">Insert</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var page_tinymce = 1;
        function loadTinymceMedia(refresh = false)
        {
            if (refresh)
            {
                page_tinymce = 1;
                $('#tinymce-media-libraries .list').html('');
            }

            var search = $('#tinymce-media-search').val();
            var form = new FormData();
            form.append('search', search);
            form.append('page', page_tinymce);
            $.ajax({
                url: `<?php echo $this->url('admin/media/list'); ?>`,
                type: 'POST',
                data: form,
                contentType: false,
                processData: false,
                success: function(result) {
                    var content = '';
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
                        $('#tinymce-media-libraries .list').html(content);
                        loadPaginationTinymce(result.total_page, page_tinymce, result.total_media);

                    }
                    else{
                        alert(result.message);
                    }
                    
                    loadEventTinymceMedia();

                    return;
                },
                error : function(result){
                    alert('Can`t load media libraries');
                    return;
                }
            });
        }

        function loadPaginationTinymce(total_page, current_page, total_media){
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
            $('#tinymceMedia .pagination').html(pagination);

            var result = `Showing ${(current_page - 1) * 18 + 1} to ${current_page * 18 > total_media ? total_media : (current_page * 18) } of ${total_media} entries`;
            $('#tinymceMedia .pagination-infor').html(result);
        }

        function loadEventTinymceMedia()
        {
            $('#select-media-tinymce').attr('disabled', 'disabled');
            
            $('#tinymceMedia .item-media').off('click').on('click', function(){
                if ($(this).hasClass('active'))
                {
                    $(this).removeClass('active');
                }
                else{
                    $(this).addClass('active');
                }
                
                $('#select-media-tinymce').removeAttr('disabled');
            });

            $('#tinymceMedia .pagination .page-link').on('click', function() {
                var page = $(this).data('page');
                page_tinymce = parseInt(page);
                loadTinymceMedia();
            })
        }
        var page_tinymce = 1;

        $(document).ready(function() {
            $('#tinymce-libraries-tab').on('show.bs.tab', function(){
                $('#tinymceMedia .modal-footer').removeClass('d-none');
            });
            $('#tinymce-libraries-tab').on('hide.bs.tab', function(){
                $('#tinymceMedia .modal-footer').addClass('d-none');
            });
            
            $('#select-media-tinymce').on('click', function(){
                var images = [];
                $('#tinymceMedia .item-media.active').each(function(index){
                    var path = $(this).data('path');
                    tinymce.activeEditor.insertContent('<img src="/' + path + '"/>');
                });
                $('#tinymceMedia').modal('hide');
            })

            $('#btn-search-media-tinymce').on('click', function(e){
                e.preventDefault();
                loadTinymceMedia(true);
            });

            $('#tinymceMedia').on('show.bs.modal', function(){
                loadTinymceMedia(true);
            });

            $('#tinymce-upload-image-button').on('click', function(e){
                e.preventDefault();
                var form = new FormData();
                $('#tinymce-upload-image-button').attr('disabled', 'disabled');
                for (var i = 0; i < $("#tinymce_upload_files").prop("files").length; i++) {
                    form.append('file[]', $("#tinymce_upload_files").prop("files")[i]);
                }

                $.ajax({
                    url: '<?php echo $this->url('admin/media/ajax-upload'); ?>',
                    type: 'POST',
                    data: form,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $('#tinymce-upload-image-button').removeAttr('disabled');
                        if (result.status != 'done') {
                            alert(result.message);
                            return;
                        }
                        
                        $("#tinymce_upload_files").val(null);
                        alert(result.message);
                        return;
                    },
                    error : function(result){
                        $('#tinymce-upload-image-button').removeAttr('disabled');
                        alert('Can`t Upload File');
                        return;
                    }
                });
            })
        });
    </script>
<?php endif; ?>