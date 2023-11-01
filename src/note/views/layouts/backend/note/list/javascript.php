<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    document.getElementById('clear_filter').onclick = function() {
        document.getElementById("search").value = "";
        document.getElementById("sort").value = "title asc";
        $('#tags').val(null).trigger('change');
        $('#note_type').val(null).trigger('change');
        $('#author').val(null).trigger('change');
        document.getElementById("input_clear_filter").value = 1;
        document.getElementById('filter_form').submit();
    };
    $(document).ready(function() {
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
        $('#sort').on("change", function (e) {
            $('#filter_form').submit()
        });
        $('#limit').on("change", function (e) {
            $('#filter_form').submit()
        });
    });
</script>
</script>
<?php
$js = <<<Javascript
    $(document).ready(function(){
        var filter_tags = {$this->filter_tags};
        
        $("#tags").select2({
            matcher: matchCustom,
            ajax: {
                url: "{$this->link_tag}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
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
            placeholder: 'Tag',
            minimumInputLength: 1,
        });

        $('#tags').val(null).trigger('change');
        var selected_tag = [];
        if (Array.isArray(filter_tags))
        {
            filter_tags.forEach(function(item,index){
                var newOption = new Option(item.name, item.id, true, true);
                selected_tag.push(item.id);
                $('#tags').append(newOption);
            });
            $('#tags').val(selected_tag).trigger('change');
        }
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
?>