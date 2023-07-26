
<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');

echo $this->render('backend.document.form', []);
echo $this->render('backend.version_latest.list', []);
?>
<div class="toast message-toast" id="message_ajax">
    <div id="message_form" class="d-flex message-body ">
        <div class="toast-body">
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
<div class="modal fade" id="formModalToggle" aria-labelledby="formModalToggleLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form action="<?php echo $this->link_form_request;?>" method="post" id="form_request">
                <div class="row">
                    <div class="mb-3 col-12 mx-auto pt-3">
                        <input name="title" type="text" id="title" required="" placeholder="Request" value="<?php echo htmlspecialchars($this->request['title']);?>" class="form-control h-50-px fw-bold rounded-0 fs-3">
                    </div>
                </div>
                <input type="hidden" name="tags" id="tags">
                <div class="row px-0 mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Tags</label>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <select class="js-example-tags" multiple id="select_tags">
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Start At</label>
                    </div>
                    <div class="col-12">
                        <input name="start_at" type="date" id="start_at" placeholder="Start At" value="<?php echo $this->request['start_at'] && $this->request['start_at'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($this->request['start_at'])) : '';?>" class="form-control rounded-0 border border-1 py-1 fs-4-5"/>                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">End At</label>
                    </div>
                    <div class="col-12">
                        <input name="finished_at" type="date" id="finished_at" placeholder="End At" value="<?php echo $this->request['finished_at'] && $this->request['finished_at'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($this->request['finished_at'])) : '';?>" class="form-control rounded-0 border border-1 py-1 fs-4-5"/>                        
                    </div>
                </div>
                <input type="hidden" name="detail_request" value="1">
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Description</label>
                    </div>
                    <div class="col-12">
                        <textarea name="description" type="text" id="description" placeholder="Enter description" class="form-control rounded-0 border border-1 py-1 fs-4-5"><?php echo htmlspecialchars($this->request['description']);?></textarea>                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Assigments</label>
                    </div>
                    <div class="col-12">
                        <select name="assignment[]" multiple id="assignment">
                        </select>
                    </div>
                </div>
                <div class="row g-3 align-items-center m-0">
                    <div class="modal-footer">
                        <input name="token" type="hidden" id="token" value="91e0f6584395a6a937615717605e92c7">                            <input class="form-control rounded-0 border border-1" id="request" type="hidden" name="_method" value="PUT">
                        <div class="row">
                            <div class="col-6 text-end pe-0">
                                <button type="button" class="btn btn-outline-secondary fs-4" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col-6 text-end pe-0 ">
                                <button type="submit" class="btn btn-outline-success fs-4">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="noteNewModal" aria-labelledby="noteNewModalTitle" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="noteNewModalTitle">New Note</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex justify-content-around">
				<?php foreach($this->note_types as $type) : ?>
					<h4>
						<a  target="_blank" class="mx-3" href="<?php echo $type['link']?>"><?php echo $type['title']?></a>
					</h4>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->render('backend.request.detail_request.javascript'); ?>
