<tr>
    <td>
        <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <a href="<?php echo $this->link_preview . '/' . $this->item['id']; ?>"><?php echo  $this->item['title']  ?></a>
    </td>
    <td><?php echo $this->item['type'] ?></td>
    <td><?php echo !empty($this->data_tags[$this->item['id']]) ? $this->data_tags[$this->item['id']] : '' ?></td>
    <td><?php echo $this->item['created_by'] ?></td>
    <td><?php echo $this->item['created_at'] ?></td>
</tr>
