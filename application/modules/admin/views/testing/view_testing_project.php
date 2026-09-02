<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo->full_name);die;
?>				
<?php echo get_flashdata(); ?>
<style>
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
.basic{
	width: 100%;
    float: left;
    clear: both;
}
.no_border{
	border:none;
}
.cust_btn{
	/*padding: 10px 50px 10px !important;*/
	width:100%;
}
.check_custm{
	border-color: #116f94 !important;
}
.portlet.box.red {
    border: 1px solid #2d3a43;
    border-top: 0;
}
.portlet.box.red>.portlet-title, .portlet.red, .portlet>.portlet-body.red {
    background-color: #2d3a43;
}

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
.loader{
	margin: 0 auto;
    display: block;
    position: absolute;
    left: 45%;
}
.questionnaire_heading{
    font-size: 22px;
    font-weight: 600;
    border: none;
}
.questionnaire_heading label{
	font-weight: 600 !important;
    border-bottom: 1px solid #6978826b;
}
.mb-0{
	margin-bottom:0px;
}
.mt-5{
	margin-top:5px;
}
.basic{
	width: 100%;
    float: left;
    clear: both;
}
.no_border{
	border:none;
}
.border-tab{
	border: 1px solid #2d3a43;
	border-radius: 5px;
}
.m-0{
	margin:0px;	
}
.portlet.box.red {
    border: 1px solid #2d3a43;
    border-top: 0;
}
.portlet.box.red>.portlet-title, .portlet.red, .portlet>.portlet-body.red {
    background-color: #2d3a43;
}
.p-5{
	padding:5px;
}
end_date > input{
	padding-right: 30px;
}
.end_date{
	position:relative;
}
.end_date a{
	position: absolute;
    right: 16px;
    top: calc(50% - 10px);
    color: #fff;
    background: #2d3a43;
    height: 34px;
    width: 30px;
    padding: 7px;
    border-radius: 3px;
}
.testings span{
    position: absolute;
    left: -10px;
    top: 0px;
}
.caption{
	width:94%;
}
.has-success label span.check_all {
	left:16px;
}
.status{
	float: right;
	font-size:14px;
}
.status1{
	    margin-left: 309px;
		font-size:14px;
}
.pl-0{
	padding-left:0px;
}
.questionnaire_heading{
    font-size: 22px;
    font-weight: 600;
    border: none;
	text-align: center;
}
.questionnaire_heading label{
	font-weight: 600 !important;
    border-bottom: 1px solid #5aa3e1ad;
}
.questionnaire_heading label{
	color: #5c9bd1;
    text-transform: uppercase;
    font-size: 22px;
}
</style>
<div id='msgShow'></div>
<div class="row">
<?php
 $serviceDate = date('d-m-Y',strtotime(@$start_date[0]->start_date));
 $status=Admin_status_change(ID_decode($_GET['testing_id']),ID_decode($_GET['process_id']),ID_decode($_GET['customer_id']));
 $approvedData= Admin_status_change(ID_decode($_GET['testing_id']),ID_decode($_GET['process_id']),ID_decode($_GET['customer_id'])); 
 ?>

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> View <?php echo $testing_name->service_name;?> Project (Compliance)</span>
					<span class="caption-subject  status1" style='font-size: 15px;'> Status: <?php if(isset($status) && !empty($status)){
							if(trim($status)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?> </span>
					<?php if(isset($start_date[0]->end_date) && !empty($start_date[0]->end_date)){ ?>
								<span class="caption-subject  status" style='font-size: 14px;'> End Date:  <?php echo $start_date[0]->end_date; ?></span>
					<?php } ?>
                </div>
            </div>
			<form action="<?php echo base_url(); ?>admin/testing/select_multiple_change_status" method="POST" enctype="multipart/form-data" id="report">
            <div class="row p-10">
					   <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 p-10 pl-0">
									<div class="col-md-12 portlet-body p-10 pl-0 border questionnaire_heading ">
									<span>&nbsp;</span>
										<label class="control-label">Questionnaire</label>
									</div>
								</div>
								<?php if(empty($start_date[0]->end_date)){ ?>
								<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-center" style="padding: 5px;     padding-top: 35px;">
								<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-center" style="padding: 5px;     padding-top: 35px;">
								<div class="">
										<div class="md-checkbox has-success testings">
											<input type="checkbox" id="ckbCheckAll" class="md-check"> 
											<label for="ckbCheckAll">
												<span class="inc"></span>
												<span class="check check_all"></span>
												<span class="box" style="left:12px;"></span>
											</label>
											
										<span style="margin-left: 0px;">All</span></div>
									</div>
							</div>
							</div>
							<?php	} ?>
							</div>
							    <?php $datas=array();if(isset($questionair) && !empty($questionair)){
                                  $i=1; foreach($questionair as $question){
								  $customer_id=ID_decode($_GET['customer_id']);
								  $process_id=ID_decode($_GET['process_id']);
								  $datas[]=common_function(@$question->id,@$question->service_id,$process_id,$customer_id);
								  $data=common_function(@$question->id,@$question->service_id,$process_id,$customer_id);
								  $QAname=user_name(@$data[0]->qa_id);
								  $docsData=common_function_for_docs(@$question->id,@$question->service_id,$process_id,$customer_id);
								 ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 p-10">
									<div class="portlet box red basic">
										<div class="portlet-title">
											<div class="caption">
												Question <?php echo $i;?> : <?php echo @$question->question;?>
												<input type="hidden" name="question_id[<?php  echo $question->id; ?>][]" value="<?php  echo $question->id; ?>">
												<input type="hidden" name="servide_id" value="<?php echo $question->service_id; ?>">
												<input type="hidden" name="process_id" value="<?php echo ID_decode($_GET['process_id']); ?>">
												<input type="hidden" name="customer_id" value="<?php echo ID_decode($_GET['customer_id']); ?>">
												
											</div>
											<div class="tools">
                                            <a href="javascript:;" class="<?php if(isset($data[0]->questionnaire_id) && !empty($data[0]->questionnaire_id)){ echo "collapse";}else{ echo "expand";};?>" data-original-title="" title=""> </a>
											</div>
										</div>
										<div class="portlet-body tabs-below" style="display: <?php if(isset($data[0]->questionnaire_id) && !empty($data[0]->questionnaire_id)){ echo "block";}else{ echo "none";};?>; width: 100%; float: left; clear: both;">
											<div class="tab-content" style="width: 100%;float: left;clear: both; ">
												<div class="tab-pane active" id="tab_4_1" style="width: 100%; float: left; clear: both;">
													<?php if(empty($data)){ echo "<p style='color: #697882;text-align:center;font-size:19px;'>No Document Available.</p>"; }; ?>
													
														 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
											<?php if(isset($docsData) && !empty($docsData)){
													foreach($docsData as $docsDatas){ 
													$extension=explode('.',$docsDatas->docs);
													$_blank="";
													$file="";
													 if(!empty($extension[1])){
													 if(trim($extension[1]) =="jpeg"){ 
														$_blank="target='_blank'";
														$file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="jpg"){
														 $_blank="target='_blank'";
														 $file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="gif"){
														 $_blank="target='_blank'";
														 $file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="png"){
														 $_blank="target='_blank'";
														$file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="pdf"){
														 $_blank="target='download'";
														$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="doc"){
														 $_blank="target='download'";
														 $file="<i class='fa fa-file-text-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="docx"){
														 $_blank="target='download'";
														  $file="<i class='fa fa-file-text-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="txt"){
														 $_blank="target='download'";
														  $file="<i class='fa fa-file-text fa_icon' aria-hidden='true'></i>";
													 }
													}
													?>
														<div class="col-md-4 portlet-body form-group">
														<label class="control-label" style="color: #697882;">
														<strong>
															<a  href='<?php echo base_url()?>uploads/evidence/<?php echo $docsDatas->docs  ?>' data-toggle="tooltip" title="<?php echo $docsDatas->docs  ?>" download ><?php echo $file; ?></a>
															</strong></label>
															<p class="mb-0" style="color: #697882;"><?php echo date("d-m-Y H:i", strtotime($docsDatas->updated_date)); ?></p>
														</div>
													<?php  } } ?>
												
													</div>
												</div>
												
													<?php $cusData=customer_comments(@$question->id,@$question->service_id,$process_id,$customer_id);
												if(isset($cusData) && !empty($cusData)){ 
													foreach($cusData as $cusDatas){ 
													 $customerName=user_name(@$cusDatas->login_user_id);
														$CustomerFirestName=explode(' ',$customerName->full_name); ?>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
																<div class="col-md-4">
																	<label class="control-label" style="color: #697882;"><strong><?php echo $CustomerFirestName[0]; ?> :</strong></label>
																</div>
																<div class="col-md-8">
																	<label class="control-label" style="color: #697882;"><?php echo $cusDatas->comments."&nbsp;(".date("d-m-Y H:i", strtotime($cusDatas->updated_date)).")"; ?></label>
																</div>
																
															</div>
														</div>
												<?php } } ?>
													
													<!-- start code for admin atatus-->
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
														<?php if(trim(@$data[0]->all_status) == "0"){  ?>
													<div class="col-md-12 portlet-body p-5">
														<label class="control-label" style="color: #697882;">
															<strong>Status: Assigned To QSA on <?php echo date("d-m-Y H:i", strtotime($data[0]->updated_date));?></strong>
														</label>
													</div>
												<?php }elseif(trim(@$data[0]->all_status) == "1"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Assigned to QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="2"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Disapproved By QSA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="3"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Marked as Incompleted By QSA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="4"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Approved By QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="5"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Disapproved By QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="6"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Marked as Incompleted By QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="7"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Approved by QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->all_status) =="8"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status: Disapproved by QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   }elseif(trim(@$data[0]->status) =="9"){ ?>
														<div class="col-md-12 portlet-body p-5">
															<label class="control-label" style="color: #697882;"><strong>Status:Marked as Incompleted by QA on <?php echo date("d-m-Y H:i", strtotime($data[0]->all_status_date));?></strong></label>
														</div>
												<?php   } ?>
														
													</div>
													<!-- start code for admin atatus-->
													<!-- start modification request for qa -->
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<?php 
														$qaName=user_name(@$data[0]->qa_id);
															if(trim(@$data[0]->qa_modification) =="1" && trim(@$data[0]->admin_qa) =="0"){ ?>
															 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
																<div class="col-md-12 p-5">
																	<label class="control-label" style="color: #697882;"><strong>Modification Request from QA</strong></label>
																	<button type="button" class="btn blue" style="padding: 8px 18px;margin-left:30px;"  onClick="AcceptToQa(<?php echo ID_encode(1).",".ID_encode($question->id).",".ID_encode($question->service_id).",".ID_encode($customer_id).",".ID_encode($process_id);?>)">Accept</button>
																	<button type="button" class="btn default" style="padding: 8px 20px;" onClick="AcceptToQa(<?php echo ID_encode(2).",".ID_encode($question->id).",".ID_encode($question->service_id).",".ID_encode($customer_id).",".ID_encode($process_id);?>)">Reject</button>
																</div>
															</div>
													<?php }  ?>
												  </div>
											</div>
										</div>
												
											</div>
										</div>
									</div>
								</div>
						
								<?php if(empty($start_date[0]->end_date)){ ?>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 p-20">
										<div class="md-checkbox has-success">
											<input type="checkbox" id="checkbox<?php echo $question->id;?>" name="checked[<?php  echo $question->id; ?>][]"  value="<?php  echo $question->id ?>" class="md-check">
											<label for="checkbox<?php echo $question->id;?>">
												<span></span>
												<span class="check check_custm"></span>
												<span class="box"></span>
											</label>
										</div>
									</div>
								  <?php  } ?>
							</div>
                            <?php $i++; } ?>
                            <?php } ?>
						
						</div>
						<?php //if(!empty($datas[0])){ ?>
						 	<?php if(trim(@$approvedData) == "Approved"){
											$disabledd="disabled";									 
										}else{
											$disabledd="";	
										}
										
										?>
                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center" style="height: 89px;padding: 17px; ">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10">
									<div class="col-md-12 portlet-body text-center no_border">
										<button type="submit" <?php echo $disabledd; ?> class="btn blue-sharp cust_btn" name="Approved" value="<?php echo ID_encode(1); ?>">Approved</button>
									</div>
									
								</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10">
									<div class="col-md-12 portlet-body text-center no_border">
										<button type="submit" <?php echo $disabledd; ?> class="btn red cust_btn" name="Disapprove" value="<?php echo ID_encode(2); ?>">Disapprove</button>
									</div>
									
								</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10">
									<div class="col-md-12 portlet-body text-center no_border">
										<button type="submit" <?php echo $disabledd; ?> class="btn yellow cust_btn" name="Markincomplete" value="<?php echo ID_encode(4); ?>">Mark Incomplete</button>
									</div>
									
								</div>
								<?php 
								 if(trim(@$approvedData) == "Approved"){
									$disabled="";									 
										if(empty($start_date[0]->end_date)){
											$disabled= "";
										}else{
											$disabled= "disabled";
										}
								 ?>
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10" >
									<div class="col-md-12 portlet-body text-center no_border end_date">
										<input id="star_date" name="" <?php echo $disabled; ?> placeholder="End Date"  class="btn blue-oleo cust_btn">
										<a href="javascript:void(0)" onClick="EndDate(<?php echo $_GET['id'].",".$_GET['customer_id'].",".$_GET['process_id'].",".$_GET['testing_id'] ?>);">Go</a>
										
									</div>
									
								</div>
								<?php  } ?>
								<!--<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10" >
									<div class="col-md-12 portlet-body text-center no_border">
										<button type="button" <?php if(trim(@$approvedData) == "Approved")
								{ echo "";}else{echo "disabled";} ?> class="btn blue-oleo cust_btn" onClick="EndDate(<?php echo $_GET['id'].",".$_GET['customer_id'].",".$_GET['process_id'].",".$_GET['testing_id'] ?>);">End Date</button>
									</div>
									
								</div>-->
									<?php if(!empty($rot)){
									  $extension=explode('.',$rot->report_docs);
									  $file="";
									if(!empty($extension[1])){
										if(trim($extension[1]) =="pdf"){
											$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
										}
									}
									?>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10" >
										<div class="col-md-12 portlet-body text-center no_border">
											<div class="basic border-tab">
												<p class="m-0 portlet red p-5" style="color:#fff">ROT</p>
												<div class="col-md-12 portlet-body form-group">
													<label class="control-label" style="color: #697882;">
														<strong>
															<a  href='<?php echo base_url()?>uploads/report/<?php  echo $rot->report_docs; ?>'  data-toggle="tooltip" title="<?php echo $rot->report_docs;  ?>" download><?php  echo $file; ?></a>
															
															<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_aoc_roc(<?php echo @ID_encode($rot->id).",".@ID_encode($rot->service_id).",".@ID_encode($rot->process_id).",".@ID_encode($rot->year).",".@ID_encode($rot->customer_id); ?>);'><span class='glyphicon glyphicon-trash'></span></button>
															<p class="mb-0 mt-5" style="color: #697882;"><?php  echo date("d-m-Y H:i", strtotime($rot->updated_date)); ?></p>
														</strong>
													</label>
												</div>
											</div>
										</div>
									</div>
								<?php }else{ ?>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10" >
									<div class="col-md-12 portlet-body text-center no_border">
										<input type="file" id="imguploadroc" onchange="validatePDF(this)" name="imguploadroc" style="display:none"/> 
											<button id="OpenImgUploadROC"  <?php if(trim(@$approvedData) == "Approved"){ echo "";}else{echo "disabled";} ?> class="btn blue-sharp cust_btn" style="width:100%"><img id="loaderroc" class="loader" src="<?php echo base_url(); ?>/assets/pages/img/ajax-loader.gif"  style="display:none;">Upload ROT</button>
											
											
									</div>
								</div>
								<?php } ?>
								<?php if(!empty($aot)){
									  $extension=explode('.',$aot->report_docs);
									  $file="";
									if(!empty($extension[1])){
										if(trim($extension[1]) =="pdf"){
											$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
										}
									}
									?>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10" >
										<div class="col-md-12 portlet-body text-center no_border">
											<div class="basic border-tab">
												<p class="m-0 portlet red p-5" style="color:#fff">AOT</p>
												<div class="col-md-12 portlet-body form-group">
													<label class="control-label" style="color: #697882;">
														<strong>
															<a  href='<?php echo base_url()?>uploads/report/<?php  echo $aot->report_docs; ?>'  data-toggle="tooltip" title="<?php echo $aot->report_docs;  ?>" download><?php  echo $file; ?></a>
															
															<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_aoc_roc(<?php echo @ID_encode($aot->id).",".@ID_encode($aot->service_id).",".@ID_encode($aot->process_id).",".@ID_encode($aot->year).",".@ID_encode($aot->customer_id); ?>);'><span class='glyphicon glyphicon-trash'></span></button>
															<p class="mb-0 mt-5" style="color: #697882;"><?php  echo date("d-m-Y H:i", strtotime($aot->updated_date)); ?></p>
														</strong>
													</label>
												</div>
											</div>
										</div>
									</div>
								<?php }else{ ?>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 p-10" >
									<div class="col-md-12 portlet-body text-center no_border">
											<input type="file" onchange="validatePDF(this)" id="imguploadaoc" style="display:none"/> 
											<button id="OpenImgUploadAOC" <?php if(trim(@$approvedData) == "Approved"){ echo "";}else{echo "disabled";} ?> name="OpenImgUploadAOC" class="btn blue-sharp cust_btn" style="width:100%"><img id="loaderaoc" class="loader"src="<?php echo base_url(); ?>/assets/pages/img/ajax-loader.gif"  style="display:none;">Upload AOT</button>
											
									</div>
								</div>
							</div> 
						<?php }   //} ?>
							
						</form>	
					</div>
                 	<!-- New Row -->
				   <!-- END PAGE BASE CONTENT -->
                </div>   
               
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
// start reply admin accept/reject to qsa
	function AcceptToQsa(id,question_id,service_id,customer_id,process_id){
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/compliances/accepttoqsa",
				data:{accepted_id:id,question_id:question_id,service_id:service_id,customer_id:customer_id,process_id:process_id},
				success:function(success){
					 location.reload();
				}
			});
		
	}
