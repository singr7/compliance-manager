<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);die;
?>				
<?php echo get_flashdata(); ?>
<style>
.p-10{
	padding:10px;
}
.row_end{
	border-bottom:1px solid #ccc;
}
.border{
	border: 1px solid #ccc;
}
</style>
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
.border_1{
    border:1px solid #ccc;
}
.p-5{
    padding:5px;
}
.no-margin{
    margin:0px;
}
.bg{
    background:#082435;
}
.no-padding{
	padding:0px;
}
.chart_main_div{
	padding:10px;
}
.chart{
	border:1px solid #ccc;
	padding:10px;
}
.canvasjs-chart-canvas{
	width:400px !important;
}

</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> Customers View </span>
                </div>
                
                    
            </div>
            
                    
                    <div class="row p-10 row_end">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Name:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->full_name;?></label>
							</div>
							
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Unique ID:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->unique_id;?></label>
							</div>
							
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Email Id:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->email;?></label>
							</div>
							
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Phone Number:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->phone_number;?></label>
							</div>
							
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Company Name:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->company_name;?></label>
							</div>
							
						</div>
						
						
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Company contact number:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->company_number;?></label>
							</div>
							
						</div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"><strong>Address:</strong></label>
							</div>
							<div class="col-md-6 portlet-body form">
								<label class="control-label" style="color: #697882;"> <?php echo $customer_details->address;?></label>
							</div>
							
						</div>
                        
					</div>
					
					
					<!-- New Row -->
					
					
					<div class="row p-10">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
							<div class="portlet-body form text-center">
								<label class="control-label" style="color: #697882;font-size:20px;"><strong>PROCESSES</strong></label>
							</div>
							
						</div>
                                        
								   <?php if(isset($customer_process) && !empty($customer_process)){ ?>
									<?php foreach($customer_process as $process){ 	?>
							  
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10" style="<?php if(isset($process->customer_process) && !empty($process->customer_process)){ echo 'min-height: 220px';} ?>" >
								<div class="border_1 col-md-12 col-lg-12 no-padding" style="min-height:220px;">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
								<div class="col-md-12 portlet-body text-center p-10 border bg">
									<label class="control-label" style="color: #ffffff;"><strong><?php echo $process->process_name;?></strong></label>
								</div>
								
								
							</div>
							 <?php if(isset($process->customer_process) && !empty($process->customer_process)){ ?>
							 <?php foreach($process->customer_process as $customerprocess) { 
							 $status=Admin_status_change($customerprocess->service_id,$customerprocess->process_id,$customerprocess->customer_id);
							 
							 ?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
								<?php $servicename = _get_service_name($customerprocess->service_id);?>
								<div class="col-md-4">
									<div class="p-5 border_1" style="border:none;">
										<label class="control-label no-margin" style="color: #697882;"><strong><a href='<?= base_url();?>admin/compliances/view_compliances?id=<?php echo ID_encode($customerprocess->process_id);?>&&service_id=<?php echo ID_encode($customerprocess->service_id);?>&&cus_id=<?php echo ID_encode($customerprocess->customer_id);?>&&process_id=<?php echo ID_encode($customerprocess->process_id);?>&&qa_id=<?php echo ID_encode($customerprocess->qa_id);?>&&qsa_id=<?php echo ID_encode($customerprocess->qsa_id);?>'><?php echo $servicename->service_name;?></a></strong></label>
											
									</div>
									</div>
								<div class="col-md-4 p-5">
										<label class="control-label" style="color: #697882;">Status: <?php if(isset($status) && !empty($status)){
										if(trim($status)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?></label>
                                       <?php if(isset($customerprocess->start_date) && !empty($customerprocess->start_date)){ ?>
									<label class="control-label" style="color: #697882;">Start Date: <?php echo date( 'd-m-Y', strtotime($customerprocess->start_date)) ;?></label>
                                        <?php } ?>
                                        <?php if(isset($customerprocess->end_date) && !empty($customerprocess->end_date)){ ?>
									<label class="control-label" style="color: #697882;">End Date: <?php echo date( 'd-m-Y', strtotime($customerprocess->end_date));?></label>
                                            <?php } ?>
								</div>
								<div class="col-md-4 p-5">
								   <p><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 show_chart" style="top:-21px;width: 213px;">
											<button  type="button" onClick="chartPOP(<?php echo ID_encode($customerprocess->service_id).",".ID_encode($customerprocess->customer_id).",".ID_encode($customerprocess->process_id);?>);" data-toggle="modal" data-target="#myModal">View Status</button>
						
										</div></p>
								</div>
								
							</div>
							
                                                             <?php } ?>
															  <?php } ?>
							<!--  start for test servive name -->
							<?php if(isset($process->customer_testing_process) && !empty($process->customer_testing_process)){ ?>
							 <?php foreach($process->customer_testing_process as $customertestingprocess) { 
							 
							 
							 
							// pr($customertestingprocess);
							 $status=Admin_status_change($customertestingprocess->testing_id,$customertestingprocess->process_id,$customertestingprocess->customer_id);
							 
							 ?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
								<?php $servicename = _get_service_name($customertestingprocess->testing_id);?>
								<div class="col-md-4">
									<div class="p-5 border_1" style="border:none;">
										<label class="control-label no-margin" style="color: #697882;"><strong><a href='<?= base_url();?>admin/testing/view_testing_project?id=<?php echo ID_encode($customertestingprocess->process_id);?>&&testing_id=<?php echo ID_encode($customertestingprocess->testing_id);?>&&customer_id=<?php echo ID_encode($customertestingprocess->customer_id);?>&&process_id=<?php echo ID_encode($customertestingprocess->process_id);?>&&qa_id=<?php echo ID_encode($customertestingprocess->qa_id);?>&&qsa_id=<?php echo ID_encode($customertestingprocess->qsa_id);?>'><?php echo $servicename->service_name;?></a></strong></label>
											
									</div>
									</div>
								<div class="col-md-4 p-5">
										<label class="control-label" style="color: #697882;">Status: <?php if(isset($status) && !empty($status)){
										if(trim($status)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?></label>
                                       <?php if(isset($customertestingprocess->start_date) && !empty($customertestingprocess->start_date)){ ?>
									<label class="control-label" style="color: #697882;">Start Date: <?php echo date( 'd-m-Y', strtotime($customertestingprocess->start_date)) ;?></label>
                                        <?php } ?>
                                        <?php if(isset($customertestingprocess->end_date) && !empty($customertestingprocess->end_date)){ ?>
									<label class="control-label" style="color: #697882;">End Date: <?php echo date( 'd-m-Y', strtotime($customertestingprocess->end_date));?></label>
                                            <?php } ?>
								</div>
								<div class="col-md-4 p-5">
								   <p><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 show_chart" style="top:-21px;width: 213px;">
											<button  type="button" onClick="chartPOP(<?php echo ID_encode($customertestingprocess->testing_id).",".ID_encode($customertestingprocess->customer_id).",".ID_encode($customertestingprocess->process_id);?>);" data-toggle="modal" data-target="#myModal">View Status</button>
						
										</div></p>
								</div>
								
							</div>
							
                                                             <?php } ?>
															  <?php } ?>
							<!--  start for test servive name -->
							
						</div>
						</div>
                         <?php }?>
						 <?php }?>
						
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<div class="p-10">
<!--								<button type="submit" class="btn blue" style="padding: 8px 40px;margin-right:10px;">Submit</button>-->
								<button type="submit" class="btn green cancel-button" style="padding: 8px 40px;">Back</button>
							</div>
						</div>
						
					</div>
					
                    
                    <!-- END PAGE BASE CONTENT -->
                </div>   
               
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Status</h4>
      </div>
      <div class="modal-body">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 chart_main_div">
			<div class="chart" style="height:400px">
				<div id="chartContainer" style="height: 300px; width: 100%;"></div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 chart_main_div">
			<div class="chart" style="height:400px">
				<div id="chartContainer_1" style="height: 300px; width: 100%;"></div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script>
    $('.cancel-button').on('click', function () {
        url = '<?php echo base_url(); ?>admin/customer';
        location = url;
    });
    </script>
	  <script>
	  
	 function chartPOP(service_id,customer_id,process_id){
	//	  alert('uuuuuuuuu');
	  $.ajax({
			  type:"POST",
			  url:"<?php echo base_url() ?>admin/customer/piaChart",
			  data:{service_id:service_id,customer_id:customer_id,process_id:process_id},
			  success:function(data){
					var   response=data.split(",");
						var chart = new CanvasJS.Chart("chartContainer", {
						animationEnabled: true,
						title: {
							fontSize: 20,
							text: " Total Question:"+response[0]
						},
						
						data: [{
							type: "pie",
							startAngle: 240,
							yValueFormatString: "##0\"\"",
							indexLabel: "{label} {y}",
							dataPoints: [
							
							
								{y: response[1], label: "Attempted"},
								{y: response[0] - response[1], label: "Not Attempted",color: "Indigo"},
								//{y: 7.06, label: "Remaining"},
								//{y: 4.91, label: "rejected"}
								
							]
						}]
					});
			var chart_1 = new CanvasJS.Chart("chartContainer_1", {
			animationEnabled: true,
			title: {
				fontSize: 20,
				text: " Attempted Question:"+response[1]
			},
			data: [{
				type: "pie",
				startAngle: 140,
				yValueFormatString: "##0\"\"",
				indexLabel: "{label} {y}",
				dataPoints: [
					{y:response[5], label: "Assigned to QSA"},
					{y:response[6], label: "Assigned to QA"},
					{y:response[3], label: "Approved by QA", color: "Indigo"},
					{y:response[7], label: "Marked as Incompleted"},
					{y:response[9], label: "Disapproved by QA"},
					{y:response[8], label: "Disapproved by QSA"}
					
				]
			}]
		});
			chart.render();
			chart_1.render();
			  }
		});
			
	
}
</script>

