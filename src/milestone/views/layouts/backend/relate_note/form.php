<?php echo $this->render('notification', []); ?>
<div class="modal fade" id="formRelateNote" aria-labelledby="formRelateNoteLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form method="post" id="form_relate_note">
                <div class="row px-0">
                    <div class="mb-3 col-12 mx-auto">
                        <label class="form-label fw-bold">Note:</label>
                        <select multiple name="note_id[]" id="note_id" class="d-none">
                        </select>
                    </div>
                </div>
                <div class="row g-3 align-items-center m-0">
                    <div class="modal-footer">
                        <?php $this->ui->field('token'); ?>
                        <div class="row">
                            <div class="col-6 text-end pe-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <?php if(!$this->status) {?>
                            <div class="col-6 text-end pe-0">
                                <button type="submit" class="btn btn-outline-success">Add</button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="relateEdit" aria-labelledby="relateEditLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form method="post" id="form_update_relate_note">
                <div class="row px-0">
                    <div class="mb-3 col-12 mx-auto">
                        <label class="form-label fw-bold">Note:</label>
                        <span id="note_title"></span>
                    </div>
                    <div class="mb-3 col-12 mx-auto">
                        <label class="form-label fw-bold">Alias:</label>
                        <input type="text" class="form-control" name="alias" id="alias">
                    </div>
                </div>
                <div class="row g-3 align-items-center m-0">
                    <div class="modal-footer">
                        <?php $this->ui->field('token'); ?>
                        <div class="row">
                            <div class="col-6 text-end pe-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <?php if(!$this->status) {?>
                            <div class="col-6 text-end pe-0">
                                <button type="submit" class="btn btn-outline-success">Update</button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#note_id").select2({
            matcher: matchCustom,
            placeholder: 'Select Notes',
            minimumInputLength: 1,
            multiple: true,
            dropdownParent : "#formRelateNote",
            closeOnSelect: false,
            ajax: {
                url: '<?php echo $this->url. 'get-notes/'. $this->request_id ?>',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function(data, params) {
                    let items = [];
                    let list = data.result;
                    if (Array.isArray(list) && list.length > 0) {
                        list.forEach(function(item) {
                            items.push({
                                id: item.id,
                                text: item.title
                            })
                        })
                    }

                    return {
                        results: items,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true
            },
        });

        function matchCustom(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            // Return `null` if the term should not be displayed
            return null;
        }
        $("#form_relate_note").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->link_form .'/0' ?>',
                data: $('#form_relate_note').serialize(),
                success: function (result) {
                    modal = bootstrap.Modal.getInstance($('#formRelateNote'))
                    modal.hide();
                    $('#note_id').val(null).trigger('change');
                    showMessage(result.result, result.message);
                    listRelateNote($('#filter_form').serialize());
                }
            });
        });

        $("#form_update_relate_note").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#form_update_relate_note').attr('action'),
                data: $('#form_update_relate_note').serialize(),
                success: function (result) {
                    modal = bootstrap.Modal.getInstance($('#relateEdit'))
                    modal.hide();
                    showMessage(result.result, result.message);
                    listRelateNote($('#filter_form').serialize());
                }
            });
        });
    });
</script>
<?php