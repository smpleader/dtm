<?php
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
$this->theme->add($this->url . 'assets/tinymce/tinymce.min.js', '', 'tinymce');
?>
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row">
            <div id="col-8" class="col-lg-8 col-sm-12">
                <input id="input_title" type="hidden" class="d-none" name="title" required>
                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <div class="fw-bold d-flex">
                            <span class="me-auto">Description:</span>
                            <input type="hidden"  name="type" value="<?php echo $this->type;?>">
                            <nav class="navbar navbar-expand navbar-light navbar-bg d-flex pe-0 justify-content-end py-0" style="box-shadow: inherit;">
                                <a class="sidebar-toggle1 js-sidebar-toggle" id="sidebarToggle" style="color: black !important;">
                                    <i class="fa-solid fa-caret-right fs-1"></i>
                                </a>
                            </nav>
                        </div>
                        <div id="html_editor" class="d-none">
                            <?php $this->ui->field('description'); ?>
                        </div>
                        <?php $this->ui->field('description_sheetjs'); ?>
                        <div id="presenter_editor" class="d-none">
                            <?php $this->ui->field('description_presenter'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex g-3 flex-row align-items-end m-0 pb-3 justify-content-center">
                    <?php $this->ui->field('token'); ?>
                    <input class="form-control rounded-0 border border-1" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
                </div>
            </div>
            
            <div id="col-4" class="col-lg-4 col-sm-12 col-left-note">
                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <label class="form-label fw-bold">Notice:</label>
                        <?php $this->ui->field('note'); ?>
                    </div>
                </div>
                <?php if ($this->data && !$this->data_version) : ?>
                <div class="row">
                    <div class="mb-1 col-lg-12 col-sm-12 mx-auto">
                        <label data-bs-target="#listRevision" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="listRevision" class="form-label fw-bold"><i class="fa-solid fa-clock-rotate-left"></i> Revision: <?php echo count($this->data['versions']);?></label>
                    </div>
                    <div class="collapse mb-2" id="listRevision">
                        <ul class="list-group list-group-flush">
                            <?php foreach($this->data['versions'] as $item) : ?>
                            <li class="list-group-item d-flex">
                                <a href="<?php echo $this->link_form. '/version/'.$item['id']?>">Modified At: <?php echo $item['created_at'] ?> by <?php echo $item['created_by'] ?></a>
                                <a href="#" class="clear-version ms-auto" data-version-id="<?php echo $item['id']?>"><i class="fa-solid fa-trash"></i></a>
                            </li>
                            <?php endforeach;?>
                        </ul>
                        
                    </div>
                </div>
                <?php endif; ?>
                <div class="row pt-3" style="display: none">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                        <label class="form-label fw-bold">Tags:</label>
                        <?php $this->ui->field('tags'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto  tag-note select2-no-index">
                        <label class="form-label fw-bold">Tags:</label>
                        <select class="js-example-tags" multiple id="select_tags">
                            <?php foreach ($this->data['data_tags'] as $key => $value) : ?>
                                <option selected="selected" value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="save_close" id="save_close">
                <label class="form-label fw-bold pt-2">Attachments:</label>
                <input name="files[]" type="file" multiple id="file" class="form-control">
                <div class="d-flex flex-wrap pt-4">
                    <?php foreach ($this->attachments as $item) :
                        $extension = @end(explode('.', $item['path']));
                        if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                            $path = file_exists(PUBLIC_PATH . $item['path']) ? $this->url . $item['path'] : $this->url . 'media/default/default_image.png';
                        }
                        elseif($extension == 'pdf')
                        {
                            $path = $this->url . 'media/default/default_pdf.png';
                        }
                        elseif(in_array($extension, ['doc', 'docx']))
                        {
                            $path = $this->url . 'media/default/default_doc.png';
                        } 
                        elseif(in_array($extension, ['xlsx', 'csv']))
                        {
                            $path = $this->url . 'media/default/default_excel.png';
                        }
                        else
                        {
                            $path = $this->url . 'media/default/default_file.png';
                        }
                        ?>
                        <div class="card border shadow-none d-flex flex-column me-2 justify-content-center" style="width: auto;">
                            <a href="<?php echo file_exists(PUBLIC_PATH. $item['path'] ) ? $this->url . $item['path'] : '' ?>" target="_blank" class="h-100 my-2 px-2 mx-auto" title="<?php echo $item['name']; ?>" style="">
                                <img style="height: 120px; max-width: 100%;" src="<?php echo $path ?>" alt="<?php echo $item['name']; ?>">
                            </a>
                            <div class="card-body d-flex">
                                <p class="card-text fw-bold m-0 me-2"><?php echo $item['name']; ?> </p>
                                <a data-id="<?php echo $item['id']?>" class="ms-auto me-2 button_download_item fs-4"><i class="fa-solid fa-download"></i></a>
                                <a data-id="<?php echo $item['id']?>" class="ms-auto button_delete_item fs-4"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </div>
                        <div class="d-block">
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        
    </form>
</div>
<form class="hidden" method="POST" id="form_delete">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="DELETE" name="_method">
</form>
<form class="hidden" method="POST" id="form_download">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="POST" name="_method">
</form>
<style>
    span.select2 {
        width: 100% !important;
    }
</style>
<?php echo $this->render('backend.note.form.javascript', []); ?>
