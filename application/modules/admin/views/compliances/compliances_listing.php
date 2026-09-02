<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);die;
//pr(ID_decode($_GET['id']));
?>				
<?php echo get_flashdata(); ?>

<style>
.table-scrollable .dataTable td>.btn-group, .table-scrollable .dataTable th>.btn-group {
    position: relative;
    margin-top: -2px;
}
.form-group-custom .col-md-6 label {
    float: left;
    width: 36%;
}
.form-group-custom {
    position: absolute;
    right: 0px;
    z-index: 9;
    background: #fff;
    width: 58%;
    float: right;
    top: -29px;
}
.select-style {
    width: 60%;
    float: right;
    margin-top: -9px;
}
.custom-height-top{
    position: relative;
}
input#fn_from_date, input#fn_to_date {
    padding: 5px 10px;
    border-radius: 3px;
    border: 1px solid #ddd;
}
.input-small {
    width: 164px!important;
}
.stuts_jbdt{position: absolute; left: 10px;}
.portlet.light .portlet-body {
    padding-top: 15px !important;
}
.table-scrollable {
    overflow-x: auto;
}
.supplier{
    padding: 6px 17px;
    border-radius: 3px;
    border: 1px solid #ddd;
    margin-top: 5px;
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> <?php echo $service_name->service_name;?>  Compliances Listing </span>
                </div>
                
                    <div class="actions">
                        <div class="btn-group">
                            <a href="<?= base_url();?>admin/compliances/add_compliances?id=<?php echo ID_encode($service_name->id);?>" class="btn sbold green">Add Compliances</a>
						</div>
                    </div>
				</div>
                <div class="portlet-body">
                
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Customer Name</th>
                            <th>QSA</th>
                            <th>Consultant</th>
                            <th>QA</th>
                            <th>Process</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </th>
                            
                        </tr>
                    </thead>
                    <tbody>
					
					
                        <?php	if(isset($project_list) && !empty($project_list)){ ?>
                        <?php $i=1; foreach($project_list as $project){ 
					//	pr($project);
						?>
					
                       		<tr class="odd gradeX">
                                    <td class="center"><?php echo $i;?></td>
                                    <td class="center"><?php echo $project->CustomerName;?></td>
                                    <td class="center"><?php echo $project->QSAName;?></td>
                                    <td class="center"><?php echo $project->ConsultantName;?></td>
                                    <td class="center"><?php echo $project->QaName;?></td>
                                    <td class="center"><?php echo $project->ProcessName;?></td>
                                    <td class="center"><?php echo date("d-m-Y", strtotime($project->start_date));?></td>
                                     <td class="center"><?php if(isset($project->end_date) && !empty($project->end_date)){
										  echo date("d-m-Y", strtotime($project->end_date));
									 }?></td>
                                     <td class="center"><?php $data= Admin_status_change($project->service_id,$project->process_id,$project->customer_id);
									 if($data =="Approved"){
										 echo "Completed";
									 }else{
										  echo "In Progress";
									 }
											//if($data =='')
												?></td>
                                    <td class="center" style="width: 18%;">
										<a href="<?= base_url();?>admin/compliances/view_compliances?id=<?php echo ID_encode($project->id);?>&&service_id=<?php echo ID_encode($service_name->id);?>&&cus_id=<?php echo ID_encode($project->customer_id);?>&&process_id=<?php echo ID_encode($project->process_id);?>&&qsa_id=<?php echo ID_encode($project->qsa_id);?>&&qa_id=<?php echo ID_encode($project->qa_id);?>&&consultant_id=<?php echo ID_encode($project->consultant_id);?>" class="label label-sm btn-default label-info margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="View" data-original-title="Status">
										<i class="icon-eye"></i>
										</a>
                                        <a href="<?= base_url();?>admin/compliances/edit_compliances?id=<?php echo ID_encode($project->id);?>&&service_id=<?php echo ID_encode($service_name->id);?>" class="label label-sm btn-default label-success margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Edit" data-original-title="Status">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <!--<a href="javascript:void(0);" onclick="deleteCompliance(<?php echo ID_encode($project->id).",".ID_encode($project->service_id).",".ID_encode($project->customer_id).",".ID_encode($project->process_id).",".ID_encode($project->qsa_id).",".ID_encode($project->consultant_id).",".ID_encode($project->qa_id); ?>);" class="label label-sm btn-default label-danger margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Delete" data-original-title="Status">
                                           <i class="icon-trash"></i>
                                        </a>-->
                                    </td>
								</tr>
                        <?php $i++; } ?>
                        <?php } ?>

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
<!-- Button trigger modal -->

<script>
    
    function deleteCompliance(prject_id,service_id,customer_id,process_id,qsa_id,consultant_id,qa_id) {
        var r = confirm("Do you want to delete this?")
        if (r == true)
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/compliances/delete",
				data:{prject_id:prject_id,service_id:service_id,process_id:process_id,customer_id:customer_id,consultant_id:consultant_id,qsa_id:qsa_id,qa_id:qa_id},
				success:function(deleted){
					
					alert(deleted);
		//			location.reload();
				}
	
				
				
			});
		
		
    }
    
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
                                    "pageLength": 100,
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
                                "ordering": false,
                                        columnDefs: [{  orderable: false, targets:  "no-sort"}]
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