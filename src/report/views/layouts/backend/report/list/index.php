<?php echo $this->renderWidget('core::notification'); ?>
<div class="main">
	<main class="content p-0 ">
		<div class="container-fluid p-0">
			<div class="row justify-content-center mx-auto">
				<div class="col-12 p-0">
					<div class="card border-0 shadow-none">
						<div class="card-body">
							<div class="row align-items-center">
								<?php echo $this->render('backend.report.list.filter', []); ?>
							</div>
							<form action="<?php echo $this->link_list ?>" method="POST" id="formList">
								<input type="hidden" value="<?php echo $this->token ?>" name="token">
								<input type="hidden" value="DELETE" name="_method">
								<table id="datatables-buttons" class="table table-striped border-top border-1" style="width:100%">
									<thead>
										<tr>
											<th width="10px">
												<input type="checkbox" id="select_all">
											</th>
											<th>Title</th>
											<th>Type</th>
											<th>Status</th>
											<th>Auth</th>
											<th>Assignment</th>
											<th>Created At</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php while ($this->list->hasRow()) echo $this->render('backend.report.list.row', []); ?>
									</tbody>
									<?php
									?>
								</table>
							</form>
							<div class="row g-3 align-items-center">
								<?php echo $this->renderWidget('core::pagination'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
<div class="modal fade" id="reportNewModal" aria-labelledby="reportNewModalTitle" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="reportNewModalTitle">Create Report</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex justify-content-around flex-wrap">
				<?php foreach($this->types as $key => $type) : ?>
					<h4 class="text-nowrap mb-3">
						<a class="mx-3" href="<?php echo $this->link_new_form . '/'. $key?>"><?php echo $type['title']?></a>
					</h4>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<form class="hidden" method="POST" id="form_delete">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="DELETE" name="_method">
</form>
<form class="hidden" method="POST" id="form_update" action="<?php echo $this->link_list ?>">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="" name="id" class="toogle_status_id">
    <input type="hidden" value="PUT" name="_method">
</form>
<?php echo $this->render('backend.report.form', []); ?>
<?php echo $this->render('backend.report.list.javascript', []); ?>
