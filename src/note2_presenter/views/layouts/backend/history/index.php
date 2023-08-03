
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-sm-12">
                <input id="input_title" type="hidden" name="title" required>
                <div>
                    <?php $this->ui->field('data'); ?>
                </div>
                <input id="save_close" type="hidden" name="save_close">
            </div>
        </div>
    </form>
</div>
<?php echo $this->render('backend.history.javascript'); ?>

