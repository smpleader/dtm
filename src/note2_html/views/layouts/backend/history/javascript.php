<script>
     $(document).ready(function(e) {
        $(".button-rollback").click(function(e) {
            e.preventDefault();
            var result = confirm("You are going to rollback. Are you sure ?");
            if(result)
            {
                $('#form_submit').submit();
                return true;
            }
            
            return false;
        });
    });
</script>