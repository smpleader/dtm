<tr>
    <td>
        <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <?php echo  $this->item['title']  ?>
    </td>
    <td><?php echo $this->item['type'] ?></td>
    <td><?php echo !empty($this->data_tags[$this->item['id']]) ? $this->data_tags[$this->item['id']] : '' ?></td>
    <td><?php echo $this->item['created_by'] ?></td>
    <td><?php echo $this->item['created_at'] ?></td>
    <td><?php echo $this->item['deleted_at'] ?></td>
</tr>
