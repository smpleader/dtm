<div class="modal fade" id="noteNewModal" aria-labelledby="noteNewModalTitle" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="noteNewModalTitle">New Note</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex justify-content-around">
				<?php foreach($this->note_types as $type) : ?>
					<h4 class="text-nowrap">
						<a  target="_blank" class="mx-3" href="<?php echo $type['link']?>"><?php echo $type['title']?></a>
					</h4>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>