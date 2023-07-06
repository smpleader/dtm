<script>
    var new_tags = [];
    $(document).ready(function(){
        $(".js-example-tags").select2({
            tags: <?php echo $this->allow_tag ?>,
            matcher: matchCustom,
            ajax: {
                url: "<?php echo $this->link_tag ?>",
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
            placeholder: 'Search tags',
            minimumInputLength: 1,
        });

        $('.js-example-tags').on('select2:select', async function(e) {
            setTags();
        });

        $('.js-example-tags').on('select2:unselect', function(e) {
            setTags();
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

        
    })
    function setTags() {
        let tmp_tags = $('#select_tags').val();
        if (tmp_tags.length > 0) {
            var items = [];

            if (new_tags.length > 0) {
                tmp_tags.forEach(function(item, key) {
                    let ck = false;
                    new_tags.forEach(function(item2, key2) {

                        if (item == item2.text)
                            ck = item2
                    })

                    if (ck === false)
                        items.push(item)
                    else
                        items.push(ck.id)
                })
            } else items = tmp_tags

            $('#tags').val(items.join(','))
        } else {
            $('#tags').val('')
        }
    }
</script>
<?php
$js = <<<Javascript
$(document).ready(function() {
    $("#assignment").select2({
        matcher: matchCustom,
        ajax: {
            url: "{$this->link_user_search}",
            dataType: 'json',
            delay: 100,
            data: function(params) {
                return {
                    search: params.term,
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

$this->theme->addInline('js', $js); ?>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    document.getElementById('clear_filter').onclick = function() {
        document.getElementById("search").value = "";
        document.getElementById("sort").value = "title asc";
        $('#filter_tags').val(null).trigger('change');
        document.getElementById("input_clear_filter").value = 1;
        document.getElementById('filter_form').submit();
    };
    $(document).ready(function() {
        $("#select_all").click( function(){
            $('.checkbox-item:not(:disabled)').prop('checked', this.checked);
            
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
            var description = $(this).data('description');
            var start_at = $(this).data('start_at');
            var finished_at = $(this).data('finished_at');
            var tags = $(this).data('tags');
            $('#select_tags').val('').trigger('change');

            
            var assignment = $(this).data('assignment') ?? '';
            //clear all
            $('#assignment').val(null).trigger('change');
            if (assignment)
            {
                assignment.forEach(element => {
                    var newOption = new Option(element.name, element.id, true, true);
                    $('#assignment').append(newOption).trigger('change');
                });
            }

            if (Array.isArray(tags))
            {
                tags.forEach(function(item,index){
                    var newOption = new Option(item.name, item.id, true, true);
                    $('#select_tags').append(newOption).trigger('change');
                });
            }
            setTags();

            $('#title').val(title);
            $('#description').val(description);
            $('#start_at').val(start_at);
            $('#finished_at').val(finished_at);

            $('#form_request').attr('action', '<?php echo $this->link_form;?>/' + id);
            if(id) {
                $('#request').val('PUT');
            } else {
                $('#request').val('POST');
            }
        });
    });
</script>

<?php
$js = <<<Javascript
    $(document).ready(function(){
        var filter_tags = {$this->filter_tags};
        
        $("#filter_tags").select2({
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
            placeholder: 'Search tags',
            minimumInputLength: 1,
        });

        $('#filter_tags').val(null).trigger('change');
        var selected_tag = [];
        if (Array.isArray(filter_tags))
        {
            filter_tags.forEach(function(item,index){
                var newOption = new Option(item.name, item.id, true, true);
                selected_tag.push(item.id);
                $('#filter_tags').append(newOption);
            });
            $('#filter_tags').val(selected_tag).trigger('change');
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