<?php echo $this->renderWidget('core::notification'); ?>
<div class="main">
	<main class="content p-0 ">
		<div class="container-fluid p-0">
			<div class="row justify-content-center mx-auto">
				<div class="col-12 p-0">
					<div class="card border-0 shadow-none">
						<div class="card-body">
							<div class="row align-items-center">
								<?php echo $this->render('backend.request.list.filter', []); ?>
							</div>
							<div class="row align-items-center">
								<?php echo $this->render('backend.request.form', []); ?>
							</div>
							<form action="<?php echo $this->link_list ?>" method="POST" id="formList">
								<input type="hidden" value="<?php echo $this->token ?>" name="token">
								<input type="hidden" value="DELETE" name="_method">
								<table id="datatables-buttons" class="request-table table table-striped border-top border-1" style="width:100%">
									<thead>
										<tr>
											<th width="10px">
												<input type="checkbox" id="select_all">
											</th>
											<th>Title</th>
											<th>Tags</th>
											<th width="300px">Description</th>
											<th>Creator</th>
											<th>Assignment</th>
											<th>Start at</th>
											<th>Finished at</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($this->list->hasRow()) echo $this->render('backend.request.list.row', []); ?>
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
</div>
<?php echo $this->render('backend.request.list.javascript'); ?>
<form class="hidden" method="POST" id="form_delete">
	<input type="hidden" value="<?php echo $this->token ?>" name="token">
	<input type="hidden" value="DELETE" name="_method">
</form>