<?php use SPT\Theme;

static $presenter;
if(!isset($presenter))
{
    $this->theme->add($this->url . 'assets/fabric/fabric.min.js', '', 'fabric.min.js', 'top');
    $this->theme->add($this->url . 'assets/fabric/custom.js', '', 'fabric-custom.js');
}
?>
<div class="row">
    <div class="col-12 overflow-auto" id="editor-canvas" >
        <canvas id="canvas"></canvas>
    </div>
    <div class="col-12">
        <div class="container-fluid text-center my-3">
            <span class="index-page-canvas">
            </span> / 
            <span class="total-page-canvas">
            </span>
        </div>
        <div class="container-fluid text-center my-3" id="fabric_slide_menu">
            <a class="btn btn-primary me-3 previous-button"><i class="fa-solid fa-chevron-left"></i>
            </a>
            <a class="btn btn-primary me-3 next-button"><i class="fa-solid fa-chevron-right"></i>
            </a>
            <a class="btn btn-primary me-3 add-button"><i class="fa-solid fa-plus"></i>
            </a>
            <a class="btn btn-danger me-3 remove-button"><i class="fa-solid fa-trash"></i>
            </a>
        </div>
        <div class="container-fluid text-center my-3" id="fabric_tool_menu">
            
            <a class="btn btn-primary me-3" onclick="addText()">Add Text
            </a>
            <a class="btn btn-primary me-3" onclick="addRect()">Add Rectangle
            </a>
            <a class="btn btn-primary me-3" onclick="addCircle()">Add Circle
            </a>
            <a class="btn btn-primary me-3" onclick="addArrow()">Add Arrow
            </a>
            <a class="btn btn-primary me-3" onclick="addImage()">Add Image
            </a>
            <a class="btn btn-danger me-3 selector-remove-button d-none" onclick="remove()">Remove
            </a>
        </div>
        <div class="container-fluid text-center my-3 d-none" id="editPosition">
            <a class="btn btn-primary" onclick="bringForward()">↑
            </a>
            <a class="btn btn-primary" onclick="bringToFront()">⇑
            </a>
            <a class="btn btn-primary" onclick="sendBackwards()">↓
            </a>
            <a class="btn btn-primary" onclick="sendToBack()">⇓
            </a>
            <div class="d-flex justify-content-center mt-3">
                <div class="change-color d-flex me-3">
                    <input class="me-3" type="color" name="fill_color">
                    <h3>Color: <span id="color-fill"></span></h3>.
                </div>
                <div class="change-border-color d-flex">
                    <input class="me-3" type="color" name="fill_border_color">
                    <h3>Border Color: <span id="border-color-fill"></span></h3>.
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addImageModal" aria-hidden="true" aria-labelledby="addImageModalLabel" tabcanvas_index="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addImageModalLabel">Add Image</h5>
            <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
        </div>
        <div class="modal-body">
            <input type="text" name="add_image" placeholder="http://imageurl.com" class="form-control">
        </div>
        <div class="modal-footer">
            <a class="btn btn-primary import-image">Add</a>
        </div>
        </div>
    </div>
</div>
<input name="<?php echo $this->field->name ?>" type="hidden" id="<?php echo $this->field->id ?>" value='<?php echo $this->field->value ?>' />
<script>
    var data_canvas = $('#description_presenter').val();
    data_canvas = data_canvas ? JSON.parse(data_canvas) : [];
    var total_page_canvas = 1;
    var canvas_index = 0;
    $(document).ready(function(){

        if (data_canvas && data_canvas.length)
        {
            total_page_canvas = data_canvas.length;
            Import(data_canvas[canvas_index]);
        }
        else
        {
            data_canvas = [];
        }

        loadPagination(total_page_canvas, canvas_index);
        $('.remove-button').on('click', function(){
            var result = confirm("You are going to delete 1 item. Are you sure ?");
            if (result) {
                var tmp = data_canvas.splice(canvas_index, 1);
                if (total_page_canvas > 1)
                {
                    total_page_canvas--;
                }
                canvas_index = total_page_canvas < canvas_index + 1 ? total_page_canvas - 1: canvas_index;
                canvas.clear();
                initCanvas($('#editor-canvas'));
                reRender();
                Import(data_canvas[canvas_index])
                loadPagination(total_page_canvas, canvas_index);
            }
            else
            {
                return false;
            }
        })

        $('.add-button').on('click', function(){
            data_canvas[canvas_index] = canvas.toJSON();
            total_page_canvas++;
            canvas_index++;
            canvas.clear();
            initCanvas($('#editor-canvas'));
            reRender();
            loadPagination(total_page_canvas, canvas_index);
        });

        $('.previous-button').on("click", function()
        {
            data_canvas[canvas_index] = canvas.toJSON();
            canvas_index--;
            loadPagination(total_page_canvas, canvas_index);
            var content = data_canvas[canvas_index];
            Import(content);
        });

        $('.next-button').on("click", function()
        {
            data_canvas[canvas_index] = canvas.toJSON();
            canvas_index++;
            loadPagination(total_page_canvas, canvas_index);
            var content = data_canvas[canvas_index];
            Import(content);
        });
    });
</script>