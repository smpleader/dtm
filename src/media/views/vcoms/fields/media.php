<?php if($this->field->showLabel): ?>
<label for="<?php echo $this->field->name ?>"><?php echo $this->field->label ?><?php echo $this->field->required ? ' * ':''?></label>
<?php endif;?>
<?php /*
$index = 0;
?>
<?php foreach($this->field->hub as $key => $positions) { ?>
    <div data-template-path="<?php echo $key?>" class="<?php echo $index ? 'd-none' : ''; $index ++;?> position-template">
        <?php foreach($positions as $position) { ?>
            <div data-position="<?php echo $position->name?>" data-limit="<?php echo $position->limit?>" class="d-flex align-items-center my-2 border-bottom position-item">
                <?php if ($position->limit):?>
                <?php endif; ?>
                <h3><?php echo isset($position->label) ? $position->label: $position->name; ?>:</h3>
                <div data-position="<?php echo $position->name?>" class="d-flex widgets">
                </div>
                <a data-position="<?php echo $position->name?>" class="new-position-widget ms-2">
                    <i class="fa-solid fa-circle-plus fs-3"></i>
                </a>
            </div>
        <?php } ?>
    </div>
<?php } */?>
this is media