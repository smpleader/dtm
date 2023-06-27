<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');
?>
<div class="modal fade" id="formEdit" aria-labelledby="formEditLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form action="" method="post" id="form_report">
                <div class="row g-3 align-items-center">
                    <div class="row px-0">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Title</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('title'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Assignment</label>
                        </div>
                        <div class="col-12">
                            <select class="" multiple id="assignment" name="assignment[]">
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center m-0">
                        <div class="modal-footer">
                            <?php $this->ui->field('token'); ?>
                            <input class="form-control rounded-0 border border-1" id="_method" type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-6 text-end pe-0">
                                    <button type="button" class="btn btn-outline-secondary fs-4" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col-6 text-end pe-0 ">
                                    <button type="submit" class="btn btn-outline-success fs-4">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var ignores = [];
    $(document).ready(function(){
        $('.show_data').on('click', function(){
            var id = $(this).data('id') ?? 0;
            var title = $(this).data('title') ?? '';
            var assignment = $(this).data('assignment') ?? '';
            
            $('#form_report').attr('action', '<?php echo $this->link_form ?>/' + id);
            $('#form_report #title').val(title);
            
            //clear all
            $('#assignment').val(null).trigger('change');
            if (assignment)
            {
                assignment.forEach(element => {
                    var newOption = new Option(element.name, element.id, true, true);
                    $('#assignment').append(newOption).trigger('change');
                });
            }
        });
    })
</script>
<?php
$js = <<<Javascript
$(document).ready(function() {
    $("#assignment").select2({
        matcher: matchCustom,
        ajax: {
            url: "{$this->link_search}",
            dataType: 'json',
            delay: 100,
            data: function(params) {
                return {
                    search: params.term,
                    ignores: ignores
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
        dropdownParent: $("#formEdit"),
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

$this->theme->addInline('js', $js);