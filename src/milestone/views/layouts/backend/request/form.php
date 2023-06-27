<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');

echo $this->render('notification', []); ?>
<div class="modal fade" id="exampleModalToggle" aria-labelledby="exampleModalToggleLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form action="" method="post" id="form_request">
                <div class="row g-3 align-items-center">
                    <div class="row px-0">
                    <div class="row px-0">
                        <div class="mb-3 col-12 mx-auto pt-3">
                            <?php $this->ui->field('title'); ?>
                        </div>
                    </div>
                    <?php $this->ui->field('tags'); ?>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Tags</label>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <select class="js-example-tags" multiple id="select_tags">
                            </select>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Start</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('start_at'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Finished</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('finished_at'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Description</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('description'); ?>
                        </div>
                    </div>
                    <div class="row px-0 mb-3">
                        <div class="col-12 d-flex align-items-center">
                            <label class="form-label fw-bold mb-2">Assigments</label>
                        </div>
                        <div class="col-12">
                            <?php $this->ui->field('assignment'); ?>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center m-0">
                        <div class="modal-footer">
                            <?php $this->ui->field('token'); ?>
                            <input class="form-control rounded-0 border border-1" id="request" type="hidden" name="_method" value="POST">
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
    var new_tags = [];
    $(document).ready(function(){
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
</script>
<?php
$js = <<<Javascript
$(document).ready(function() {
    $("#assignment").select2({
        matcher: matchCustom,
        ajax: {
            url: "{$this->link_user_search}",
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