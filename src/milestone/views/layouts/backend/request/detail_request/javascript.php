<script>
    var new_tags = [];
    $(document).ready(function(){
        $('.new-note-popup').on('click', function(e){
            e.preventDefault();
            $('#noteNewModal').modal('show');
        })
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

        $("#assignment").select2({
            matcher: matchCustom,
            ajax: {
                url: "<?php echo $this->link_user_search ?>",
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

        var tags = <?php echo json_encode($this->request['tags']);?>;
        var assignments = <?php echo json_encode($this->request['assignment']);?>;
        $('#select_tags').val('').trigger('change');

        if (Array.isArray(tags))
        {
            tags.forEach(function(item,index){
                var newOption = new Option(item.name, item.id, true, true);
                $('#select_tags').append(newOption).trigger('change');
            });
        }
        if (Array.isArray(assignments))
        {
            assignments.forEach(function(item,index){
                var newOption = new Option(item.name, item.id, true, true);
                $('#assignment').append(newOption).trigger('change');
            });
        }
        setTags();
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
    function activeMenu(link)
    {
        var link_active = link.split('#')[1];
        if (link_active)
        {
            $('a.sidebar-link').each(function () {
                var currLink = $(this);
                var href = currLink.attr("href");
                var refElement = href.split('#')[1];
                if (refElement == link_active) {
                    $('li.sidebar-item  a.sidebar-link').removeClass("active");
                    currLink.parent('li.sidebar-item').addClass("active");
                }
                else{
                    currLink.parent('li.sidebar-item').removeClass("active");
                }
            });
        }
        

        var toogleMenu = {
            'relate_note_link' : 'collapseRelateNote',
            'document_link' : 'document_form',
            'task_link' : 'collapseTask',
            'version_link' : 'collapseChangeLog',
        };

        if (toogleMenu[link_active])
        {
            $('#' + toogleMenu[link_active]).collapse('show');
            $('#' + toogleMenu[link_active]).parent(".col-12").find('.icon-collapse').toggleClass('fa-caret-down fa-caret-up');
        }
        else
        {
            $('#' + toogleMenu['relate_note_link']).collapse('show');
            $('#' + toogleMenu['relate_note_link']).parent(".col-12").find('.icon-collapse').toggleClass('fa-caret-down fa-caret-up');
        }
        $("#list-discussion").scrollTop($("#list-discussion")[0].scrollHeight);
    }
    
	function showMessage(status, message)
    {
        if (status == 'ok')
        {
            $('#message_form').addClass('alert-success');
            $('#message_form').removeClass('alert-danger');
        }else{
            $('#message_form').removeClass('alert-success');
            $('#message_form').addClass('alert-danger');
        }

        $('#message_form .toast-body').text(message);
        $("#message_ajax").toast('show');
    }
	
    $('.request-collapse').on('click', function() {
        $('.icon-collapse', this).toggleClass('fa-caret-down fa-caret-up');
    });

	$(document).ready(function() {
        activeMenu(window.location.href);
        $('a.sidebar-link').on('click', function(){
            var href = $(this).attr('href');
            activeMenu(href);
        });
	});
</script>