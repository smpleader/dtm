<tr>
    <td>
        <input class="checkbox-item"  type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <a class="fs-4 me-1 show_data" 
            href="#"
            data-id="<?php echo  $this->item['id'] ?>" 
            data-name="<?php echo htmlspecialchars($this->item['name'])  ?>" 
            data-description="<?php echo htmlspecialchars($this->item['description'] ?? ''); ?>" 
            data-parent_id="<?php echo ($this->item['parent_id']); ?>" 
            data-parent_tag="<?php echo ($this->item['parent_tag']); ?>" 
            data-bs-placement="top" 
            data-bs-toggle="modal" 
            data-bs-target="#formEditTag">
            <?php echo $this->item['name'] ?>
        </a>
    </td>
    <td>
        <?php echo $this->item['parent_tag'] ?>
    </td>
    <td>
        <?php echo $this->item['description'] ?>
    </td>
</tr>