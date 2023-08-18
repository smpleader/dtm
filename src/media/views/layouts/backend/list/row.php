<tr>
    <td>
        <input class="checkbox-item"  type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <a class="fs-4 me-1 show_data" href="<?php echo $this->url($this->item['path']) ?>">
            <?php echo $this->item['name'] ?>
        </a>
    </td>
    <td>
        <?php echo $this->item['type'] ?>
    </td>
    <td>
        <?php echo $this->item['created_at'] ?>
    </td>
</tr>