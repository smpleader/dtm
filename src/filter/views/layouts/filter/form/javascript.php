<script>
    $(document).ready(function(e) {
        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            $("#save_close").val(1);
            $('#button_save').click();
        });

        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $("#save_close").val(0);
            $('#button_save').click();
        });

        $("#tags").select2({
            matcher: matchCustom,
            tags: true,
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
            placeholder: 'Tag',
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
</script>