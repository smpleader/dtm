<?php echo $this->renderWidget('core::notification'); ?>
<div id="version_link" class="pt-3">
	<div class="container-fluid">
		<div class="row justify-content-center mx-auto">
			<div class="col-12">
				<h2 class="pb-1" >
					<i class="fa-solid fa-code-branch pe-2"></i>
					<?php echo $this->title_page_version ?>
				</h2>
				<?php if ($this->version_latest['id']) { ?>
					<div id="collapseChangeLog" class="mb-5 row align-items-center justify-content-center pt-3">
						<div class="col-lg-8 col-12" id="listChangeLog">
							<?php foreach ($this->list as $item) : ?>
								<form class="form_changelog" action="<?php echo $this->link_form . '/' . $item['id']; ?>" method="post">
									<input type="hidden" value="<?php echo $this->token ?>" name="token">
									<input type="hidden" id="method_<?php echo $item['id'] ?>" value="PUT" name="_method">
									
									<div class="input-group mb-3">
										<?php if(!$this->status) {?>
										<input class="form-control rounded-0 border border-1" name="log" required value="<?php echo $item['log'] ?>"></input>
										<button class="btn btn-outline-secondary" type="submit">Apply</button>
										<button class="btn btn-outline-secondary button-remove" data-id-remove="<?php echo $item['id']; ?>">Remove</button>
										<?php } ?>
									</div>
								</form>
							<?php endforeach; ?>
							<form class="form_changelog" action="<?php echo $this->link_form . '/0' ?>" method="post">
								<input type="hidden" value="<?php echo $this->token ?>" name="token">
								<input type="hidden" value="POST" name="_method">
								<div class="input-group mb-3">
									<?php if(!$this->status) {?>
									<input class="form-control rounded-0 border border-1" required name="log"></input>
									<button class="btn btn-outline-secondary" type="submit">Add</button>
									<?php } ?>
								</div>
							</form>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->render('backend.version_latest.list.javascript', ['link_list' => $this->link_list, 'link_form' => $this->link_form]); ?>
