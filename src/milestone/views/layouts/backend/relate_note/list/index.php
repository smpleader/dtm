<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<a class="relate-note-popup"><h4>Request Notes</h4></a>
			<table id="datatables-buttons" class="table table-striped border-top border-1" style="width:100%">
				<thead>
					<tr>
						<th width="10px">
							#
						</th>
						<th>Note</th>
						<th>Alias</th>
					</tr>
				</thead>
				<tbody id="listAliasNote">
					<?php foreach($this->result as $index => $item) : ?>
					<tr>
						<td><?php echo $index + 1?></td>
						<td>
							<a target="_blank" href="<?php echo $this->link_note. '/'. $item['note_id']; ?>"><?php echo  $item['title']  ?></a>
						</td>
						<td><?php echo $item['alias']?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<?php
				?>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" id="relateNoteList" aria-labelledby="relateNoteListTitle" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="relateNoteListTitle">Relate Notes</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div>
					<div class=" row align-items-center pt-3">
						<?php echo $this->render('backend.relate_note.list.filter', []); ?>
					</div>
					<form action="<?php echo $this->link_list ?>" method="POST" id="formListRelateNote">
						<input type="hidden" value="<?php echo $this->token ?>" name="token">
						<input type="hidden" value="DELETE" name="_method">
						<table id="datatables-buttons" class="table table-striped border-top border-1" style="width:100%">
							<thead>
								<tr>
									<th width="10px">
										<input type="checkbox" id="select_all_relate_note">
									</th>
									<th>Title Note</th>
									<th>Alias</th>
									<th>Description</th>
									<th>Tags</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="listRelateNote">
								<?php while ($this->list->hasRow()) echo $this->render('backend.relate_note.list.row', ['item' => $this->list->getRow(), 'index' => $this->list->getIndex(), 'link_note' => $this->link_note]); ?>
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
<form class="hidden" method="POST" id="form_delete_relate_note">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="DELETE" name="_method">
</form>
<?php echo $this->render('backend.relate_note.form'); ?>
<?php echo $this->render('backend.relate_note.list.javascript', ['link_update_relate_note' => $this->link_update_relate_note]); ?>