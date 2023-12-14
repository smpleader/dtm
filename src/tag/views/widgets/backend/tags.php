<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
?>
<div>
    <label class="form-label">Tags</label>
    <select name="tags[]" class="select-tag" multiple id="tags">
        <?php foreach($this->tags as $item) : ?>
        <option value="<?php echo $item['id'] ?>" selected><?php echo $item['parent_name'] ? $item['parent_name'].':'.$item['name'] : $item['name'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
<?php echo $this->renderWidget('tag::backend.javascript'); ?>