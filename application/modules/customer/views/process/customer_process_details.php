<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo->company_name);die;
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

    .p-10{
        padding:10px;
    }
    .p-20{
        padding:20px;
    }
    .row_end{
        border-bottom:1px solid #ccc;
    }
    .border{
        border: 1px solid #ccc;
    }
	.start-date{
		margin-top: -18px;
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


</style>
<div id='msgShow'></div>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
				    <span class="caption-subject bold uppercase"><?php echo $process_name->process_name; ?></span>
                    <!--<span style="margin-left: 130px;font-weight: 600; ">Start Date: <?php echo date('d-m-Y', strtotime($process_name->created_date)); ?></span>
					<!--<span style="margin-left: 130px;font-weight: 600; ">Company Name: <?php echo $userInfo->company_name; ?></span>-->
                </div>
            </div>
            <div class="">
                <div class="row p10">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if(isset($process_details) && !empty($process_details)){ ?>
                        <?php foreach($process_details as $process) {	?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 p-10">
                                <div class="col-md-12 portlet-body text-center p-10 border" style="background-color: #ccc;">
                                    <?php $servicename = _get_service_name($process->service_id);?>
                                    <label class="control-label" style="color: #697882;"><a href="<?= base_url();?>customer/process/service_details?id=<?php echo ID_encode($process->service_id);?>"><strong><?php echo $servicename->service_name;?></strong></a></label>
                                </div>

                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 p-10">
                                <div class="col-md-12 portlet-body text-center p-10">
								<?php if(isset($process->start_date) && !empty($process->start_date)){ ?>
									<div class="col-md-3 portlet-body text-center p-10  start-date">
										<label class="control-label" style="color: #697882;">Start Date: <span>
										<?php echo date("d-m-Y", strtotime($process->start_date)); ?></span></label>
										</div>
										<?php } ?>
									   <?php if(isset($process->end_date) && !empty($process->end_date)){ ?>
											<div class="col-md-3 portlet-body text-center p-10 start-date">
												<label class="control-label" style="color: #697882;">End Date: <span><?php if(isset($process->end_date) && !empty($process->end_date)){
										  echo date("d-m-Y", strtotime($process->end_date));
									 }?></span></label>
										</div>
										<?php } ?>
										<div class="col-md-3 portlet-body text-center p-10 start-date">
										<label class="control-label" style="color: #697882;">Status: <span>
										<?php $data= Admin_status_change($process->service_id,$process->process_id,$process->customer_id);
										if(isset($data) && !empty($data)){
											if(trim($data) =="Approved"){
												echo "Completed";		
											}else{
												echo "In Progress";	
											}
										}
										?></span></label>
										</div>
										<div class="col-md-3 portlet-body text-center p-10 start-date">
										<label class="control-label" style="color: #697882;"><button  type="button" class="btn yellow" onClick="chartPOP(<?php echo ID_encode($process->service_id).",".ID_encode($process->customer_id).",".ID_encode($process->process_id);?>);" data-toggle="modal" data-target="#myModal">View Status</button> <span>
										</span></label>
										</div>
									</div>

                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>

                    </div>
                </div>

            </div>
			<div class="">
                <div class="row p10">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if(isset($testing_details) && !empty($testing_details)){ ?>
                        <?php foreach($testing_details as $testing) { ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 p-10">
                                <div class="col-md-12 portlet-body text-center p-10 border" style="background-color: #ccc;">
                                    <?php $testingname = _get_service_name($testing->testing_id); ?>
                                    <label class="control-label" style="color: #697882;"><a href="<?= base_url();?>customer/process/service_details?id=<?php echo ID_encode($testing->testing_id);?>"><strong><?php echo $testingname->service_name;?></strong></a></label>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 p-10">
								<div class="col-md-12 portlet-body text-center p-10">
								<?php if(isset($testing->start_date) && !empty($testing->start_date)){ ?>
									<div class="col-md-3 portlet-body text-center p-10 start-date">
										<label class="control-label" style="color: #697882;">Start Date: <span>
										<?php echo date("d-m-Y", strtotime($testing->start_date)); ?></span></label>
										</div>
										<?php  } ?>
										<?php	if(isset($testing->end_date) && !empty($testing->end_date)){ ?>
											<div class="col-md-3 portlet-body text-center p-10 start-date">
												<label class="control-label" style="color: #697882;">End Date: <span><?php if(isset($testing->end_date) && !empty($testing->end_date)){
													echo date("d-m-Y", strtotime($testing->end_date));
												}?></span></label>
										</div>
										<?php  } ?>
										
										<div class="col-md-3 portlet-body text-center p-10 start-date">
										<label class="control-label" style="color: #697882;">Status: <span>
										<?php $data= Admin_status_change($testing->testing_id,$testing->process_id,$testing->customer_id);
										if(isset($data) && !empty($data)){
											if(trim($data) =="Approved"){
												echo "Completed";		
											}else{
												echo "In Progress";	
											}
										}
										?></span></label>
										</div>
										<div class="col-md-3 portlet-body text-center p-10 start-date">
										<label class="control-label" style="color: #697882;"><button  type="button" class="btn yellow" onClick="chartPOP(<?php echo ID_encode($testing->testing_id).",".ID_encode($testing->customer_id).",".ID_encode($testing->process_id);?>);" data-toggle="modal" data-target="#myModal">View Status</button><span>
										</span></label>
										</div>
									</div>

                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>	
<!-- Button trigger modal -->

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