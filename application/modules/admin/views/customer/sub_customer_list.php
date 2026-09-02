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
.g_cust{
    font-size: 10px;
    background-color: #659be0;
    padding: 5px 5px;
    color: #fff;
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> Sub Customers Listing </span>
                </div>
                
                    <div class="actions">
                        <div class="btn-group">
                            <a href="<?php echo base_url();?>admin/customer/add_sub_customer?id=<?php echo ID_encode($customer_id);?>" class="btn sbold green">Add Sub Customer</a>

                        </div>
                    </div>
            </div>
            
          
               
         
                <div class="portlet-body">
                
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th> S.No. </th>
                            <th> Customer Name </th>
                            <th> Email </th>
                            <th> Assigned Process </th>
                            <th> Action </th>
                            <th> Login Certificate </th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($sub_customer_list) && !empty($sub_customer_list)){ ?>
                        <?php $i=1; foreach($sub_customer_list as $subcustomer){ ?>
                        <tr class="odd gradeX">
                                    
                                    <td class="center"><?php echo $i;?></td>
                                    <td class="center"><?php echo $subcustomer->full_name;?></td>
                                    <td class="center"><?php echo $subcustomer->email;?></td>
                                    <td class="center">
                                        <?php if(isset($subcustomer->sub_customer_process) && !empty($subcustomer->sub_customer_process)){ ?>
                                        <?php $processId = explode(',',$subcustomer->sub_customer_process);?>
                                        
                                            <?php foreach($processId as $process_id){ ?>
                                        <?php  $process_name = _get_process_name($process_id); ?>
                                        
                                        <a href="<?php echo base_url();?>admin/customer/view_process?id=<?php echo ID_encode($process_name->id);?>"><?php echo $process_name->process_name;?></a>,</br>
                                        
                                        <?php }?>
                                        <?php }?>
                                    </td>
                                    <td class="center">
                                    <a href="<?php echo base_url();?>admin/customer/edit_sub_customer?id=<?php echo ID_encode($subcustomer->id);?>&&customer_id=<?php echo ID_encode($customer_id);?>" title="Edit"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td class="center"><a href="<?= base_url();?>admin/customer/genrate_certificate?id=<?= ID_encode($subcustomer->id) ?>">Generate</a></td>
                                    <td class="center" id="pwdstr<?php echo $subcustomer->id; ?>"><a href="javascript:;" onclick="viewpassword(<?php echo $subcustomer->id; ?>)" id="vpwd<?php echo $subcustomer->id; ?>">View</a><br/>
                                        <p id="pwd<?php echo $subcustomer->id; ?>" class="hide pwd12"><input type="password" id="pwdval<?php echo $subcustomer->id; ?>" placeholder="Enter admin password"><a href="javascript:;" onclick="checkpassword(<?php echo $subcustomer->id; ?>)">Go</a></p></td>
                                   <!--  code for get customer process -->
                                   
                                   <!--  end code for get customer process -->
                                    
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