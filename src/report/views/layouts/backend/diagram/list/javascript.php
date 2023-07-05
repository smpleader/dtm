<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    document.getElementById('clear_filter').onclick = function() {
        document.getElementById("search").value = "";
        document.getElementById("sort").value = "title asc";
        document.getElementById("status").value = "";
        document.getElementById('filter_form').submit();
    };
    $(document).ready(function() {
        $('.toast').toast('show');
        $("#select_all").click( function(){
            $('.checkbox-item').prop('checked', this.checked);
        });
        $(".button_delete_item").click(function() {
            var id = $(this).data('id');
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (result) {
                $('#form_delete').attr('action', '<?php echo $this->link_form;?>/' + id);
                $('#form_delete').submit();
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
                $('#formList').submit();
            }
            else
            {
                return false;
            }
        });
        $('#limit').on("change", function (e) {
            $('#filter_form').submit()
        });
        $(".show_data").click(function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var status = $(this).data('status');
            var start_date = $(this).data('start_date');
            var end_date = $(this).data('end_date');
            var description = $(this).data('description');

            $('#title').val(title);
            $('input[name=status][value='+ status +']').prop("checked", true); 
            $('#end_date').val(end_date);
            $('#start_date').val(start_date);
            $('#description').val(description);

            $('#form_milestone').attr('action', '<?php echo $this->link_form;?>/' + id);
            if(id) {
                $('#milestone').val('PUT');
            } else {
                $('#milestone').val('POST');
            }
        });
        $('.toggle_status').on('click', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).data('status');
            $('.toogle_status_id').val(id);
            $('#form_update').submit();
        });

    });
</script>
<script>
    var ignores = [];
    $(document).ready(function(){
        $('.show_data').on('click', function(){
            var id = $(this).data('id') ?? 0;
            var title = $(this).data('title') ?? '';
            var assignment = $(this).data('assignment') ?? '';
            
            $('#form_report').attr('action', '<?php echo $this->link_form ?>/' + id);
            $('#form_report #title').val(title);
            
            //clear all
            $('#assignment').val(null).trigger('change');
            if (assignment)
            {
                assignment.forEach(element => {
                    var newOption = new Option(element.name, element.id, true, true);
                    $('#assignment').append(newOption).trigger('change');
                });
            }
        });
    })
</script>
<?php
$js = <<<Javascript
$(document).ready(function() {
    $("#assignment").select2({
        matcher: matchCustom,
        ajax: {
            url: "{$this->link_search}",
            dataType: 'json',
            delay: 100,
            data: function(params) {
                return {
                    search: params.term,
                    ignores: ignores
                };
            },
            processResults: function(data, params) {
                let items = [];
                if (data.data.length > 0) {
                    data.data.forEach(function(item) {
                        items.push({
                            id: item.id,
                            text: item.name
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
        placeholder: 'Users',
        dropdownParent: $("#formEdit"),
        minimumInputLength: 1,
    });
    function matchCustom(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
            return null;
        }

        // Return `null` if the term should not be displayed
        return null;
    }
  });
Javascript;

$this->theme->addInline('js', $js);