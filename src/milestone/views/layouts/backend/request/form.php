<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');

echo $this->renderWidget('core::notification'); ?>
<div class="modal fade" id="exampleModalToggle" aria-labelledby="exampleModalToggleLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form action="" method="post" id="form_request">
                <div class="row g-3 align-items-center">
                    <div class="row px-0">
                    <div class="row px-0">
                        <div class="mb-3 col-12 mx-auto pt-3">
                            <?php $this->ui->field('title'); ?>
                        </div>
                    </div>
                    <?php $this->ui->field('tags'); ?>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Tags</label>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <select class="js-example-tags" multiple id="select_tags">
                            </select>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Start</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('start_at'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Finished</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('finished_at'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Description</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('description'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Assigments</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('assignment'); ?>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center m-0">
                        <div class="modal-footer">
                            <?php $this->ui->field('token'); ?>
                            <input class="form-control rounded-0 border border-1" id="request" type="hidden" name="_method" value="POST">
                            <div class="row">
                                <div class="col-6 text-end pe-0">
                                    <button type="button" class="btn btn-outline-secondary fs-4" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col-6 text-end pe-0 ">
                                    <button type="submit" class="btn btn-outline-success fs-4">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>