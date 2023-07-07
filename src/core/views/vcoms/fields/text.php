<?php if($this->field->showLabel): ?>
<label for="<?php echo $this->field->name ?>" class="form-label"><?php echo $this->field->label ?><?php echo $this->field->required ? ' * ':''?></label>
<?php endif; ?>
<input name="<?php echo $this->field->name ?>" type="text" id="<?php echo $this->field->id ?>"  <?php echo $this->field->required. ' '. $this->field->placeholder.' '. $this->field->autocomplete?>
    value='<?php if(is_array($this->field->value)){foreach($this->field->value as $key=>$v){echo implode(';', $v);} } else echo htmlspecialchars($this->field->value);?>'   class="<?php echo $this->field->formClass?>" >
