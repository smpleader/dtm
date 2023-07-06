<?php echo $this->renderWidget('notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto ">
    <?php
    ?>
    <form enctype='multipart/form-data' action="<?php echo  $this->link_form ?>" method="POST">
        <?php foreach($this->fields as $key => $value) { ?>
            <div class="mb-3 col-lg-12 col-sm-12 mx-auto label-bold">
                <?php $this->ui->field($key); ?>
            </div>
        <?php } ?>
        <div class="d-flex g-3 flex-row align-items-end m-0 justify-content-center">
            <?php $this->ui->field('token'); ?>
            <input class="form-control rounded-0 border border-1" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
            <div class="me-2">
                <a href="<?php echo $this->link_list ?>">
                    <button type="button" class="btn btn-outline-secondary">Cancel</button>
                </a>
            </div>
            <div class="">
                <button type="submit" class="btn btn-outline-success">Apply</button>
            </div>
        </div>
    </form>
    <?php
    //  } 
    ?>
</div>
