<form class="hidden" method="POST" id="form_delete_relate_note">
    <input type="hidden" value="<?php echo $this->token ?>" name="token">
    <input type="hidden" value="DELETE" name="_method">
</form>
<script>
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
                if (Array.isArray(resultData.result))
                {
                    resultData.result.forEach(function(item)
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
                        `
                    });
                    $("#listRelateNote").html(list);
                    modalEdit();
                }
            }
        })
    }
    $(document).ready(function() {
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