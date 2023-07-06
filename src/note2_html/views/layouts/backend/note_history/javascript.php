<script>
    var view_mode = '<?php echo $this->view_mode ?>';
    $(document).ready(function(e) {
        var editor = '<?php echo $this->data ? $this->data['editor'] : '' ?>';
        fabric.Object.prototype.selectable = false;
        canvas.selection = false;
        $('#editPosition').html('');
        $('#fabric_tool_menu').html('');
        $('#fabric_slide_menu .add-button').remove();
        $('#fabric_slide_menu .remove-button').remove();

        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            var result = confirm("You are going to rollback. Are you sure ?");
            if (result) {
                $("#save_close").val(1);
                $('#form_submit').submit();
            }
            else
            {
                return false;
            }
            
            
        });

        if(view_mode) {
            $("#save_and_close").addClass("d-none");
            $("#apply").addClass("d-none");
            $("#save_and_close_header").addClass("d-none");
            $("#apply_header").addClass("d-none");
            $("#col-8").addClass("col-lg-12");
            $("#col-4").addClass("col-lg-0 d-none");
            $(".button-editor-mode").addClass('d-none');
            openModeEditor();
        } else {
            $("#html_editor").removeClass("d-none");
            $("#check_mode").removeClass("d-none");
            $("#content").removeClass("border");
            $("#save_and_close").removeClass("d-none");
            $("#apply").removeClass("d-none");
            $("#save_and_close_header").removeClass("d-none");
            $("#apply_header").removeClass("d-none");
            openModeEditor();
        }

        $("#sidebarToggle").click(function() {
            $("#col-8").toggleClass("col-lg-12");
            $("#col-4").toggleClass("col-lg-0 d-none");
            reRender();
            window.dispatchEvent(new Event('resize'));
        });
    });

    function openModeEditor() {
        var value = $('input[name="editor"]:checked').val();
        if (value=='tynimce')
        {
            if (view_mode)
            {
                $("#content").removeClass("d-none");
                $("#content").addClass("border");
                $('#html_editor').addClass('d-none');
            }
            else
            {
                $('#html_editor').removeClass('d-none');
                $('#sheet_description_sheetjs').addClass('d-none');
                $('#presenter_editor').addClass('d-none');
            }
        }

        if (value=='sheetjs')
        {
            $('#html_editor').addClass('d-none');
            $('#sheet_description_sheetjs').removeClass('d-none');
            $('#presenter_editor').addClass('d-none');
        }

        if (value=='presenter')
        {
            $('#html_editor').addClass('d-none');
            $('#sheet_description_sheetjs').addClass('d-none');
            $('#presenter_editor').removeClass('d-none');
            reRender();
        }
    }
</script>
<?php
$js = <<<Javascript
    var new_tags = [];
    $(".js-example-tags").select2({
        tags: true,
        createTag: newtag,
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
        minimumInputLength: 2,
    });

    function newtag(params, data) {
        var term = $.trim(params.term);
        if (term === '') {
            return null;
        }

        return {
            id: term,
            text: term,
            newTag: true // add additional parameters
        }
    }

    $('.js-example-tags').on('select2:select', async function(e) {
        let tag = e.params.data;
        if (tag.newTag === true) {
            await $.post("{$this->link_tag}", {
                    name: tag.text
                })
                .done(function(data) {
                    new_tags.push({
                        id: data.data.id,
                        text: data.data.name
                    })

                    setTags();
                });
        } else {
            setTags();
        }
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

    $(document).ready(function() {
        if (!$('#sheetToogle').is(":checked"))
        {
            $('#sheet_description_sheetjs').addClass('d-none');
        }
        else
        {
            $('#html_editor').addClass('d-none');
            $('#content').addClass('d-none');
            window.dispatchEvent(new Event('resize'));
        }

        $('.clear-version').on('click', function(){
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (result) {
                var version_id = $(this).data('version-id');
                $('#form_submit').attr('action', '{$this->link_form}/version/' + version_id);
                $('input[name="_method"').val('DELETE');
                $('#form_submit').submit();
            }
            else
            {
                return false;
            }
        });
        $('input[name="editor"]').change(function()
        {
            openModeEditor();
        });
        $("#description").attr('rows', 25);
        $(".button_delete_item").click(function() {
            var id = $(this).data('id');
            var result = confirm("You are going to delete 1 file(s) attchament. Are you sure ?");
            if (result) {
                $('#form_delete').attr('action', '{$this->link_form_attachment}' + id);
                $('#form_delete').submit();
            }
            else
            {
                return false;
            }
        });
    });
Javascript;

$this->theme->addInline('js', $js);
?>