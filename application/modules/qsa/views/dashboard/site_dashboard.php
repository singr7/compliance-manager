<?php
$userInfo = $this->session->userdata('userinfo');
///pr($userInfo);die;
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
                    <span class="caption-subject bold uppercase">Company Name: <?php echo $userInfo->company_name;?></span>
                </div>
                <div class="actions">
                        <div class="btn-group">
                         
                        </div>
                    </div>
            </div>
            <div class="portlet-title">
                <div class="btn-group">
				<span class="caption-subject"> Customer Name:&nbsp;<?php echo ucfirst($customer_info->full_name);?> </span>
                    
                </div>
                <div class="actions">
                        <div class="btn-group">
                          <span class="caption-subject">Customer Unique Id: <?php echo $customer_info->unique_id;?></span>
                        </div>
                    </div>
            </div>
          
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject  bold uppercase">Process</span>
                </div>
                <div class="actions">
                        <div class="btn-group">
                          
                        </div>
                    </div>
            </div>
          
              
            
            
            <?php if(isset($customer_process) && !empty($customer_process)){ ?>
            <?php foreach($customer_process as $customer){ ?>
            
            <?php $process = FALSE; ?>
            <?php if(($process == false) && !empty($customer->customer_process)){ ?>
            <?php $process = TRUE;?>
            <?php } ?>
            
             <?php //if(isset($process) && !empty($process)){ ?>
            
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject"><?php echo ucwords($customer->process_name);?></span>
                </div>
            </div>
            
             <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th> Service </th>
                            <th> Type </th>
                            <th> Status  </th>
                            <th> Start Date</th>
                            <th> End Date  </th>
                            <th> Action  </th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($customer->customer_process) && !empty($customer->customer_process)){ ?>
                        <?php  $i =1; foreach($customer->customer_process as $customer_service_process){	
						?>
                       
                        <tr class="odd gradeX">
                                    
                                   <?php $servicename = _get_service_name($customer_service_process->service_id);?>
                                    <td class="center"><?php echo $servicename->service_name;?></td>
                                    <td class="center"><?php  if($customer_service_process->type == 1){ echo "Compliance"; }else{ echo "Testing"; };?></td>
                                    <td class="center"><?php $data= Admin_status_change($customer_service_process->service_id,$customer_service_process->process_id,$customer_service_process->customer_id);
									 if($data =="Approved"){
										 echo "Completed";
									 }else{
										  echo "In Progress";
									 }
									 ?></td>
                                    <td class="center"><?php echo date("d-m-Y", strtotime($customer_service_process->start_date));?></td>
                                     <td class="center"><?php if(isset($customer_service_process->end_date) && !empty($customer_service_process->end_date)){
										  echo date("d-m-Y", strtotime($customer_service_process->end_date));
									 }?>
									 </td>
                                     <td class="center"><a href="<?= base_url();?>qsa/qsa_view?process_id=<?php echo ID_encode($customer_service_process->process_id);?>&service_id=<?php echo ID_encode($customer_service_process->service_id);?>">View</a></td>
                                       
								</tr>
                        <?php $i++; } ?>
						 <?php  } ?>
						  <?php if(isset($customer->customer_testing_process) && !empty($customer->customer_testing_process)){ ?>
						<?php  $i =1; foreach($customer->customer_testing_process as $testing_service_process){	
						?>
                       
							<tr class="odd gradeX">
                                   <?php $servicename = _get_service_name($testing_service_process->testing_id);?>
                                    <td class="center"><?php echo $servicename->service_name;?></td>
                                    <td class="center">Testing</td>
                                    <td class="center"><?php $data= Admin_status_change($testing_service_process->testing_id,$testing_service_process->process_id,$testing_service_process->customer_id);
									 if($data =="Approved"){
										 echo "Completed";
									 }else{
										  echo "In Progress";
									 }
									 ?></td>
                                    <td class="center"><?php echo date("d-m-Y", strtotime($testing_service_process->start_date));?></td>
                                     <td class="center"><?php if(isset($testing_service_process->end_date) && !empty($testing_service_process->end_date)){
										  echo date("d-m-Y", strtotime($testing_service_process->end_date));
									 }?>
									 </td>
                                     <td class="center"><a href="<?= base_url();?>qsa/qsa_view?process_id=<?php echo ID_encode($testing_service_process->process_id);?>&service_id=<?php echo ID_encode($testing_service_process->testing_id);?>">View</a></td>
                                       
								</tr>
                        <?php $i++; } ?>
                        <?php  } ?>

                    </tbody>
                </table>
            </div>
            <?php //} ?>
            <?php } ?>
             <?php } ?>
			
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>








<!-- END PAGE CONTENT-->
</div>
</div>	
<!-- Button trigger modal -->