
				 <?php echo get_flashdata(); ?>
				<div class="row">                   
                   
                    <div id="msgShow"></div>
                    <div class="col-md-12 col-sm-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet light bordered">
						<div class="portlet-title">
							<div class="caption">
								 CMS Listing
							</div>
<!--							<div class="actions">
								<?php //if(has_permission($this->controller_name, 'export')){ ?>
									<a href="javascript:;" class="btn btn-default btn-sm cms_export">
									<i class="fa fa-file-excel-o "></i> Export</a>
								<?php// } ?>
							</div>-->
						</div>
						<div class="portlet-body">
						 <form method="get"  id="filter_id">
						<div class=" ">
								<div class="row custom-height-top">
								<div class="form-group-custom">
										   <div class="col-md-4">
											<label>Status:</label>
											</div>
											<div class="col-md-8">
												<div class="select-style">
													<select name="status" class="form-control custom_filter " id="id-status" >														
														<option value="active" <?php echo @$_GET['status'] == 'active' ? "selected" : ""; ?>>Active</option>
														<option value="inactive" <?php echo @$_GET['status'] == 'inactive' ? "selected" : ""; ?>>Inactive</option>
														<option value="delete" <?php echo @$_GET['status'] == 'delete' ? "selected" : ""; ?>>Deleted</option>														
													</select>
												</div>
										</div>                                      
									</div>
									</div>
							</div>
                            </form>
						
						
							<table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
							<thead>
							<tr>
								<th class="table-checkbox hide">
									<input type="checkbox" class="group-checkable" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/>
								</th>
                                                                <th>
									 S.No.
								</th>
