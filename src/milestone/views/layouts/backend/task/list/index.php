<?php echo $this->renderWidget('core::notification');?>
<div class="pt-2" id="task_link">
	<div class="container-fluid">
		<div class="row justify-content-center mx-auto">
			<div class="col-12">
				<a class="request-collapse w-100 text-decoration-none d-flex border-bottom" data-bs-toggle="collapse" type="button" data-bs-target="#collapseTask" aria-expanded="true" aria-controls="collapseTask">
					<h2>
						<i class="fa-solid fa-list-check pe-2"></i><?php echo $this->title_page_task ?></h2>
                    <h2 class="ms-auto">
                        <i class="icon-collapse fa-solid fa-caret-down"></i>
                    </h2>
                </a>
				<div class="collapse" id="collapseTask">
					<div class="row align-items-center pt-3">
						<?php echo $this->render('backend.task.list.filter', ['link_list' => $this->link_list, 'link_form' => $this->link_form, 'status' => $this->status]);?>
					</div>
					<div class="row align-items-center">
						<?php echo $this->render('backend.task.form', []);?>
					</div>
					<form action="<?php echo $this->link_list ?>" method="POST" id="formListTask">
						<input type="hidden" value="<?php echo $this->token ?>" name="token">
						<input type="hidden" value="DELETE" name="_method">
						<table id="datatables-buttons" class="table table-striped border-top border-1" style="width:100%">
							<thead>
								<tr>
									<th width="10px">
										<input type="checkbox" id="select_all">
									</th>
									<th>Title</th>
									<th>Url</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="listTask">
								<?php while($this->list->hasRow()) echo $this->render('backend.task.list.row', ['item' => $this->list->getRow(), 'index' => $this->list->getIndex(), 'status' => $this->status]); ?> 
							</tbody>
						<?php
						?>
						</table>
					</form>
				</div>
				
			</div>
		</div>

	</div>
</div>
<form class="hidden" method="POST" id="form_delete">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="DELETE" name="_method">
</form>
<?php echo $this->render('backend.task.list.javascript', ['link_list' => $this->link_list, 'link_form' => $this->link_form]); ?>
