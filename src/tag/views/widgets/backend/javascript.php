<script>
    $(document).ready(function() {
        $("#tags").select2({
            matcher: matchCustom,
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true // add additional parameters
                }
            },
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
            placeholder: 'Tags',
            minimumInputLength: 1,
        });

        $('#tags').on('select2:select', async function(e) {
            var data = e.params.data;
            if (data.newTag)
            {
                var form = new FormData();
                form.append('name', data.text);
                selected = $('#tags').select2('data');
                $.ajax({
                    url: '<?php echo $this->link_add_tag; ?>',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: form,
                    success: function(result) {
                        if (result.status != 'done') {
                            var message = result.message ? result.message : 'Create Tag Failed';
                            alert(result.message);
                        }else{
                            $('#tags').val(null).trigger('change');
                            id = result.id;
                            // update selected
                            selected.forEach(function(value, index){
                                if(value.id == data.text && value.newTag)
                                {
                                    value.id = id;
                                }

                                var newOption = new Option(value.text, value.id, true, true);
                                $('#tags').append(newOption).trigger('change');
                            })
                        }
                    }
                });
            }

            return true;
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
</script>