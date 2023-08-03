<tr>
    <td>
        <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <a href="<?php echo $this->link_form. '/' . $this->item['id'] ; ?>">
            <?php echo  $this->item['title']  ?>
        </a>
    </td>
    <td><?php echo   is_array($this->item['type']) ? $this->item['type']['title'] : $this->item['type'];  ?></td>
    <td><a href="#" class="toggle_status" data-id="<?php echo $this->item['id'];?>" data-status="<?php echo $this->item['status'];?>"><?php echo   $this->item['status'] ? 'Show' : 'Hide';  ?></a></td>
    <td><?php echo   $this->item['auth'];  ?></td>
    <td><?php echo   $this->item['assign'];  ?></td>
    <td><?php echo   $this->item['created_at'];  ?></td>
    <td>
        <a class="fs-4 me-1 show_data" 
            href="#"
            data-id="<?php echo  $this->item['id'] ?>" 
            data-assignment="<?php echo htmlspecialchars($this->item['assignment']); ?>" 
            data-title="<?php echo htmlspecialchars($this->item['title']); ?>" 
            data-bs-placement="top" 
            data-bs-toggle="modal" 
            data-bs-target="#formEdit">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    </td>
</tr>