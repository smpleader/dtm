<script>
    function loadShortcut()
    {
        $.ajax({
            url: '<?php echo $this->link_shortcut_list; ?>',
            type: 'GET',
            success: function(result) {
                var html = '';
                var list = result.list;
                if (list)
                {
                    list.forEach(function(value, index) {
                        html += `<div class="col-4 mb-3">`;
                        if (value.childs)
                        {
                            html += `<div>
                                    <h4 style="min-height: 21px;" class="mb-3">${value.group}</h4>
                                    <table class="table border-top border-1">
                                        <tbody>`;
                            value.childs.forEach(function(child){
                                html += `<tr><td><a href="${child.link}">${child.name}</a></td>
                                            <td class="action" width="100">
                                                <a class="fs-4 me-2 button-shortcut" 
                                                    data-bs-placement="top" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#shortcutModel"
                                                    data-link="<?php echo $this->link_shortcut_form;?>/${child.id}"
                                                    data-id="${child.id}" 
                                                    data-name_shortcut ="${child.name}" 
                                                    data-link_shortcut ="${child.link}"
                                                    data-group_shortcut ="${child.group}"
                                                >
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a class="fs-4 remove-shortcut" data-id="${child.id}" 
                                                >
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                            `;
                            });
                            html += '</tbody></table></div>';
                        }
                        else
                        {
                            html += `
                            <table class="table border-top border-1">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="${value.link}">${value.name}</a>
                                            </td>
                                            <td class="action" width="100">
                                                <a class="fs-4 me-2 button-shortcut" 
                                                    data-bs-placement="top" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#shortcutModel"
                                                    data-link="<?php echo $this->link_shortcut_form?>/${value.id}"
                                                    data-id="${value.id}" 
                                                    data-name_shortcut ="${value.name}" 
                                                    data-link_shortcut ="${value.link}"
                                                    data-group_shortcut ="${value.group}"
                                                >
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a class="fs-4 remove-shortcut" data-id="${value.id}" 
                                                >
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>`;
                        }
                        html +='</div>';
                    });
                }

                $('.shortcut-list').html(html);
                loadEventShortcut();
            }
        });
    }

    $(document).ready(function(){
        $('#form_shortcut').on('submit', function(e){
            e.preventDefault();
            var form = new FormData($('#form_shortcut')[0])
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                success: function(result) {
                    if (result.status != 'done') {
                        var message = result.message ? result.message : 'Save Failed';
                        alert(result.message);
                    } 
                    
                    loadShortcut();
                    location.reload();
                    $('#name_shortcut').val('');
                    $('#link_shortcut').val('');
                    $('#group_shortcut').val('');
                    $('#shortcutModel').modal('hide');
                }
            });
        })
    })
</script>