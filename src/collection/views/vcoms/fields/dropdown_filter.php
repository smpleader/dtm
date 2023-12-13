<?php
$this->theme->add($this->url . 'assets/collection/css/dropdown_filter.css', '', 'dropdown_filter-css');
if ($this->field->emptyOption) {
    array_unshift($this->field->options, ['text' => $this->field->emptyOption, 'value' => '']);
}

?>
<div class="filter-dropdown">
    <input type="hidden" name="<?php echo $this->field->name ?>[]" id="<?php echo $this->field->id ?>" class=" <?php echo $this->field->formClass ?>" <?php echo $this->field->required ?> <?php echo $this->field->autocomplete; ?>>
    </input>
    <div class="filter-dropdown-button" id="filter_button_<?php echo $this->field->id ?>">
        <div class="arrow-filter">
        </div>
        <span>
            <?php echo $this->field->label; ?>
        </span>
    </div>
    <div class="filter-show" id="filter_list_<?php echo $this->field->id ?>">
        <div class="filter-list">
            <?php foreach($this->field->options as $option) : ?>
                <a class="filter-item" data-value="<?php echo $option['value'] ?>">
                    <?php echo $option['text']; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="filter-button text-end">
            <a class="align-middle btn border border-1">
                <i class="fa-solid fa-filter"></i>
            </a>
            <a class="align-middle btn border border-1">
                <i class="fa-solid fa-filter-circle-xmark"></i>
            </a>
        </div>

    </div>
</div>
<script>
    $(document).ready(function(){
        $('#filter_button_<?php echo $this->field->id ?>').on('click', function(e){
            $(this).addClass('active');
            $('#filter_list_<?php echo $this->field->id ?>').addClass('active')
            e.stopPropagation();
        });
        $('#filter_list_<?php echo $this->field->id ?> .filter-item').on('click', function(){
            $(this).toggleClass('active');
        })
        $(window).on('click', function(e){
            if($('#filter_button_<?php echo $this->field->id ?>').hasClass('active'))
            {
                if(!e.target.className.includes('filter-show'))
                {
                    $('#filter_button_<?php echo $this->field->id ?>').removeClass('active');
                    $('#filter_list_<?php echo $this->field->id ?>').removeClass('active');
                }
            }
        });
    });
</script>