// end reply admin accept/reject to qsa

// start reply admin accept/reject to qa
	function AcceptToQa(id,question_id,service_id,customer_id,process_id){
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/testing/accepttoqa",
				data:{accepted_id:id,question_id:question_id,service_id:service_id,customer_id:customer_id,process_id:process_id},
				success:function(success){
					//alert(success);
					 location.reload();
				}
			});
		
	}
// end reply admin accept/reject to qsa

// start reply admin accept/reject to qa
	function AcceptToCustomer(id,question_id,service_id,customer_id,process_id){
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/testing/accepttocustomer",
				data:{accepted_id:id,question_id:question_id,service_id:service_id,customer_id:customer_id,process_id:process_id},
				success:function(success){
					 location.reload();
				}
			});
		
	}
// end reply admin accept/reject to qsa

// start reply admin accept/reject to qa
	function AcceptToConsultant(id,question_id,service_id,customer_id,process_id){
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/testing/AcceptToConsultant",
				data:{accepted_id:id,question_id:question_id,service_id:service_id,customer_id:customer_id,process_id:process_id},
				success:function(success){
					 location.reload();
				}
			});
		
	}
// end reply admin accept/reject to qsa
// start reply admin end date 
	function EndDate(project_id,customer_id,process_id,service_id){
		var date=$('#star_date').val();
		if(date ==""){
			alert("Please select");
			return false;
		}else{
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/testing/EndDateformAdmin",
				data:{date:date,service_id:service_id,customer_id:customer_id,process_id:process_id,project_id:project_id},
				success:function(success){
					//alert(success);
					 location.reload();
				}
			});
		}	
	}
