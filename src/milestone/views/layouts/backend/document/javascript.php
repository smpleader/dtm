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
                            <a href="#" class="openHistory" data-id="${item['id']}" data-modified_at="${item['created_at']}">Modified at ${item['created_at']} by ${item['user']}</a>
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
                        if (user_id == item['created_by']) {
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
                                    <p class="ms-2 text-muted small mb-0 align-self-center"><i class="far fa-clock"></i>${item['created_at']}</p>
                                </div>
                                <div class="card-body pt-0">
                                    <p class="mb-0">
                                        ${item['comment']}
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
        loadDiscussion();
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
            $("#form_comment button").attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: $("#form_comment").attr('action'),
                data: $('#form_comment').serialize(),
                success: function(result) {
                    showMessage(result.result, result.message);
                    $("#form_comment button").removeAttr('disabled');
                    $('textarea[name=message]').val('');
                    loadDiscussion();
                }
            });
        });

        loadEventHistory();
    });
</script>