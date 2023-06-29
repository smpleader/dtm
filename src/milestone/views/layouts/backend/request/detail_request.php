
<?php 
$this->theme->add($this->url . 'assets/css/select2.min.css', '', 'select2-css');
$this->theme->add($this->url . 'assets/js/select2.full.min.js', '', 'bootstrap-select2');

echo $this->render('layouts.backend.document.form', []);
echo $this->render('layouts.backend.version_latest.list', []);
?>
<div class="toast message-toast" id="message_ajax">
    <div id="message_form" class="d-flex message-body ">
        <div class="toast-body">
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
<script>
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

<div class="modal fade" id="formModalToggle" aria-labelledby="formModalToggleLabel" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered " style="max-width: 600px;">
        <div class="modal-content container px-5 pt-5">
            <form action="<?php echo $this->link_form_request;?>" method="post" id="form_request">
                <div class="row">
                    <div class="mb-3 col-12 mx-auto pt-3">
                        <input name="title" type="text" id="title" required="" placeholder="Request" value="<?php echo htmlspecialchars($this->request['title']);?>" class="form-control h-50-px fw-bold rounded-0 fs-3">
                    </div>
                </div>
                <input type="hidden" name="tags" id="tags">
                <div class="row px-0 mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Tags</label>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <select class="js-example-tags" multiple id="select_tags">
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Start At</label>
                    </div>
                    <div class="col-12">
                        <input name="start_at" type="date" id="start_at" placeholder="Start At" value="<?php echo $this->request['start_at'] && $this->request['start_at'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($this->request['start_at'])) : '';?>" class="form-control rounded-0 border border-1 py-1 fs-4-5"/>                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">End At</label>
                    </div>
                    <div class="col-12">
                        <input name="finished_at" type="date" id="finished_at" placeholder="End At" value="<?php echo $this->request['finished_at'] && $this->request['finished_at'] != '0000-00-00 00:00:00' ? date('Y-m-d', strtotime($this->request['finished_at'])) : '';?>" class="form-control rounded-0 border border-1 py-1 fs-4-5"/>                        
                    </div>
                </div>
                <input type="hidden" name="detail_request" value="1">
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Description</label>
                    </div>
                    <div class="col-12">
                        <textarea name="description" type="text" id="description" placeholder="Enter description" class="form-control rounded-0 border border-1 py-1 fs-4-5"><?php echo htmlspecialchars($this->request['description']);?></textarea>                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <label class="form-label fw-bold mb-2">Assigments</label>
                    </div>
                    <div class="col-12">
                        <select name="assignment[]" multiple id="assignment">
                        </select>
                    </div>
                </div>
                <div class="row g-3 align-items-center m-0">
                    <div class="modal-footer">
                        <input name="token" type="hidden" id="token" value="91e0f6584395a6a937615717605e92c7">                            <input class="form-control rounded-0 border border-1" id="request" type="hidden" name="_method" value="PUT">
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
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="noteNewModal" aria-labelledby="noteNewModalTitle" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="noteNewModalTitle">New Note</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex justify-content-around">
				<?php foreach($this->note_types as $type) : ?>
					<h4>
						<a  target="_blank" class="mx-3" href="<?php echo $type['link']?>"><?php echo $type['title']?></a>
					</h4>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
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
</script>