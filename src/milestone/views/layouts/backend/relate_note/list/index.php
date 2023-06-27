<?php echo $this->render('notification', []); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<h4>Request Notes</h4>
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
<?php echo $this->render('backend.relate_note.form', []); ?>
<script>
	 $(document).ready(function(){
        $('.relate-note-popup').on('click', function(e){
            e.preventDefault();
            $('#relateNoteList').modal('show');
        })
	});

	function modalEdit()
    {
        $('.open-edit-relate').off('click').on('click', function(e){
            e.preventDefault();

            var title = $(this).data('title-note');
			var id = $(this).data('id');
			var alias = $(this).data('alias');
			$('#note_title').text(title);
			$('#alias').val(alias);
			$('#form_update_relate_note').attr('action', '<?php echo $this->link_update_relate_note; ?>/' + id);

            $('#relateEdit').modal('show');
        });
    }
</script>