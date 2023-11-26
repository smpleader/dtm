<form id="filter_form" class="row pe-0 pb-2" action="<?php echo $this->link_list ?>" method="POST">
    <div class="col-lg-10 col-sm-12">
        <div class="input-group input-group-navbar">
            <?php if($this->filter_id == -1) : ?>
            <div class="pe-2 pb-2">
                <div class="row">
                    <div class="col-auto">
                        <?php if($this->mode != 'share-note'): ?>
                        <a href="#" class="align-middle btn border border-1"
                            type="button"
                            data-bs-placement="top" 
                            data-bs-toggle="modal" 
                            data-bs-target="#noteNewModal"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="pe-2 pb-2">
                <div class="row">
                    <div class="col-auto">
                        <button id="delete_selected" data-bs-placement="top" title="Delete Selected" data-bs-toggle="tooltip" class="btn border border-1" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="pe-2 pb-2">
                <div class="row">
                    <div class="col-auto">
                        <a href="<?php echo $this->link_note_trash ?>" data-bs-placement="top" title="Trash Note" data-bs-toggle="tooltip" class="btn border border-1" type="button">
                            <i class="fa-solid fa-recycle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="pe-2 pb-2">
                <?php $this->ui->field('search');  ?>
            </div>
            <div class="pe-2 pb-2 select2-w-100">
                <?php $this->ui->field('tags');  ?>
            </div>
            <div class="pe-2 pb-2">
                <?php $this->ui->field('note_type');  ?>
            </div>
            <?php if($this->filter_id != -1) : ?>
            <div class="pe-2 pb-2">
                <?php $this->ui->field('author');  ?>
            </div>
            <?php endif;?>
            <input type="hidden" name="clear_filter" id="input_clear_filter">
            <div class="pe-2 pb-2">
                <button type='Submit' data-bs-toggle="tooltip" title="Filter" class=" align-middle btn border border-1" type="button">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
            <div class="pe-2 pb-2">
                <button data-bs-toggle="tooltip" title="Clear Filter" id="clear_filter" class="align-middle btn border border-1" type="button">
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-sm-12 text-end pe-0 pb-1 ">
        <div class="d-flex justify-content-end">
            <div class="me-2">
                <?php $this->ui->field('sort');  ?>
            </div>
            <div>
                <?php $this->ui->field('limit');  ?>
            </div>
        </div>
    </div>
</form>