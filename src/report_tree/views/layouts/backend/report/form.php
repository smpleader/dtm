<?php
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/treephp/css/style.css', '', 'treephp-css');
$this->theme->add($this->url . 'assets/css/select2_custom.css', '', 'select2-custom-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
?>
<?php echo $this->renderWidget('core::notification'); ?>
<div class="container-fluid align-items-center row justify-content-center mx-auto pt-3">
    <form enctype="multipart/form-data" action="<?php echo $this->link_form . '/' . $this->id ?>" method="post" id="form_submit">
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <input id="input_title" type="hidden" class="d-none" name="title">
                <div class="row">
                    <div class="mb-3 col-lg-12 col-sm-12 mx-auto d-flex">
                        <button class="btn btn-outline-success me-3 add-note-button">Add</button>
                        <div class="w-auto flex-fill">
                            <select multiple name="notes" id="notes" class="d-none">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3 col-lg-12 col-sm-12 mx-auto">
                    <div id="tree_root" class="overflow-auto">
                        <table class="table">
                            <thead>
                                <tr id="item_0" data-level="0" class="item-tree active open" data-id="0">
                                    <th >Root</th>
                                    <th width="35px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                if ($this->data) {
                                    foreach ($this->data['list_tree'] as $item) {
                                ?>
                                <tr data-text="<?php echo $item['title'];?>" id="item_<?php echo $item['note_id'] ?>" data-level="<?php echo $item['tree_level'] ?>" class="item-tree" data-id="<?php echo $item['note_id'] ?>" data-parent="<?php echo $item['parent_id'] ?>" data-position="<?php echo $item['tree_position'] ?>">
                                    <td ><?php echo str_repeat('&nbsp; &nbsp; &nbsp; &nbsp;', (int) $item['tree_level']-1). '<span class="title"> |&mdash; ' .$item['title'] ?></span></td>
                                    <td> 
                                        <div class="d-flex justify-content-end">
                                            <a class="up-note me-2 d-none" data-id="<?php echo $item['note_id'] ?>"><i class="fa-solid fa-arrow-up"></i></a>
                                            <a class="down-note me-2 d-none" data-id="<?php echo $item['note_id'] ?>"><i class="fa-solid fa-arrow-down"></i></a>
                                            <a class="remove-note" data-id="<?php echo $item['note_id'] ?>"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } 
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 d-none" id="related_request">
                <div class="d-flex mb-2">
                    <h3>Related Request: </h3>
                    <h3 class="ms-1" id="name_node"><a id="link_node" target="_blank" type="button" id="close_request" class=""></a></h3>
                </div>
                <table id="request-table" class="table table-striped border-top border-1" style="width:100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Start at</th>
                            <th>End at</th>
                        </tr>
                    </thead>
                    <tbody id="body_related_request">
                    </tbody>
                </table>
            </div>
        </div>
        <input class="form-control rounded-0 border border-1" type="hidden" name="_method" value="<?php echo $this->id ? 'PUT' : 'POST' ?>">
        <?php $this->ui->field('removes'); ?>
        <?php $this->ui->field('structure'); ?>
        <input type="hidden" name="save_close" id="save_close">
    </form>
</div>
<script>
    var ignore = <?php echo $this->data ? json_encode($this->data['ignore']) : "[]" ;?>;
    var removes= [];
    var active_item = 0;
    function removeItem(id)
    {
        removes.push(id);
        var index = ignore.indexOf(id);
        if (index > -1) {
            ignore.splice(index, 1);
        }
        var parent = $('#item_' + id).data('parent');
        var position = 0;
        $('#item_' + id).remove();

        $('.item-tree[data-parent="'+ parent +'"]').each(function(index){
            position++;
            $(this).attr('data-position', position);
            $(this).data('position', position);
        })
        $('.item-tree').each(function (index){
            if ($(this).data('parent') == id)
            {
                removeItem($(this).data('id'));
            }
        });
        loadPosition();
    }

    function loadPosition()
    {
        $('.item-tree').each(function(index){
            var id = $(this).data('id');
            if(id != 0)
            {
                moveChild(id);
            }
            var parent = $(this).data('parent');
            var position = 0;
            $('.item-tree[data-parent="'+ parent +'"]').each(function(index){
                position++;
                $(this).attr('data-position', position);
                $(this).data('position', position);
            })

            if ($('.item-tree[data-parent="'+ id +'"]').length)
            {
                $(this).addClass('childs');
            }
            else{
                $(this).removeClass('childs');
            }

            position = $(this).data('position');
            var lastPosition = $('.item-tree[data-parent="' + parent + '"]').last().data('position');
            
            if (position == 1)
            {
                $('#item_' + id + ' .up-note').addClass('d-none');
            }
            else{
                $('#item_' + id + ' .up-note').removeClass('d-none');
            }

            if (position == lastPosition)
            {
                $('#item_' + id + ' .down-note').addClass('d-none');
            }
            else{
                $('#item_' + id + ' .down-note').removeClass('d-none');
            }
        });
    }
    
    function moveChild(id)
    {
        var next = $('#item_' +id );
        $('.item-tree[data-parent="'+id+'"]').each(function(index){
            next.after($(this));
            next = $(this);
            moveChild($(this).data('id'));
        })
    }

    function toogleChildItem(id, toogle)
    {
        if (toogle)
        {
            $('#item_'+ id).removeClass('close');
            $('.item-tree[data-parent="'+ id +'"').removeClass('d-none');
        }
        else{
            $('#item_'+ id).addClass('close');
            $('.item-tree[data-parent="'+ id +'"').addClass('d-none');
        }

        $('.item-tree[data-parent="'+ id +'"').each(function(index){
            toogleChildItem($(this).data('id'), toogle);
        });
    }
    function eventActiveItem()
    {
        $('.item-tree').off('click').on('click', function(e){
            e.preventDefault();
            $('.item-tree').removeClass('active');
            $(this).addClass('active');
            active_item = $(this).data('id');

            var detail_link = '<?php echo $this->link_request .'/' ?>' + active_item;
            var node_detail = '<?php echo $this->link_note ?>/' + active_item;

            $('#link_node').text($(this).data('text'));
            $('#link_node').attr('href', node_detail);
            $('#body_related_request').html('');
            if (active_item == '0')
            {
                $('#link_node').text('');
                return true;
            }
            $.ajax({  
                type: 'GET',  
                url: detail_link, 
                data: {},
                success: function(response) {
                    if (response.status == 'success')
                    {
                        var data = response.data;
                        $('#related_request').addClass('d-none');

                        data.forEach(function(item) {
                            var link_detail = '<?php echo $this->link_detail_request . '/'; ?>' + item.id;
                            $('#body_related_request').append(`
                                <tr>
                                    <td><a target="_blank" href="${link_detail}" >${item.title}</a></td>
                                    <td>${item.start_at}</td>
                                    <td>${item.finished_at}</td>
                                </tr>
                            `);
                        })
                        $('#related_request').removeClass('d-none');
                    }
                    else{
                        alert(response.message)
                    }
                }
            });
        });

        $('.remove-note').off('click').on('click', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            if (active_item == id)
            {
                active_item == 0;
            }
            
            removeItem(id);
        });

        $('.up-note').off('click').on('click', function(){
            var id = $(this).data('id');
            var position = $('#item_'+ id).data('position');
            var parent = $('#item_'+ id).data('parent');

            if (position > 1)
            {
                var move_id = $('.item-tree[data-parent="'+ parent +'"][data-position="'+ (position - 1) +'"]').data('id');
                $('#item_' + move_id ).before($('#item_' + id));
                $('#item_' + move_id ).data('position', position);
                $('#item_' + move_id ).attr('data-position', position);
                $('#item_' + id ).attr('data-position', position - 1);
                $('#item_' + id).data('position', position - 1);
            }
            
            loadPosition();
        })
        $('.item-tree').off('dblclick').on('dblclick', function(){
            var id = $(this).data('id');
            toogleChildItem(id, $(this).hasClass('close'));
            
        })
        
        $('.down-note').off('click').on('click', function(){
            var id = $(this).data('id');
            var position = $('#item_'+ id).data('position');
            var parent = $('#item_'+ id).data('parent');
            var lastPosition = $('.item-tree[data-parent="' + parent + '"]').last().data('position');
            if (position != lastPosition)
            {
                var move_id = $('.item-tree[data-parent="'+ parent +'"][data-position="'+ (position +1) +'"]').data('id');
                $('#item_' + move_id ).after($('#item_' + id));
                $('#item_' + move_id ).data('position', position);
                $('#item_' + move_id ).attr('data-position', position);
                $('#item_' + id ).attr('data-position', position +1);
                $('#item_' + id).data('position', position + 1);
            }
            
            loadPosition();
        })
    }
    $(document).ready(function(e) {
        eventActiveItem();
        loadPosition();
        $(".btn_save_close").click(function(e) {
            e.preventDefault();
            $("#save_close").val(1);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val()) {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            var structure = [];
            $('.item-tree').each(function(index){
                var level = $(this).data('level');
                var position = $(this).data('position');
                var parent = $(this).data('parent');
                var id = $(this).data('id');
                structure.push({
                    id : id,
                    position : position,
                    parent : parent,
                    level : level,
                });
                $('#structure').val(JSON.stringify(structure));
                $('#removes').val(JSON.stringify(removes));
            });
            $('#form_submit').submit();
        });

        $(".btn_apply").click(function(e) {
            e.preventDefault();
            $("#save_close").val(0);
            $('input#input_title').val($('input#title').val());
            if (!$('input#title').val()) {
                alert("Please enter a valid Title");
                $('html, body').animate({
                    scrollTop: 0
                });
                $('input#title').focus();
                return false;
            }
            var structure = [];
            $('.item-tree').each(function(index){
                var level = $(this).data('level');
                var position = $(this).data('position');
                var parent = $(this).data('parent');
                var id = $(this).data('id');
                structure.push({
                    id : id,
                    position : position,
                    parent : parent,
                    level : level,
                });
                $('#structure').val(JSON.stringify(structure));
                $('#removes').val(JSON.stringify(removes));
            });
            $('#form_submit').submit();
        });

        $('.add-note-button').on('click', function(e) {
            e.preventDefault();
            var notes_selected = $('#notes').select2('data');
            if (notes_selected && Array.isArray(notes_selected)) {
                notes_selected.forEach(function(item, index) {
                    ignore.push(item.id);
                    var index = removes.indexOf(item.id);
                    if (index != -1)
                    {
                        removes.splice(index, 1);
                    }
                    createNote(item);
                });
            }

            $('#notes').val(null).trigger('change');
        })

        function createNote(item)
        {
            var level = $('#item_' + active_item).data('level');
            ignore.push(item.id);
            level++;
            var lastPosition = 0;
            var lastItem = active_item;
            $('.item-tree').each(function(index)
            {
                if ($(this).data('level') == level && $(this).data('parent') == active_item)
                {
                    lastPosition = $(this).data('position');
                    lastItem = $(this).data('id');
                }
            });
            lastPosition++;
            var tab = '&nbsp; &nbsp; &nbsp; &nbsp;';
            var levelTab = tab.repeat(level-1);
            $('#item_' + lastItem).after(`
                <tr data-text="${item.text}" id="item_${item.id}" data-level="${level}" data-position="${lastPosition}" data-parent="${active_item}" class="item-tree" data-id="${item.id}">
                    <td>${levelTab}<span class="title">|&mdash;${item.text}</span></td>
                    <td> 
                        <div class="d-flex justify-content-end">
                            <a class="up-note me-2 d-none" data-id="${item.id}"><i class="fa-solid fa-arrow-up"></i></a>
                            <a class="down-note  me-2 d-none" data-id="${item.id}"><i class="fa-solid fa-arrow-down"></i></a>
                            <a class="remove-note" data-id="${item.id}"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            `);
            eventActiveItem();
            loadPosition();
        }

        $("#notes").select2({
            matcher: matchCustom,
            placeholder: 'Select Notes',
            minimumInputLength: 1,
            multiple: true,
            closeOnSelect: false,
            ajax: {
                url: "<?php echo $this->link_search ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        ignore: ignore.toString(),
                    };
                },
                processResults: function(data, params) {
                    let items = [];
                    if (data.data.length > 0) {
                        data.data.forEach(function(item) {
                            items.push({
                                id: item.id,
                                text: item.title
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
        });

        function matchCustom(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            // Return `null` if the term should not be displayed
            return null;
        }
    });
</script>