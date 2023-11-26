<div class="modal fade" id="shortcutModel" aria-labelledby="shortcutModelLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-4">
            <form action="" method="post" id="form_shortcut">
                <div class="row">
                    <div class="mb-2">
                        <h4>Shortcut</h4>
                    </div>
                    <div class="mb-2 col-12 mx-auto">
                        <?php $this->ui->field('name_shortcut'); ?>
                    </div>
                    <div class="mb-2 col-12 mx-auto">
                        <?php $this->ui->field('link_shortcut'); ?>
                    </div>
                    <div class="mb-2 col-12 mx-auto">
                        <?php $this->ui->field('group_shortcut'); ?>
                    </div>
                    <input type="hidden" class="_method" value="POST" name="_method">
                </div>
                <div class="row g-3 ">
                    <div class="col-12 d-flex justify-content-end mb-4">
                        <div class="me-2">
                            <button type="button" class="btn btn-outline-secondary fs-4" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-outline-success fs-4">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php echo $this->renderWidget('shortcut::form.javascript'); ?>