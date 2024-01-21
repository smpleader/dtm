<script>
    var ignores = [];

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    document.getElementById('clear_filter').onclick = function() {
        document.getElementById("search").value = "";
        document.getElementById("sort").value = "title asc";
        document.getElementById('filter_form').submit();
    };
    $(document).ready(function() {
        $('.show_data').on('click', function(){
            var id = $(this).data('id') ?? 0;
            var name = $(this).data('name') ?? '';
            var description = $(this).data('description') ?? '';
            var parent_id = $(this).data('parent_id') ?? '';
            var parent_tag = $(this).data('parent_tag')?? '';

            $('#form_tag').attr('action', '<?php echo $this->link_form ?>/' + id);
            $('#form_tag #name').val(name);
            $('#form_tag #description').val(description);
            
            if (id)
            {
                ignores=[parent_id];
                $('#_method').val('PUT');
            }
            else
            {
                ignores= [];
                $('#_method').val('POST');
            }

            //clear all
            $('#parent_id').val(null).trigger('change');
            if (parent_id && parent_tag)
            {
                var newOption = new Option(parent_tag, parent_id, false, false);
                $('#parent_id').append(newOption).trigger('change');
            }
        });

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
    });
</script>
<?php
$js = <<<Javascript
$(document).ready(function() {
    $("#parent_id").select2({
        matcher: matchCustom,
        ajax: {
            url: "{$this->link_search}",
            dataType: 'json',
            delay: 250,
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
        placeholder: 'Parent Tag',
        dropdownParent: $("#formEditTag"),
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