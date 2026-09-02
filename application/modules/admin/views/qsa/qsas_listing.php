<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);die;
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
.pwd12 > input{
    padding-right: 20px;
    width: auto;
}
.pwd12{
    position:relative;
}
.pwd12 > a{
    position: absolute;
    right: 1px;
    top: 1px;
    color: #fff;
    background-color: #0c5d80;
    padding: 2px 2px;
}
span.close_btn{
    position: absolute;
    top: -24px;
    right: 5px;
    font-size: 10px;
    border: 1px solid #e01010;
    width: 18px;
    height: 18px;
    padding: 0px;
    font-weight: bold;
    color: #ed6b75;
    /* padding-bottom: 20px; */
    text-align: center;
    border-radius: 50%;
    /* padding-bottom: 2px; */
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> QSA Listing </span>
                </div>
                
                    <div class="actions">
                        <div class="btn-group">
                            <a href="<?= base_url();?>admin/qsa/add_qsa" class="btn sbold green">Add QSA</a>
                            </div>
                    </div>
            </div>
            
          
               
         
                <div class="portlet-body">
                
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th> S.No. </th>
                            <th> QSA Name </th>
                            <th> Email Id  </th>
                            <th> Total Customer  </th>
                            <th> Phone Number  </th>
                            <th> Status  </th>
                            <th style="width: 83px;"> Actions </th>
                            <th> Password </th>
<!--                            <th> Login Certificate  </th>-->
                        </tr>
                    </thead>
                    <tbody>
                       <?php if(isset($qsa_listing) && !empty($qsa_listing)){ ?>
                        <?php $i = 1; foreach($qsa_listing as $qsa_data){ ?>
                        <tr class="odd gradeX">
                                    
                                    <td class="center"><?php echo $i;?></td>
                                    <td class="center"><?php echo ucwords($qsa_data->full_name);?></td>
                                    <td class="center"><?php echo $qsa_data->email;?></td>
                                    <?php $totalcustomer = _get_total_customer('qsa_id',$qsa_data->id); ?>
                                    <td class="center"><?php echo $totalcustomer;?></td>
                                    <td class="center"><?php echo $qsa_data->phone_number;?></td>
                                    <td class="center"><?php echo ucwords($qsa_data->status);?></td>
                                    <td class="center" style="width: 13%;">
                                        <a href="<?= base_url();?>admin/qsa/view_qsa?id=<?php echo ID_encode($qsa_data->id) ?>" class="label label-sm btn-default label-info margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="View" data-original-title="Status">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <a href="<?= base_url();?>admin/qsa/edit_qsa?id=<?php echo ID_encode($qsa_data->id) ?>" class="label label-sm btn-default label-success margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Edit" data-original-title="Status">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="deleteUser(<?php echo ID_encode($qsa_data->id) ?>);" class="label label-sm btn-default label-danger margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Delete" data-original-title="Status">
                                           <i class="icon-trash"></i>
                                        </a>
                                    </td>
                                     <td class="center" id="pwdstr<?php echo $qsa_data->id; ?>"><a href="javascript:;" onclick="viewpassword(<?php echo $qsa_data->id; ?>)" id="vpwd<?php echo $qsa_data->id; ?>">View</a><br/>
                                        <p id="pwd<?php echo $qsa_data->id; ?>" class="hide pwd12"><input type="password" id="pwdval<?php echo $qsa_data->id; ?>" placeholder="Enter admin password"><a href="javascript:;" onclick="checkpassword(<?php echo $qsa_data->id; ?>)">Go</a><span class="close_btn"><a href='javascript:;' onclick='closeviewpwd(<?php echo $qsa_data->id; ?>)'>x</a></span></p></td>
                                    
                                   
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
    
    function deleteUser(id) {
        var r = confirm("Do you want to delete this?")
        if (r == true)
            window.location = url + "admin/qsa/delete?id=" + id;
        else
            return false;
    }
	
	 function closeviewpwd(id){
        $("#pwd"+id).addClass("hide");
        $("#vpwd"+id).removeClass("hide");
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
                        
//=====code for view password===============//                        
function viewpassword(id){
   $("#pwd"+id).removeClass("hide");
    $("#vpwd"+id).addClass("hide");
}
function checkpassword(id){
    var pwd = $("#pwdval"+id).val();
    if(pwd == ''){
        alert('Please enter your password');
        return false;
    }
    $.ajax({
        url:'<?= base_url(); ?>admin/customer/view_password',
        data:{
         pwd:pwd,   
        id:id,
        },
         type:"POST",
         success:function(result){
             
             if(result == 'false'){
            alert("Enter valid password");  
            $("#vpwd"+id).removeClass("hide");
              $("#pwd"+id).addClass("hide"); 
             }else{
                
                  $("#pwd"+id).addClass("hide");
                 $("#vpwd"+id).addClass("hide");
                 $("#pwdstr"+id).html(result);
        }
    }
    });
}
//=====end code for view password===============//
</script>