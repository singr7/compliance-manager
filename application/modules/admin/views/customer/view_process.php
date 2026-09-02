<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);die;
?>				
<?php echo get_flashdata(); ?>
<style>
.p-10{
	padding:10px;
}
.pl-4{
	padding-left:4px;
}
.row_end{
	border-bottom:1px solid #ccc;
}
.border{
	border: 1px solid #ccc;
}
.services_date{
    padding:5px 0px 0px 0px !important;
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
    padding-top: 0px;
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
.both_chart{
	padding:15px 0px;
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
.show_chart{
    width: 49%;
    display: inline-block;
	text-align:right;
}
.w-100{
	width:100%;
}
.control-label{
	padding-top: 10px;
}
.process_name{
	width: 49%;
    display: inline-block;
	text-align:left;
}
.status_div{
	min-height:127px;
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> Customers Process View </span>
                </div>
            </div>
		    <div class="row">
			<?php
				$moves=Admin_status_change(@$process_details->customer_process[0]->service_id,@$process_details->customer_process[0]->process_id,@$process_details->customer_process[0]->customer_id);
			?>
					
						
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
								<div class="col-md-4 portlet-body  p-10 ">
									<label class="control-label" style="color: #697882;"><strong>Process Name:</strong></label>
								</div>
								<div class="col-md-4 portlet-body p-10 border " style="border:none;">
									<label class="control-label" style="color: #697882;"><?php echo $process_details->process_name;?> </label>
								</div>
							
								<div class="col-md-4 portlet-body p-10 ">
								<?php $archived = _check_archived($process_details->id); ?>
							<?php 
								if(trim($moves)=="Approved"){
									if($archived){ ?>
									<div class="">
											<a href="javascript:;" class="btn blue">Moved to Archived</a>
									</div>
									<?php }else{ ?>
									<div class="">
											<a href="<?php echo base_url();?>admin/customer/move_archive?id=<?php echo ID_encode($process_details->id);?>" class="btn blue">Move to Archieve</a>
									</div>
									<?php }
									} ?>
								</div>
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-4 portlet-body  p-10 pl-4">
									<label class="control-label" style="color: #697882;"><strong>Status :</strong></label>
								</div>
								<div class="col-md-4 portlet-body p-10 ">
								<?php
									if(trim($moves)=="Approved"){ ?>
										<label class="control-label" style="color: #697882;"> Completed</label>
								<?php 	}else{ ?>
										<label class="control-label" style="color: #697882;">In Progress</label>
								<?php	}
								?>
								</div>
							</div>
						</div>
					<?php 
					$QsaName=user_name(@$process_details->customer_process[0]->qsa_id);
					$QaName=user_name(@$process_details->customer_process[0]->qa_id);
					//pr($process_details->customer_process);
					?>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10">
								<div class="col-md-6 portlet-body p-10 ">
									<label class="control-label" style="color: #697882;"><strong>Start Date:</strong></label>
								</div>
								<div class="col-md-6 portlet-body p-10">
									<label class="control-label" style="color: #697882;"><?php echo date( 'd-m-Y', strtotime($process_details->created_date) );?></label>
								</div>
								
							</div>
							
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10">
								<div class="col-md-6 portlet-body p-10 ">
									<label class="control-label" style="color: #697882;"><strong>QSA Name:</strong></label>
								</div>
								<div class="col-md-6 portlet-body p-10">
									<label class="control-label" style="color: #697882;"><?php echo @$QsaName->full_name; ?></label>
								</div>
								
							</div>
							
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10">
								<div class="col-md-6 portlet-body p-10 ">
									<label class="control-label" style="color: #697882;"><strong>QA Name:</strong></label>
								</div>
								<div class="col-md-6 portlet-body p-10">
									<label class="control-label" style="color: #697882;"><?php echo @$QaName->full_name; ?></label>
								</div>
								
							</div>
							
						</div>
						
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 p-10">
								<div class="col-md-12 portlet-body p-10 ">
									<label class="control-label" style="color: #697882;"><strong>Services:</strong></label>
								</div>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 p-10">
							<?php if(isset($process_details->customer_process) && !empty($process_details->customer_process)){?>
							<?php foreach($process_details->customer_process as $service){ 
							?><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
									<div class="col-md-6 portlet-body p-10 text-center w-100">
										  <?php $servicename = _get_service_name($service->service_id);?>
											<div class="col-md-12 portlet-body p-10 border text-center status_div">
												<div class="process_name"><label class="control-label" style="color: #697882;"><a href="<?= base_url();?>admin/compliances/view_compliances?id=<?php echo ID_encode($service->process_id);?>&&service_id=<?php echo ID_encode($service->service_id);?>&&cus_id=<?php echo ID_encode($service->customer_id);?>&&process_id=<?php echo ID_encode($service->process_id);?>&&qa_id=<?php echo ID_encode($service->qa_id);?>&&qsa_id=<?php echo ID_encode($service->qsa_id);?>"><?php echo $servicename->service_name;?>(Compliances)</a></label></div>
												 <div class="show_chart">
											<button  type="button" class="btn yellow" onClick="chartPOP(<?php echo ID_encode($service->service_id).",".ID_encode($service->customer_id).",".ID_encode($service->process_id);?>);" data-toggle="modal" data-target="#myModal">View Status</button>
						
										</div>
											<?php if(isset($service->start_date) && !empty($service->start_date)){ ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form  services_date2">
														<label class="control-label" style="color: #697882;">Status:</label>
															<label class="control-label" style="color: #697882;">
															<?php if(isset($moves) && !empty($moves)){
															if(trim($moves)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?> 
														</label>
													</div>
											</div>
										<?php } ?>
										<?php if(isset($service->start_date) && !empty($service->start_date)){ ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form  services_date2">
															<label class="control-label" style="color: #697882;">Start Date:</label>
															<label class="control-label" style="color: #697882;"> <?php echo date( 'd-m-Y', strtotime($service->start_date));?></label>
													</div>
											</div>
										<?php } ?>
										
										<?php if(isset($service->end_date) && !empty($service->end_date)){ ?>
										 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form  services_date">
															<label class="control-label" style="color: #697882;">End Date:</label>
															<label class="control-label" style="color: #697882;"> <?php echo date( 'd-m-Y', strtotime($service->end_date));?></label>
													</div>
											</div>
										<?php } ?>
											</div>
											
									
										
									</div>
									</div>
							<?php } ?>
							
						
									<?php }else{ ?>
							<p>No Services asigned.</p>
									<?php } ?>
									
					<!-- start for testing services  -->
						<?php if(isset($process_details->customer_testing_process) && !empty($process_details->customer_testing_process)){?>
							<?php foreach($process_details->customer_testing_process as $testingservice){ 
							?><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
									<div class="col-md-6 portlet-body p-10 text-center w-100">
										  <?php $testingservicename = _get_service_name($testingservice->testing_id);?>
											<div class="col-md-12 portlet-body p-10 border text-center status_div">
												<div class="process_name"><label class="control-label" style="color: #697882;"><a href="<?= base_url();?>admin/testing/view_testing_project?id=<?php echo ID_encode($testingservice->process_id);?>&&testing_id=<?php echo ID_encode($testingservice->testing_id);?>&&customer_id=<?php echo ID_encode($testingservice->customer_id);?>&&process_id=<?php echo ID_encode($testingservice->process_id);?>&&qa_id=<?php echo ID_encode($testingservice->qa_id);?>&&qsa_id=<?php echo ID_encode($testingservice->qsa_id);?>"><?php echo $testingservicename->service_name;?>(Testing)</a></label></div>
												 <div class="show_chart">
											<button  type="button" class="btn yellow" onClick="chartPOP(<?php echo ID_encode($testingservice->testing_id).",".ID_encode($testingservice->customer_id).",".ID_encode($testingservice->process_id);?>);" data-toggle="modal" data-target="#myModal">View Status</button>
						
										</div>
											<?php if(isset($testingservice->start_date) && !empty($testingservice->start_date)){ ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form  services_date2">
														<label class="control-label" style="color: #697882;">Status:</label>
															<label class="control-label" style="color: #697882;">
															<?php if(isset($moves) && !empty($moves)){
															if(trim($moves)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?> 
														</label>
													</div>
											</div>
										<?php } ?>
										<?php if(isset($testingservice->start_date) && !empty($testingservice->start_date)){ ?>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form  services_date2">
															<label class="control-label" style="color: #697882;">Start Date:</label>
															<label class="control-label" style="color: #697882;"> <?php echo date( 'd-m-Y', strtotime($testingservice->start_date));?></label>
													</div>
											</div>
										<?php } ?>
										
										<?php if(isset($testingservice->end_date) && !empty($testingservice->end_date)){ ?>
										 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form  services_date">
															<label class="control-label" style="color: #697882;">End Date:</label>
															<label class="control-label" style="color: #697882;"> <?php echo date( 'd-m-Y', strtotime($testingservice->end_date));?></label>
													</div>
											</div>
										<?php } ?>
											</div>
											
									
										
									</div>
									</div>
							<?php } ?>
							
						
									<?php }else{ ?>
							<!--<p>No Services asigned for testing.</p>-->
									<?php } ?>
									
							</div>
							<!-- end for testing services  -->
							
						</div>
						<!--Pie Charts -->
						
			<!--Pie Charts -->
						
						
						
						
						
                    </div>
                    
                    
					
					
					<!-- New Row -->
					
					
					
					
                    
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
		  <!-- <div class="bttn">
		     <!-- <button class="">View detail</button>
		   </div>-->
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

<?php  //echo $total_question-($approved + $rejected)/10; ?>
<script>
    $('.cancel-button').on('click', function () {
        url = '<?php echo base_url(); ?>admin/customer';
        location = url;
    });
	/*$('.show_chart a').click(function(){
		$('.both_chart').slideToggle(350);
	})*/
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
							//	{y: 4.91, label: "rejected"}
								
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