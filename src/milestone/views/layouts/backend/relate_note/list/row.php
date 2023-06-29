<tr>
    <td>
        <input class="checkbox-item-relate-note" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <a target="_blank" href="<?php echo $this->link_note. '/'. $this->item['note_id']; ?>"><?php echo  $this->item['title']  ?></a>
    </td>
    <td><?php echo   $this->item['alias'] ?></td>
    <td><span class="relate-note-description"><?php echo !in_array($this->item['type'], ['presenter', 'sheetjs']) ? $this->item['description'] : '' ?></span></td>
    <td><?php echo   $this->item['tags'] ?></td>
    <td><a type="button" class="fs-3 open-edit-relate" data-id="<?php echo $this->item['id']; ?>" data-title-note="<?php echo $this->item['title']; ?>" data-alias="<?php echo $this->item['alias']; ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
</tr>