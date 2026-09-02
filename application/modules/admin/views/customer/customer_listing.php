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
                    <span class="caption-subject bold uppercase"> Customers Listing </span>
                </div>
                
                    <div class="actions">
                        <div class="btn-group">
                            <a href="<?= base_url();?>admin/customer/add_customer" class="btn sbold green">Add Customer</a>

                        </div>
                    </div>
            </div>
            
          
               
         
                <div class="portlet-body">
                
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th> S.No. </th>
                            <th> Customer Name </th>
                            <th> Unique Id  </th>
                            <th> Process  </th>
                            <th> Company Name  </th>
                            <th> Status  </th>
                            <th> Actions</th>
                            <th> Password </th>
                            <th> Login Certificate  </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($customer_listing) && !empty($customer_listing)){ ?>
                        <?php $i =1; foreach($customer_listing as $customer){ 
						//pr($this->session->userdata());
						?>
                       
                        <tr class="odd gradeX">
                                    
                                    <td class="center"><?php echo $i;?></td>
                                    <td class="center"><?php echo $customer->full_name;?></td>
                                    <td class="center"><?php echo $customer->unique_id;?></td>
                                   <!--  code for get customer process -->
                                   <?php $getProcess  = _get_customer_process($customer->id);
								   ?>
                                   <!--  end code for get customer process -->
                                    <td class="center">
                                        <?php $count =  count($getProcess);
                                        $i =1;  ?>
                                    <?php foreach($getProcess as $processname){ 	?>
                                        <a href="<?= base_url();?>admin/customer/view_process?id=<?php echo ID_encode($processname->id);?>"><?php echo $processname->process_name;?></a><?php if($count > $i){ echo ',';};?>
                                    <?php $i++; } ?>
                                        <br/>
                                        <a href='javascript:;'  onclick="$('#formid').val('<?php echo $customer->id; ?>', $('#jobnumber').val('<?php echo $customer->id; ?>'),addProcess())" class="label label-sm btn-warning margin-right-10 tooltips" data-lang="16" data-placement="top" title="Add Process" data-original-title="Status" >Add Process</a>
                                        </td>
                                    <td class="center"><?php echo $customer->company_name;?></td>
                                    <td class="center"><?php echo ucwords($customer->status);?></td>
                                    <td class="center" style="width: 18%;">
                                        <a href="<?= base_url();?>admin/customer/view_customer?id=<?php echo ID_encode($customer->id);?>" class="label label-sm btn-default label-info margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="View" data-original-title="Status">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <a href="<?= base_url();?>admin/customer/edit_customer?id=<?php echo ID_encode($customer->id);?>" class="label label-sm btn-default label-success margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Edit" data-original-title="Status">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="deleteCustomer(<?php echo ID_encode($customer->id) ?>);" class="label label-sm btn-default label-danger margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Delete" data-original-title="Status">
                                           <i class="icon-trash"></i>
                                        </a>
                                        <a href="<?php echo base_url();?>admin/customer/sub_customer_listing?id=<?php echo ID_encode($customer->id);?>" class="label label-sm btn-default label-success margin-right-10 tooltips" data-lang="16"  data-container="body" data-placement="top" title="Add Sub Customer" data-original-title="Status"><i class="fa fa-user"></i></a>
                                    </td>
                                    <td class="center" id="pwdstr<?php echo $customer->id; ?>"><a href="javascript:;" onclick="viewpassword(<?php echo $customer->id; ?>)" id="vpwd<?php echo $customer->id; ?>">View</a><br/>
                                        <p id="pwd<?php echo $customer->id; ?>" class="hide pwd12"><input type="password" id="pwdval<?php echo $customer->id; ?>" placeholder="Enter admin password"><a href="javascript:;" onclick="checkpassword(<?php echo $customer->id; ?>)">Go</a><span class="close_btn"><a href='javascript:;' onclick='closeviewpwd(<?php echo $customer->id; ?>)'>x</a></span></p></td>
                                    <td class="center">
                                       <a href="<?= base_url();?>admin/customer/genrate_certificate?id=<?= ID_encode($customer->id) ?>">Customer</a><br>
                                        <a href="<?= base_url();?>admin/customer/genrate_certificate_for_qsa?id=<?= ID_encode($customer->id) ?>">QSA</a><br>
                                        <a href="<?= base_url();?>admin/customer/genrate_certificate_for_qa?id=<?= ID_encode($customer->id) ?>">QA</a><br>
                                        <a href="<?= base_url();?>admin/customer/genrate_certificate_for_consultants?id=<?= ID_encode($customer->id) ?>">Consultant</a><br>
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
<?php $this->load->view('elements/add_process') ?>
<script>
    function addProcess(){
       $('#myModal').modal('show');
    }
    
    function deleteCustomer(id) {
        var r = confirm("Do you want to delete this?")
        if (r == true)
            window.location = url + "admin/customer/delete_customer?id=" + id;
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
</script>