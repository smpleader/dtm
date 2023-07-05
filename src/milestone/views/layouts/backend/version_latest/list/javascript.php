<script>
    function listChangeLog(data) {
        $.ajax({
            url: '<?php echo $this->url . 'request-versions/' . $this->request_id ?>',
            type: 'POST',
            data: data,
            success: function(resultData) {
                var list = '';
                if (Array.isArray(resultData.result)) {

                    resultData.result.forEach(function(item) {
                        list += `
						<form class="form_changelog" action="<?php echo $this->link_form . '/' ?>${item['id']}" method="post">
							<input type="hidden" value="<?php echo $this->token ?>" name="token">
							<input type="hidden" id="method_${item['id']}" value="PUT" name="_method">
							<div class="input-group mb-3">
								<input class="form-control rounded-0 border border-1" name="log" value="${item['log']}"></input>
								<button class="btn btn-outline-secondary" type="submit">Apply</button>
								<button class="btn btn-outline-secondary button-remove" data-id-remove="${item['id']}">Remove</button>
							</div>
						</form>
                        `
                    });
                    list += `
					<form class="form_changelog" action="<?php echo $this->link_form . '/0' ?>" method="post">
						<input type="hidden" value="<?php echo $this->token ?>" name="token">
						<input type="hidden" value="POST" name="_method">
						<div class="input-group mb-3">
							<input class="form-control rounded-0 border border-1" name="log" ></input>
							<button class="btn btn-outline-secondary" type="submit">Add</button>
						</div>
					</form>
					`;
                    $("#listChangeLog").html(list);
                    $('.form_changelog').on('submit', formChangeLogSubmit);
                    $(".button-remove").click(function() {
                        var id = $(this).data('id-remove');
                        $("#method_" + id).val('DELETE');
                    });
                }
            }
        });

    }

    function formChangeLogSubmit(e) {
        console.log();
        if (e) {
            e.preventDefault();
        }
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(result) {
                showMessage(result.result, result.message);
                listChangeLog();
            }
        });
        return false;
    }
    $('.form_changelog').on('submit', formChangeLogSubmit);

    $(document).ready(function() {
        $(".button-remove").click(function() {
            var id = $(this).data('id-remove');
            $("#method_" + id).val('DELETE');
        });
    });
</script>