<!--								<th>
									 Page Name
								</th>-->
								<th>
									 Page Title
								</th>
								<th>
									 Action
								</th>
							</tr>
							</thead>
							<tbody>
							<?php if(is_array($listing) && !empty($listing)) { 
                                                            
								$i=1; foreach($listing as $key => $value){
									
										
							?>
							<tr class="odd gradeX">
								<td class="hide"><?php if (in_array($value->id, $selected)) { ?>
									<input type="checkbox" name="selected[]" class="checkboxes" value="<?php echo $value->id; ?>" checked="checked"/>
								<?php } else { ?>
									<input type="checkbox" name="selected[]" class="checkboxes" value="<?php echo $value->id; ?>"/>
								<?php } ?>
								</td>
                                                                <td>
                                                                    <?php echo $i; ?>
                                                                </td>
<!--								<td>
									<?php //echo $value->name; ?>
								</td>-->
								<td>
									<?php echo ucwords($value->title); ?>
								</td>
                                                                
                                                                
                                                                <td align="left" valign="top" style=" display: flex;">
                                                   
                                                                <a href="<?php echo base_url();?>admin/cms/view?id=<?=ID_encode($value->id)?>" class="label label-sm label-info margin-right-10 tooltips" data-container="body" title="View" data-placement="top" data-original-title="View">
                                                                    <i class="icon-eye"></i></a>
                                                            
                                                                <a href="<?php echo base_url();?>admin/cms/edit?id=<?=ID_encode($value->id)?>" class="label label-sm label-info margin-right-10 tooltips" data-container="body" title="Edit" data-placement="top" data-original-title="Edit">
                                                                    <i class="fa fa-edit"></i></a>
                                                            <?php if (@$_GET['status'] != 'delete') { ?>
                                                                    <a href="<?php echo base_url();?>admin/cms/delete?id=<?=ID_encode($value->id)?>" class="label label-sm label-info margin-right-10 tooltips" data-container="body" title="Delete" data-placement="top" data-original-title="Delete">
                                                                    <i class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                                     <?php
                                                    if (@$_GET['status'] == 'delete') {
                                                        $restoreArr = array(
                                                            'table' => 'kc_cms',
                                                            'col1' => 'status',
                                                            'col2' => 'id',
                                                            'value' => 'Active',
                                                            'id' => $value->id,
                                                        );
                                                        $resA = htmlspecialchars(json_encode($restoreArr));
                                                        ?>
                                                        <a href="javascript:void(0);" onclick="restoreData('<?php echo $resA; ?>')" class="label label-sm btn-default label-info margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Restore" data-original-title="Status">
                                                            <i class="fa fa-recycle"></i></a>

                                                    <?php } ?>
                                                        
                                                </td>

                                                                
                                                                
                                                                
                                                                
								
								
							</tr>
							<?php $i++; }} ?>
							</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>                
                </div>

                <!-- END PAGE CONTENT-->
            </div>
        </div>	
<script>
 
    //    Delete Country
    $(document).on('click','.unit_delete', function () {
	var cms_ids         =   [];    

	$.each($("input[name='selected[]']:checked"), function(){            
	cms_ids.push($(this).val());            
	});  

	if(parseInt(cms_ids.length) == 0)
	{
	jAlert("Please select at least one record(s)!", "Warning");
	return false;;
	}      

	
	jConfirm('Are you sure want to delete the selected record(s)!', 'Confirmation Dialog', function(r) {
		if(r){     
			             
				$.ajax({ 
				type: 'POST',
				url: '<?php echo base_url(); ?>admin/unit/delete',
				data:"unit_ids="+unit_ids, 
				success: function(dat) { 
				var url = '<?php echo base_url(); ?>admin/unit';
				dat =   JSON.parse(dat);	
				if(dat['status']=="success")
				{                         
				//$('#msgShow').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i>'+dat['msg']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');   

				setTimeout(function(){         
				location = url; 
				}, 1000);
				}else if(dat['status']=="error"){                       
				//$('#msgShow').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;'+dat['msg']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');     
				//$('#msgShowResult').html(dat['result']);                     

				setTimeout(function(){         
				location = url; 
				}, 1000);

				}
				}
				}); 


				
		}
	});
	
	});

	
/* export country data */

    $(document).on('click','.cms_export', function () {
	var ids         =   [];    

	$.each($("input[name='selected[]']:checked"), function(){            
	ids.push($(this).val());            
	});  

	if(parseInt(ids.length) == 0)
	{
	jAlert("<?=lang('select_atlist_one_recourd'); ?>", "<?=lang('worning')?>");
	return false;;
	}      

	
	jConfirm('<?=lang('excel_export'); ?>', '<?=lang('conform'); ?>', function(r) {
		if(r){             
			var url = '<?php echo base_url(); ?>admin/cms/export';	
			if (ids !='') {
				url += '?ids=' + encodeURIComponent(ids);
			}
			location = url;	
			setTimeout(function(){         
			window.location.href= "<?php echo base_url(); ?>admin/cms" ;
			}, 2000);	
			} else {
			return false;
			}
	});

	
	});


</script>
<script>
 $(".custom_filter").on("change",function(){ 
        var status = $(this).val();		
        if(status == 'none')
        {
           
          window.location.href= "<?php echo base_url(); ?>admin/cms" ;
        }
        else
        {
           $("#filter_id").submit(); 
        } 
    });
	
    function restoreData(arr){ 
        $.ajax({
            url:'<?php echo base_url();?>admin/cms/restoreData',
            type:'POST',
            data:{arr:arr},
            dataType:'JSON',
            success:function(res){
                console.log(res);
                if(res.status == 'success'){
                    window.location.reload();
                }else{
                    alert(res.message);
                }
            }              
        });
    }	
	
</script>	
<script>
    var TableManaged = function () {
        var initTable2 = function () {
            var table = $('#datatable');
            table.dataTable({
                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                                        "infoEmpty": "No entries found",
                                        "infoFiltered": "(filtered1 from _MAX_ total entries)",
                                        "lengthMenu": "Show _MENU_ entries",
                                        "search": "Search",
                                        "zeroRecords": "No Matching Records Found",
                                        "lengthMenu": " _MENU_ Record",
                                                "paging": {
                                                    "previous": "Prev",
                                                    "next": "Next"
                                                }
                                    },
                                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
                                    // So when dropdowns used the scrollable div should be removed. 
                                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

                                    "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.

                                    "lengthMenu": [
                                        [20, 50, 100],
                                        [20, 50, 100] // change per page values here
                                    ],
                                    // set the initial value
                                    "pageLength": 50,
                                    "columnDefs": [{// set default column settings
                                            'orderable': false,
                                            'targets': [0, 2, 3]
                                        }, {
                                            "searchable": false,
                                            "targets": [0]
                                        }],
                                    // "order": [
                                    //    [1, "asc"]
                                    //] // set first column as a default sort by asc
                                });

                                var tableWrapper = jQuery('#datatable_wrapper');

                                table.find('.group-checkable').change(function () {
                                    var set = jQuery(this).attr("data-set");
                                    var checked = jQuery(this).is(":checked");
                                    jQuery(set).each(function () {
                                        if (checked) {
                                            $(this).attr("checked", true);
                                        } else {
                                            $(this).attr("checked", false);
                                        }
                                    });
                                    jQuery.uniform.update(set);
                                });

                                tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
                            }



                            return {
                                //main function to initiate the module
                                init: function () {
                                    if (!jQuery().dataTable) {
                                        return;
                                    }
                                    initTable2();
                                    $(".form-group-custom").removeClass('hide');
                                }

                            };

                        }();
                        jQuery(document).ready(function () {
                            setTimeout(function () {
                                TableManaged.init();
                                $(".table-checkbox").removeClass('sorting_asc');
                            }, 500);

                        });
</script>