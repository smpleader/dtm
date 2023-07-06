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
    
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    function listRelateNote(data)
    {
        $.ajax({
            url: '<?php echo $this->link_list_relate_note ?>',
            type: 'POST',
            data: data,
            success: function(resultData)
            {
                var list = '';
                var list_alias = '';
                if (Array.isArray(resultData.result))
                {
                    resultData.result.forEach(function(item, index)
                    {
                        list += `
                        <tr>
                            <td>
                                <input class="checkbox-item-relate-note" type="checkbox" name="ids[]" value="${item['id']}">
                            </td>
                            <td>
                                <a target="_blank" href="<?php echo $this->link_note .'/' ?>${item['note_id']}">${item['title']}</a>
                            </td>
                            <td>${item['alias'] ?? ''}</td>
                            <td><span class="relate-note-description">${item['description']}</span></td>
                            <td>${item['tags']}</td>
                            <td><a type="button" class="fs-3 open-edit-relate" data-id="${item['id']}" data-title-note="${item['title']}" data-alias="${item['alias']}"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        </tr>
                        `;
                        list_alias += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <a target="_blank" href="<?php echo $this->link_note .'/' ?>${item['note_id']}">${item['title']}</a>
                            </td>
                            <td>${item['alias'] ?? ''}</td>
                        </tr>
                        `;
                    });
                    $("#listRelateNote").html(list);
                    $("#listAliasNote").html(list_alias);
                    modalEdit();
                }
            }
        })
    }
    $(document).ready(function() {
        $("#note_id").select2({
            matcher: matchCustom,
            placeholder: 'Select Notes',
            minimumInputLength: 1,
            multiple: true,
            dropdownParent : "#formRelateNote",
            closeOnSelect: false,
            ajax: {
                url: '<?php echo $this->url. 'get-notes/'. $this->request_id ?>',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function(data, params) {
                    let items = [];
                    let list = data.result;
                    if (Array.isArray(list) && list.length > 0) {
                        list.forEach(function(item) {
                            items.push({
                                id: item.id,
                                text: item.title
                            })
                        })
                    }

                    return {
                        results: items,
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true
            },
        });

        function matchCustom(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            // Return `null` if the term should not be displayed
            return null;
        }
        $("#form_relate_note").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->link_form .'/0' ?>',
                data: $('#form_relate_note').serialize(),
                success: function (result) {
                    modal = bootstrap.Modal.getInstance($('#formRelateNote'))
                    modal.hide();
                    $('#note_id').val(null).trigger('change');
                    showMessage(result.result, result.message);
                    listRelateNote($('#filter_form').serialize());
                }
            });
        });

        $("#form_update_relate_note").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#form_update_relate_note').attr('action'),
                data: $('#form_update_relate_note').serialize(),
                success: function (result) {
                    modal = bootstrap.Modal.getInstance($('#relateEdit'))
                    modal.hide();
                    showMessage(result.result, result.message);
                    listRelateNote($('#filter_form').serialize());
                }
            });
        });

        modalEdit();
        $("#select_all_relate_note").click( function(){
            $('.checkbox-item-relate-note').prop('checked', this.checked);
        });
        $(".button_delete_item_relate_note").click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->link_form;?>/' + id,
                    data: $('#form_delete_relate_note').serialize(),
                    success: function (result) {
                        showMessage(result.result, result.message);
                        listRelateNote($('#filter_form').serialize());
                    }
                });
            }
            else
            {
                return false;
            }
        });
        $('#delete_relate_note_selected').click(function(){
            var count = 0;
            $('input[name="ids[]"]:checked').each(function() {
                count++;
            });
            if (!count)
            {
                alert('Please select the record before deleting!')
                return false;
            }
            var result = confirm("You are going to delete " + count + " record(s). Are you sure ?");
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: $('#formListRelateNote').attr('action'),
                    data: $('#formListRelateNote').serialize(),
                    success: function (result) {
                        showMessage(result.result, result.message);
                        listRelateNote($('#filter_form').serialize());
                    }
                });
            }
            else
            {
                return false;
            }
        });
        $('#limit').on("change", function (e) {
            $('#filter_form').submit()
        });
        $(".show_data_relate_note").click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            $('#formRelateNote').modal('show');
            
        });
        $('#filter_form').on('submit', function (e){
            e.preventDefault();
            listRelateNote($(this).serialize());
        });
    });
    document.getElementById('clear_filter').onclick = function() {
        document.getElementById("search").value = "";
        listRelateNote($('#filter_form').serialize());
    };
</script>