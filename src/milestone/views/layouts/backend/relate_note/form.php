<?php echo $this->renderWidget('core::notification'); ?>
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