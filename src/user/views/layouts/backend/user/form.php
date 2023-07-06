<?php echo $this->renderWidget('notification');?>
<div class="container-fluid p-0 align-items-center row justify-content-center mx-auto">
    <form action="<?php echo $this->link_form . '/' . $this->id ?>" method="post">
        <div class="row g-3 align-items-center">
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto pt-3">
                    <label class="form-label fw-bold">Name:</label>
                    <?php $this->ui->field('name'); ?>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto">
                    <label class="form-label fw-bold">User Name:</label>
                    <?php $this->ui->field('username'); ?>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto">
                    <label class="form-label fw-bold">Password:</label>
                    <?php $this->ui->field('password'); ?>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto">
                    <label class="form-label fw-bold">Confirm Password:</label>
                    <?php $this->ui->field('confirm_password'); ?>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto">
                    <label class="form-label fw-bold" for="inputEmail4">Email:</label>
                    <?php $this->ui->field('email'); ?>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto">
                    <label class="form-label fw-bold" for="groups">Groups:</label>
                    <?php $this->ui->field('groups'); ?>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-lg-6 col-sm-12 mx-auto">
                    <label class="form-label fw-bold">Status:</label>
                    <?php $this->ui->field('status'); ?>
                </div>
            </div>
            <div class="d-flex g-3 flex-row align-items-end m-0 justify-content-center">
                <?php $this->ui->field('token'); ?>
                <input class="form-control rounded-0 border border-1" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
                <div class="me-2">
                    <a href="<?php echo $this->link_list ?>">
                        <button type="button" class="btn btn-outline-secondary">Cancel</button>
                    </a>
                </div>
                <div class="me-2">
                    <input type="hidden" name="save_close" id="save_close">
                    <button type="submit" class="btn btn-outline-success btn_save_close">Save & Close</button>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-outline-success">Apply</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $(".btn_save_close").click(function() {
                $("#save_close").val(1);
            });
    });
</script>