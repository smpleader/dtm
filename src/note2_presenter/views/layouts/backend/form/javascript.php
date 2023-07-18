<script>
     $(document).ready(function(e) {
        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            $("#save_close").val(1);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val())
            {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            data_canvas[canvas_index] = canvas.toJSON();
            $('#data').val(JSON.stringify(data_canvas));
            $('#form_submit').submit();
        });

        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $("#save_close").val(0);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val())
            {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            data_canvas[canvas_index] = canvas.toJSON();
            $('#data').val(JSON.stringify(data_canvas));
            $('#form_submit').submit();
        });
    });
</script>