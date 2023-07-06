<form id="filter_form_task" class="row pe-0 pb-2" action="<?php echo $this->link_list ?>" method="POST">
    <div class="col-lg-11 col-sm-12">
        <div class="input-group input-group-navbar">
            <div class="pe-2">
                <div class="row">
                    <div class="col-auto">
                        <?php if(!$this->status) {?>
                            <button data-id="" 
                                data-title="" 
                                data-url=""
                                type="button" 
                                class="align-middle btn border border-1 show_data" 
                                data-bs-placement="top" 
                                data-bs-toggle="modal" 
                                data-bs-target="#Popup_form_task">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="pe-2">
                <div class="row">
                    <div class="col-auto">
                    <?php if(!$this->status) {?>
                        <button id="delete_selected" data-bs-placement="top" title="Delete Selected" data-bs-toggle="tooltip" class="btn border border-1" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="pe-2">
                <?php $this->ui->field('search_task');  ?>
            </div>
            <button type='Submit' data-bs-toggle="tooltip" title="Filter" class=" align-middle btn border border-1" type="button">
                <i class="fa-solid fa-filter"></i>
            </button>
            <button data-bs-toggle="tooltip" title="Clear Filter" id="clear_filter_task" class="align-middle btn border border-1 ms-2" type="button">
                <i class="fa-solid fa-filter-circle-xmark"></i>
            </button>
        </div>
    </div>
</form>