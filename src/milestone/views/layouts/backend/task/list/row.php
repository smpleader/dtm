<tr>
    <td>
        <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <?php if(!$this->status) {?>
        <a href="#"
            class="show_data" 
            data-id="<?php echo  $this->item['id'] ?>" 
            data-title="<?php echo  $this->item['title']  ?>" 
            data-url="<?php echo   $this->item['url'] ?>" 
            data-bs-placement="top" 
            data-bs-toggle="modal" 
            data-bs-target="#Popup_form_task">
            <?php echo  $this->item['title']  ?>
        </a>
        <?php } else {?>
            <a href="#" class="btn show_data disabled border-0" tabindex="-1" role="button" aria-disabled="true"><?php echo  $this->item['title']  ?></a>
        <?php } ?>
    </td>
    <td>
    <?php if(!$this->status) {?>
        <a href="<?php echo $this->item['url']; ?>"><?php echo   $this->item['url'] ?></a>
        <?php } else {?>
            <a href="#" class="btn show_data disabled border-0" tabindex="-1" role="button" aria-disabled="true"><?php echo   $this->item['url'] ?></a>
        <?php } ?>
    </td>
    <td>
    <?php if(!$this->status) {?>
        <a href="#>" 
            class="fs-4 me-1 show_data"
            data-id="<?php echo  $this->item['id'] ?>" 
            data-title="<?php echo  $this->item['title']  ?>" 
            data-url="<?php echo   $this->item['url']?>"
            data-bs-placement="top" 
            data-bs-toggle="modal" 
            data-bs-target="#Popup_form_task">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        <?php }?>
    </td>
</tr>