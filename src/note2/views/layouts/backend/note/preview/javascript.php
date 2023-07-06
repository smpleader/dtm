<script>
    $(document).ready(function(e) {
        $("#sidebarToggle").click(function() {
            $("#col-8").toggleClass("col-lg-12");
            $("#col-4").toggleClass("col-lg-0 d-none");
            $("#sidebarToggle i").toggleClass('fa-caret-right fa-caret-left');
            window.dispatchEvent(new Event('resize'));
            <?php if ($this->data['type'] == 'presenter') {?>
                reRender();
            <?php } ?>
        });

        <?php if ($this->data['type'] == 'presenter') : ?>
            $('#editor-canvas').addClass('pe-none');
            $('#fabric_tool_menu').addClass('d-none');
            $('#fabric_slide_menu .add-button').addClass('d-none');
            $('#fabric_slide_menu .remove-button').addClass('d-none');
        <?php endif; ?>
    });

    function openModeEditor() {
        var value = $('input[name="type"]:checked').val();
        if (value=='html')
        {
            if (view_mode)
            {
                $("#content").removeClass("d-none");
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

    $(document).ready(function() {
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
        $(".button_download_item").click(function() {
            var id = $(this).data('id');
            if (id) {
                $('#form_delete').attr('action', '{$this->link_form_download_attachment}' + id);
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