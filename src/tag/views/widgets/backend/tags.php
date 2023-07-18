<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
?>
<div>
    <label class="form-label">Tags</label>
    <select class="select-tag" multiple id="tags">
    </select>
</div>
<?php echo $this->renderWidget('tag::backend.javascript'); ?>