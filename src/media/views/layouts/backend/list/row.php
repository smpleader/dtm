<div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="item-media cursor-pointer card border shadow-none h-100 m-0">
        <div class="mt-0 d-flex flex-column justify-content-center" style="width: auto;">
            <div class="text-center mt-2">
                <img style="height: 120px; max-width: 90%;" src="<?php echo $this->url($this->item['path']);?>">
                <div class="select-item position-absolute" style="right: 15px; top: 8px;">
                    <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
                </div>
            </div>
            <div class="card-body text-center">
                <a href="<?php echo $this->url($this->item['path']);?>" target="_blank">
                    <p class="card-text fw-bold m-0"><?php echo $this->item['name'];?></p>
                </a>
            </div>
        </div>
    </div>
</div>