<script>
    
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    function listTask(data)
    {
        $.ajax({
            url: '<?php echo $this->link_list ?>',
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
                                <input class="checkbox-item" type="checkbox" name="ids[]" value="${item['id']}">
                            </td>
                            <td>
                                <a href="#"
                                    class="show_data" 
                                    data-id="${item['id']}" 
                                    data-title="${item['title']}" 
                                    data-url="${item['url']}" 
                                    data-bs-placement="top" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#Popup_form_task">
                                    ${item['title']}
                                </a>
                            </td>
                            <td><a href="${item['url']}">${item['url']}</a></td>
                            <td>
                                <a href="#>" 
                                    class="fs-4 me-1 show_data"
                                    data-id="${item['id']}" 
                                    data-title="${item['title'] }" 
                                    data-url="${item['url']}"
                                    data-bs-placement="top" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#Popup_form_task">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                        `
                    });
                    $("#listTask").html(list);
                    $(".show_data").click(function() {
                        var id = $(this).data('id');
                        var title = $(this).data('title');
                        var url = $(this).data('url');

                        $('#title').val(title);
                        $('#url').val(url);

                        $('#form_task').attr('action', '<?php echo $this->link_form;?>/' + id);
                        if(id) {
                            $('#task').val('PUT');
                        } else {
                            $('#task').val('POST');
                        }
                    });
                }
            }
        })
    }
    $(document).ready(function() {
        $("#form_task").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#form_task').attr('action'),
                data: $('#form_task').serialize(),
                success: function (result) {
                    modal = bootstrap.Modal.getInstance($('#Popup_form_task'))
                    modal.hide();
                    showMessage(result.result, result.message);
                    listTask($('#filter_form_task').serialize());
                }
            });
        });
        $("#select_all").click( function(){
            $('.checkbox-item').prop('checked', this.checked);
        });
        $(".show_data").click(function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var url = $(this).data('url');

            $('#title').val(title);
            $('#url').val(url);

            $('#form_task').attr('action', '<?php echo $this->link_form;?>/' + id);
            if(id) {
                $('#task').val('PUT');
            } else {
                $('#task').val('POST');
            }
        });
        $(".button_delete_item").click(function() {
            var id = $(this).data('id');
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (result) {
                $('#form_delete').attr('action', '<?php echo $this->link_form;?>/' + id);
                $.ajax({
                    type: 'POST',
                    url: $('#form_delete').attr('action'),
                    data: $('#form_delete').serialize(),
                    success: function (result) {
                        showMessage(result.result, result.message);
                        listTask($('#filter_form_task').serialize());

                    }
                });
            }
            else
            {
                return false;
            }
        });
        $('#delete_selected').click(function(){
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
                    url: $('#formListTask').attr('action'),
                    data: $('#formListTask').serialize(),
                    success: function (result) {
                        showMessage(result.result, result.message);
                        listTask($('#filter_form_task').serialize());

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
        $('#filter_form_task').on('submit', function (e){
            e.preventDefault();
            listTask($(this).serialize());
        });
    });
    document.getElementById('clear_filter_task').onclick = function() {
        document.getElementById("search_task").value = "";
        listTask($('#filter_form_task').serialize());
    };
</script>