// end reply admin end date

// start select multiple check boxes
$(document).ready(function () {
    $("#ckbCheckAll").click(function () {
        $(".md-check").prop('checked', $(this).prop('checked'));
    });
    
    $(".md-check").change(function(){
        if (!$(this).prop("checked")){
            $("#ckbCheckAll").prop("checked",false);
        }
    });
});

// end select multiple check boxes


//validation of file uplaoding 
var formOK = false;
function validatePDF(objFileControl){
 var file = objFileControl.value;
 var len = file.length;
 var ext = file.slice(len - 4, len);
 if(ext.toUpperCase() == ".PDF"){
   formOK = true;
 }
 else{
   formOK = false;
   alert("Only PDF files allowed.");
 }
}

// start ROC file uploading
$('#OpenImgUploadROC').click(function(){ 
event.preventDefault()
$('#imguploadroc').trigger('click');
   $('#imguploadroc').on('change', function() {
	    if(formOK == true){
			var service_id=<?php echo $_GET['testing_id']; ?>;
			var customer_id=<?php echo $_GET['customer_id']; ?>;
			var process_id=<?php echo $_GET['process_id']; ?>;
			var fileInput = document.getElementById('imguploadroc');
			var file = fileInput.files[0];
			var formData = new FormData();
			formData.append('file', file);
			formData.append('service_id', service_id);
			formData.append('customer_id', customer_id);
			formData.append('process_id', process_id);
			$("#loaderroc").hide();
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>admin/testing/FileUploadROC",
				data:  formData,
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function(){
					 $("#OpenImgUploadROC").prop('disabled', true);
					 // show image container
					 $("#loaderroc").show();
				},
				success:function(success){
					 location.reload();
				},complete:function(data){
				// Hide image container
				$("#loaderroc").hide();
			   }
			});		
	  }
	});
 });
 // end ROC file uploading
 
 // start AOC file uploading
