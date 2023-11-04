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
    <?php if($this->mode != 'share-note'): ?>
    <td>
        <?php if(in_array('note_manager', $this->asset) || $this->mode == 'my-note') : ?>
        <a class="fs-4 me-1" href="<?php echo $this->link_form . '/'. $this->item['id'] ?>">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
        <?php endif; ?>
    </td>
    <?php endif; ?>
</tr>
