<?php echo $this->render('notification', []); ?>
<div id="document_link" class="pt-2">
    <div class="container-fluid">
        <div class="row row justify-content-center mx-auto">
            <div class="col-12">
                <h2>
                    <i class="fa-regular fa-folder-open pe-2"></i>
                    <?php echo $this->title_page_document ?>
                </h2>
                <div class="row pt-3 " id="document_form">
                    <div class="col-lg-7 col-6 border-end">
                        <?php if ($this->editor) : ?>
                            <form id="form_document" action="<?php echo $this->link_form ?>" method="post">
                                <div class="row">
                                    <div class="mb-3 col-sm-12 mx-auto">
                                        <?php $this->ui->field('description'); ?>
                                    </div>
                                </div>
                                <div class="row g-3 align-items-center m-0">
                                    <?php $this->ui->field('token');
                                    if (!$this->status) { ?>
                                        <div class="col-xl-12 col-sm-12 text-center">
                                            <button type="submit" class="btn btn-outline-success">Apply</button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </form>
                        <?php else :
                            echo ($this->data['description']);
                        ?>
                            <a href="<?php echo $this->link_form . '?editor=1' ?>" type="submit" class="btn btn-outline-success">Edit</a>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="col-lg-5 col-6">
                        <?php echo $this->render('layouts.backend.relate_note.list', []); ?>
                        <ul id="list-discussion" class="list-unstyled pt-2" style="max-height: 60vh; overflow:auto;">
                            <?php foreach ($this->discussion as $item) : ?>
                                <li class="d-flex <?php echo $this->user_id == $item['user_id'] ? 'ms-5 me-2 justify-content-end' : 'me-5 ms-2 justify-content-between'; ?>  mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between p-3">
                                            <p class="fw-bold mb-0"><?php echo $this->user_id == $item['user_id'] ? 'You' : $item['user']; ?></p>
                                            <p class="ms-2 text-muted small mb-0 align-self-center"><i class="far fa-clock"></i> <?php echo $item['sent_at'] ?></p>
                                        </div>
                                        <div class="card-body pt-0">
                                            <p class="mb-0">
                                                <?php echo $item['message'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form id="form_comment" action="<?php echo $this->link_form_comment ?>" method="post">
                            <?php $this->ui->field('token'); ?>
                            <div class="form-outline">
                                <textarea required name="message" class="form-control" id="textAreaExample2" rows="4"></textarea>
                                <div class="form-notch">
                                    <div class="form-notch-leading" style="width: 9px;"></div>
                                    <div class="form-notch-middle" style="width: 60px;"></div>
                                    <div class="form-notch-trailing"></div>
                                </div>
                            </div>
                            <?php if (!$this->status) { ?>
                                <button type="submit" class="mt-2 btn btn-info btn-rounded float-end">Comment</button>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="col-12 mb-3">
                        <hr class="bg-danger border-2 border-top border-danger">
                        <h4>History:</h4>
                        <ul class="list-group list-group-flush" id="document_history">
                            <?php foreach ($this->history as $item) : ?>
                                <li class="list-group-item">
                                    <a href="#" class="openHistory" data-id="<?php echo $item['id']; ?>" data-modified_at="<?php echo $item['modified_at']; ?>">Modified at <?php echo $item['modified_at']; ?> by <?php echo $item['modified_by']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="openHistory" aria-hidden="true" aria-labelledby="openHistoryLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="historyDescription">
            </div>
            <input type="hidden" name="rollback_id">
            <div class="modal-footer">
                <button class="btn btn-primary" id="submit_rollback">RollBack</button>
            </div>
        </div>
    </div>
</div>
<script>
    function loadHistory(data) {
        $.ajax({
            url: '<?php echo $this->url . 'get-history/' . $this->request_id ?>',
            type: 'POST',
            data: data,
            success: function(resultData) {
                var list = '';
                if (Array.isArray(resultData.list)) {

                    resultData.list.forEach(function(item) {
                        list += `
                        <li class="list-group-item">
                            <a href="#" class="openHistory" data-id="${item['id']}" data-modified_at="${item['modified_at']}">Modified at ${item['modified_at']} by ${item['modified_by']}</a>
                            <a href="#" class="ps-3 clear-version ms-auto" data-version-id="${item['id']}"><i class="fa-solid fa-trash"></i></a>
                        </li>
                        `
                    });
                    $("#document_history").html(list);
                    loadEventHistory();
                }
            }
        });
    }

    function loadDiscussion(data) {
        const user_id = '<?php echo $this->user_id; ?>'
        $.ajax({
            url: '<?php echo $this->url . 'get-comment/' . $this->request_id ?>',
            type: 'POST',
            data: data,
            success: function(resultData) {
                var list = '';
                if (Array.isArray(resultData.list)) {
                    resultData.list.forEach(function(item) {
                        if (user_id == item['user_id']) {
                            var class_name = 'ms-5 me-2 justify-content-end';
                            var name = 'You';
                        } else {
                            var name = item['user'];
                            var class_name = 'me-5 ms-2 justify-content-between';
                        }

                        list += `
                        <li class="d-flex ${class_name} mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between p-3">
                                    <p class="fw-bold mb-0">${name}</p>
                                    <p class="ms-2 text-muted small mb-0 align-self-center"><i class="far fa-clock"></i>${item['sent_at']}</p>
                                </div>
                                <div class="card-body pt-0">
                                    <p class="mb-0">
                                        ${item['message']}
                                    </p>
                                </div>
                            </div>
                        </li>
                        `
                    });
                    $("#list-discussion").html(list);
                    $("#list-discussion").scrollTop($("#list-discussion")[0].scrollHeight);
                }
            }
        })
    }

    function loadEventHistory() {
        $('.clear-version').on('click', function(e) {
            e.preventDefault();
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (result) {
                var id = $(this).data('version-id');
                var form = new FormData();

                form.append("_method", 'DELETE');
                console.log(form);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->url . 'document/version/'; ?>' + id,
                    data: form,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.result == 'ok') {
                            $('#description').val('');
                        }
                        showMessage(result.result, result.message);
                        loadHistory();
                    }
                });
            } else {
                return false;
            }
        });

        $('.openHistory').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var modified = $(this).data('modified_at');

            $.ajax({
                type: 'GET',
                url: '<?php echo $this->url . 'document/version/'; ?>' + id,
                success: function(result) {
                    $('#historyDescription').html(result.result);
                }
            });
            $('input[name="rollback_id"]').val(id);
            $('#openHistory').modal('show');
            $('#historyLabel').text(modified);
        });
    }

    $(document).ready(function() {
        $("#description").attr('rows', 25);
        $('.request-collapse-document').click(function() {
            $("#list-discussion").scrollTop($("#list-discussion")[0].scrollHeight);
        });
        $("#list-discussion").scrollTop($("#list-discussion")[0].scrollHeight);
        $("#form_document").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $("#form_document").attr('action'),
                data: $('#form_document').serialize(),
                success: function(result) {
                    if (result.result == 'ok') {
                        $('#description').val('');
                    }
                    showMessage(result.result, result.message);
                    loadHistory();
                }
            });
        });

        $('#submit_rollback').on('click', function(e) {
            e.preventDefault();
            var result = confirm("You are going to rollback. Are you sure ?");
            var id = $('input[name="rollback_id"]').val()
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->url . 'document/version/'; ?>' + id,
                    success: function(result) {
                        if (result.result == 'ok') {
                            tinyMCE.activeEditor.setContent(result.description);
                        }
                        showMessage(result.result, result.message);
                        loadHistory();
                        $('#openHistory').modal('hide');

                    }
                });
            } else {
                return false;
            }
        });

        $("#form_comment").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $("#form_comment").attr('action'),
                data: $('#form_comment').serialize(),
                success: function(result) {
                    showMessage(result.result, result.message);
                    $('textarea[name=message]').val('');
                    loadDiscussion();
                }
            });
        });

        loadEventHistory();
    });
</script>