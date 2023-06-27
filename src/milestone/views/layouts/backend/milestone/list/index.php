<?php echo $this->render('notification', []); ?>
<div class="main">
	<main class="content p-0 ">
		<div class="container-fluid p-0">
			<div class="row justify-content-center mx-auto">
				<div class="col-12 p-0">
					<div class="card border-0 shadow-none">
						<div class="card-body">
							<div class="row align-items-center">
								<?php echo $this->render('backend.milestone.list.filter', []); ?>
							</div>
							<div class="row align-items-center">
								<?php echo $this->render('backend.milestone.form', []); ?>
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
											<th>Description</th>
											<th>Status</th>
											<th>Start Date</th>
											<th>End Date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($this->list->hasRow()) echo $this->render('backend.milestone.list.row', []); ?>
									</tbody>
									<?php
									?>
								</table>
							</form>
							<div class="row g-3 align-items-center">
								<?php echo $this->render('pagination', []); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</main>
</div>
<div aria-live="polite" aria-atomic="true" class="position-relative">
	<!-- Position it: -->
	<!-- - `.toast-container` for spacing between toasts -->
	<!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
	<!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
	<div class="toast-container position-absolute top-0 end-0 p-3">

		<!-- Then put toasts within -->
		<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
				<img src="..." class="rounded me-2" alt="...">
				<strong class="me-auto">Bootstrap</strong>
				<small class="text-muted">just now</small>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
				See? Just like this.
			</div>
		</div>

		<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
				<img src="..." class="rounded me-2" alt="...">
				<strong class="me-auto">Bootstrap</strong>
				<small class="text-muted">2 seconds ago</small>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
				Heads up, toasts will stack automatically
			</div>
		</div>
	</div>
</div>