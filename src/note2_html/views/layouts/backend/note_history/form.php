<?php
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/css/select2_custom.css', '', 'select2-custom-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
$this->theme->add($this->url . 'assets/tinymce/tinymce.min.js', '', 'tinymce');
?>
<?php echo $this->render('notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row">
            <div id="col-8" class="col-lg-8 col-sm-12">
                <input id="input_title" type="hidden" class="d-none" name="title" required>
                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <div class="fw-bold d-flex  mb-2">
                            <span class="me-auto">Description:</span> 
                            <span>
                                <div class="button-editor-mode form-check form-switch me-2 mb-0">
                                    <input class="form-check-input" type="radio" <?php echo ( !$this->data || $this->data && $this->data['editor'] == 'tynimce') ? 'checked' : ''; ?> name="editor" id="tynimceToogle" value="tynimce">
                                    <label class="form-check-label" for="tynimceToogle">Tynimce Mode</label>
                                </div>
                            </span>
                            <span>
                                <div class=" button-editor-mode form-check me-2 form-switch mb-0">
                                    <input class="form-check-input" type="radio" <?php echo ($this->data && $this->data['editor'] == 'sheetjs') ? 'checked' : ''; ?> name="editor" id="sheetToogle" value="sheetjs">
                                    <label class="form-check-label" for="sheetToogle">Sheet Mode</label>
                                </div>
                            </span>
                            <span>
                                <div class="button-editor-mode form-check form-switch mb-0">
                                    <input class="form-check-input" type="radio" <?php echo ($this->data && $this->data['editor'] == 'presenter') ? 'checked' : ''; ?> name="editor" id="PresenterToogle" value="presenter">
                                    <label class="form-check-label" for="PresenterToogle">Presenter Mode</label>
                                </div>
                            </span>
                            <nav class="navbar navbar-expand navbar-light navbar-bg d-flex justify-content-end py-0" style="box-shadow: inherit;">
                                <a class="sidebar-toggle1 js-sidebar-toggle" id="sidebarToggle" style="color: black !important;">
                                    <i class="fa-solid fa-bars fs-2 "></i>
                                </a>
                            </nav>
                        </div>
                        <div id="html_editor" class="d-none">
                            <?php $this->ui->field('description'); ?>
                        </div>
                        <?php $this->ui->field('description_sheetjs'); ?>
                        <div id="presenter_editor" class="d-none">
                            <?php $this->ui->field('description_presenter'); ?>
                        </div>
                        <div id="content" class="p-3 d-none text-break">
                            <?php if (isset($this->data['description'])) {
                                echo $this->data['description'];
                            } ?>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex g-3 flex-row align-items-end m-0 pb-3 justify-content-center">
                    <?php $this->ui->field('token'); ?>
                    <input class="form-control rounded-0 border border-1" type="hidden" name="_method" value="<?php echo 'POST' ?>">
                    <div class="me-2">
                        <a href="<?php echo $this->link_list ?>">
                            <button type="button" class="btn btn-outline-secondary">Cancel</button>
                        </a>
                    </div>
                    <div class="me-2">
                        <input type="hidden" name="save_close" id="save_close">
                        <button type="submit" class="btn btn-outline-success btn_save_close">Rollback</button>
                    </div>
                </div>
            </div>
            <div id="col-4" class="col-lg-4 col-sm-12">
                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <label class="form-label fw-bold">Note:</label>
                        <?php $this->ui->field('note'); ?>
                    </div>
                </div>
                <div class="row pt-3" style="display: none">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <label class="form-label fw-bold">Tags:</label>
                        <?php $this->ui->field('tags'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <label class="form-label fw-bold">Tags:</label>
                        <select class="js-example-tags" multiple id="select_tags">
                            <?php foreach ($this->data_tags as $item) : ?>
                                <option selected="selected" value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        
    </form>
</div>
<style>
    span.select2 {
        width: 100% !important;
    }
</style>
<?php echo $this->render('backend.note_history.javascript', []); ?>
