<tr>
    <td>
        <?php if(!$this->item['shared_by']) : ?> 
            <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
        <?php endif; ?>
    </td>
    <td>
        <a href="<?php echo $this->link_view . '/' . strtolower($this->item['filter_link']); ?>">
            <i class="fa-solid fa-eye me-2"></i><?php echo  $this->item['name']  ?>
        </a>
    </td>
    <td>
        <?php echo isset($this->item['shared_by']) ? $this->item['shared_by'] : ''  ?>
    </td>
    <td><?php echo $this->item['created_at'] ?></td>
    <td>
        <?php if(!isset($this->item['shared_by']) || !$this->item['shared_by']) : ?>
            <a class="fs-4 me-1" href="<?php echo $this->link_form.'/'. $this->item['id']; ?>">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        <?php endif; ?>
    </td>
</tr>
