<?php echo $this->render('notification'); ?>
<div class="container-fluid align-items-center mt-2 row justify-content-center mx-auto ">
    <form id="form-update" enctype='multipart/form-data' action="<?php echo  $this->link_form ?>" method="POST">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php
            $count = 0;
            foreach ($this->settings as $index => $fields) :
                $count++;
            ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $count == 1 ? 'active' : ''; ?>" id="setting-<?php echo $count; ?>-tab" data-bs-toggle="tab" data-bs-target="#setting-<?php echo $count; ?>" type="button" role="tab" aria-controls="setting-<?php echo $count; ?>" aria-selected="true"><?php echo $index ?></button>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content mt-3">
            <?php $count = 0;
            foreach ($this->settings as $index => $fields) {
                $count++;
            ?>
                <div class="tab-pane fade <?php echo $count == 1 ? 'show active' : ''; ?>" id="setting-<?php echo $count; ?>" role="tabpanel" aria-labelledby="setting-<?php echo $count; ?>-tab">
                    <div class="row">
                    <?php foreach ($fields as $key => $value) { ?>
                        <div class="mb-3 col-lg-6 col-sm-12 col-12 mx-auto label-bold">
                            <?php $this->ui->field($key); ?>
                        </div>
                    <?php
                    } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </form>
    <?php
    //  } 
    ?>
</div>
<script>
    $(document).ready(function() {
        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $('#form-update').submit();
        });
    });
</script>