$('#OpenImgUploadAOC').click(function(){ 
	event.preventDefault()
	$('#imguploadaoc').trigger('click');
	    $('#imguploadaoc').on('change', function() {
			 if(formOK == true){
				var service_id=<?php echo $_GET['testing_id']; ?>;
				var customer_id=<?php echo $_GET['customer_id']; ?>;
				var process_id=<?php echo $_GET['process_id']; ?>;
				var fileInput = document.getElementById('imguploadaoc');
				var file = fileInput.files[0];
				var formData = new FormData();
				formData.append('file', file);
				formData.append('service_id', service_id);
				formData.append('customer_id', customer_id);
				formData.append('process_id', process_id);
				$("#loaderaoc").hide();
				$.ajax({
					type:"POST",
					url:"<?php echo base_url();?>admin/testing/FileUploadAOC",
					data:  formData,
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function(){
						 $("#OpenImgUploadAOC").prop('disabled', true);
						 // show image container
						 $("#loaderaoc").show();
					},
					success:function(success){
						location.reload();
					},complete:function(data){
					// Hide image container
					$("#loaderaoc").hide();
				   }
				});		
			 }
		});
		

	 });
 // end AOC file uploading
 $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
}); 


$( "#star_date" ).datepicker({
     dateFormat: 'dd-m-yy',
      startDate: '+0d',
      minDate: '<?php echo $serviceDate;?>',
 });

</script>

