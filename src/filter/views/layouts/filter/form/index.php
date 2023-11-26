
<?php
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
?>
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-12">
                <input id="_method" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
                <div class="mb-3">
                    <?php $this->ui->field('name'); ?>
                </div>
                <div class="mb-3">
                    <?php $this->ui->field('select_object'); ?>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <?php $this->ui->field('start_date'); ?>
                    </div>
                    <div class="col-6">
                        <?php $this->ui->field('end_date'); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <div>
                        <label class="form-label" for="tags">Tags</label>
                        <select class="selectpicker d-block form-select" multiple name="tags[]" id="tags"  >
                            <?php foreach($this->tags as $tag) : ?>
                                <option selected value="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <?php $this->ui->field('creator'); ?>
                </div>
                <div class="mb-3">
                    <?php $this->ui->field('permission'); ?>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <?php $this->ui->field('shortcut_name'); ?>
                    </div>
                    <div class="col-4">
                        <input name="shortcut_link" type="text" 
                            id="shortcut_link" 
                            placeholder="Shortcut Link" 
                            value="<?php echo $this->data ? $this->url('my-filter/'. $this->data['filter_link']) : ''; ?>" 
                            class="form-control" disabled="">
                    </div>
                    <div class="col-4">
                        <?php $this->ui->field('shortcut_group'); ?>
                    </div>
                </div>
                <input id="save_close" type="hidden" name="save_close">
                <button id="button_save" class="d-none" type="submit">Save</button>
            </div>
        </div>
    </form>
</div>

<?php echo $this->render('filter.form.javascript'); ?>