<tr>
    <td>
        <input class="checkbox-item" type="checkbox" name="ids[]" value="<?php echo $this->item['id']; ?>">
    </td>
    <td>
        <a href="<?php echo $this->link_request_list.'/'. $this->item['id']; ?>">
            <?php echo  $this->item['title']  ?>
        </a>
    </td>
    <td>
        <?php echo  $this->item['excerpt_description'];  ?>
    </td>
    <td><?php echo   $this->item['status'] ? 'Show' : 'Hide';  ?></td>
    <td class="min-w-100"><?php echo   $this->item['start_date'] ? date('d/m/Y', strtotime($this->item['start_date'])) : '';  ?></td>
    <td class="min-w-100"><?php echo   $this->item['end_date'] ? date('d/m/Y', strtotime($this->item['end_date'])) : '';  ?></td>
    <td>
        <a href="#" 
            class="fs-4 me-1 show_data" 
            data-id="<?php echo  $this->item['id'] ?>" 
            data-title="<?php echo htmlspecialchars($this->item['title'])  ?>" 
            data-status="<?php echo   $this->item['status']?>"
            data-start_date="<?php echo   $this->item['start_date'] ? date('Y-m-d', strtotime($this->item['start_date'])) : '';  ?>" 
            data-description="<?php echo htmlspecialchars($this->item['description'] ?? ''); ?>" data-end_date="<?php echo   $this->item['end_date'] ? date('Y-m-d', strtotime($this->item['end_date'])) : '';  ?>" 
            data-bs-placement="top" 
            data-bs-toggle="modal" 
            data-bs-target="#exampleModalToggle">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>
    </td>
</tr>