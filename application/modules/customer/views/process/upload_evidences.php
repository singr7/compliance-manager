<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);die;
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
.portlet.box.red {
    border: 1px solid #2d3a43;
    border-top: 0;
}
.portlet.box.red>.portlet-title, .portlet.red, .portlet>.portlet-body.red {
    background-color: #2d3a43;
}
input[type=file].form-control{
	height:auto;
}
.p-4{
	padding:4px !important;
}
.delete_btn::before{
	content:"|";
	color:#ccc;
}
.delete_btn{
	border:none;
	color:#ff0000;
	background-color:#ffffff;
	padding-left: 0px;
}
.fa_icon{
	font-size:25px;
}
.questionnaire_heading{
    font-size: 22px;
    font-weight: 600;
    border: none;
	padding-left: 0px;
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
.tab-content{
	width: 100%;
	float: left;
	clear: both;
}
.caption{
	width:94%;
}
.status{
	float: right;
	font-size:14px;
}
.status1{
	    margin-left: 309px;
		font-size:14px;
}


</style>

<div id='msgShow'></div>


<div class="row">
<?php 
$userdata=$this->session->userdata;
$status=Admin_status_change(ID_decode($_GET['id']),$userdata['process_id'],$userdata['userinfo']->id); ?>


    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase">Upload Evidences</span>
					<span class="status1"> Status: <?php if(isset($status) && !empty($status)){
							if(trim($status)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?> </span>
					<?php if(isset($start_date[0]->end_date) && !empty($start_date[0]->end_date)){ ?>
								<span class="status" > End Date:  <?php echo $start_date[0]->end_date; ?></span>
					<?php } ?>
					<?php if(isset($testing_start_date[0]->end_date) && !empty($testing_start_date[0]->end_date)){ ?>
								<span class="status" > End Date:  <?php echo $testing_start_date[0]->end_date; ?></span>
					<?php } ?>
					
                    </div>
            </div>
			<form action="<?php echo base_url(); ?>customer/process/multiple_upload_evidence" method="POST" enctype="multipart/form-data">
            <div class="row p-10">
					   <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 p-10">
									<div class="col-md-12 portlet-body  p-10 border questionnaire_heading">
										<label class="control-label">Questionnaire</label>
									</div>
									
								</div>
							</div>
							  <?php if(isset($get_questionair) && !empty($get_questionair)){ 
										$i=1; foreach($get_questionair as $question){
										$userdata=$this->session->userdata();
										$data=common_function(@$question->id,@$question->service_id,@$userdata['process_id'],@$userdata['userinfo']->id);
										$docsData=common_function_for_docs(@$question->id,@$question->service_id,@$userdata['process_id'],@$userdata['userinfo']->id);
										?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 p-10">
									<div class="portlet box red basic">
										<div class="portlet-title">
											<div class="caption">
												Question <?php echo $i;?> : <?php echo $question->question;?>
												<input type="hidden" name="question_id[<?php  echo $question->id; ?>][]" value="<?php  echo $question->id; ?>">
												<input type="hidden" name="servide_id" value="<?php echo $question->service_id ?>">
												
											</div>
											<div class="tools">
											<a href="javascript:;" class="<?php if(isset($data[0]->questionnaire_id) && !empty($data[0]->questionnaire_id)){ echo "collapse";}else{ echo "expand";};?>" data-original-title="" title=""> </a>
				
											</div>
										</div>
										<div class="portlet-body tabs-below" style="display: <?php if(isset($data[0]->questionnaire_id) && !empty($data[0]->questionnaire_id)){ echo "block";}else{ echo "none";};?>; width: 100%; float: left; clear: both;">
											<div class="tab-content">
												<div class="tab-pane active" id="tab_4_1" style="width: 100%; float: left; clear: both;">
										 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
											<?php 
											if(isset($docsData) && !empty($docsData)){
													foreach($docsData as $docsDatas){ 
													$extension=explode('.',$docsDatas->docs);
													if(!empty($extension[1])){
														$file="";
													    $_blank="";
													 if(trim($extension[1]) =="jpeg"){ 
														$_blank="target='download'";
														$file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="jpg"){
														 $_blank="target='download'";
														 $file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="gif"){
														 $_blank="target='download'";
														 $file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
													 }elseif(trim($extension[1]) =="png"){
														 $_blank="target='download'";
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
													<div class="col-md-4 portlet-body p-5 form-group" style="margin-bottom:0px;">
														<label class="control-label" style="color: #697882;">
															<strong>
																<a  href='<?php echo base_url()?>uploads/evidence/<?php echo $docsDatas->docs  ?>' data-toggle="tooltip" title="<?php echo $docsDatas->docs;  ?>" download><?php echo $file; ?></a>
																<?php if((trim(@$data[0]->status) == '0' || trim(@$data[0]->status) == '2' || trim(@$data[0]->status) == '4')){ 
																if(trim($userInfo->id)==trim($docsDatas->user_id)){
																	if(trim(@$data[0]->all_status) == '0'){
																?>
																<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_dcos(<?php echo @ID_encode($docsDatas->id); ?>);' ><span class='glyphicon glyphicon-trash'></span></button>
																<?php } } } ?>
																<p class="mb-0" style="color: #697882; margin:0px"><?php echo date("d-m-Y H:i", strtotime($docsDatas->updated_date)); ?></p>
															</strong>
														</label>
													</div>
											<?php  } } ?>
												
											</div>
										</div>
													<?php 
													$name=$userdata['userinfo']->full_name;
													$userName=explode(' ',$name);
													$customerComme= customer_comments(@$question->id,@$question->service_id,@$userdata['process_id'],@$userdata['userinfo']->id); 
														if(isset($customerComme) && !empty($customerComme)){
															foreach($customerComme as $customerCommes){
																$customerName=user_name(@$customerCommes->login_user_id);
																$CustomerFirestName=explode(' ',$customerName->full_name);?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
															<div class="col-md-4 portlet-body">
																<label class="control-label" style="color: #697882;"><strong><?php echo ucfirst($CustomerFirestName[0]); ?></strong></label>
															</div>
															<div class="col-md-8 portlet-body">
																<p style="color: #697882; margin: 0px;"><?php echo $customerCommes->comments." &nbsp;(".date("d-m-Y H:i:s", strtotime($customerCommes->updated_date)).")"; ?></p>
															</div>
															
														</div>
													</div>
													<?php } } ?>
												<?php 	if(empty(@$data[0])){ ?>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
															<div class="col-md-4 portlet-body">
																<label class="control-label" style="color: #697882; "><strong><?php echo ucfirst($userName[0]); ?>:</strong></label>
															</div>
															<div class="col-md-8 portlet-body">
																<textarea class="form-control" placeholder="comments" name='comments[<?php echo $question->id;?>][]' rows="3" style="background-color: #f3f5f9;"></textarea>
															</div>
															
														</div>
													</div>
												<?php }elseif((trim(@$data[0]->status) == '0' || trim(@$data[0]->status) == '2' || trim(@$data[0]->status) == '4')){ 
													if(trim(@$data[0]->admin_status) =="0"){	?>
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
															<div class="col-md-4 portlet-body">
																<label class="control-label" style="color: #697882;"><strong><?php echo ucfirst($userName[0]); ?>:</strong></label>
															</div>
															<div class="col-md-8 portlet-body">
																<textarea class="form-control" placeholder="comments" name='comments[<?php echo $question->id;?>][]' rows="3" style="background-color: #f3f5f9;"></textarea>
															</div>
															
														</div>
													</div>
												<?php 	} } ?>
												
												<!--   start staus change -->
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
										<?php if(trim(@$data[0]->all_status) == "0"){  ?>
													<div class="col-md-12">
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
									</div>
												<!--  end  staus change -->
												<?php
													if(empty(@$data[0])){ ?>
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
															<div class="col-md-8 portlet-body p-6 form-group">
																<label class="control-label" style="color: #697882;"><strong></strong></label>
																<tr id="row"><td><input type="file" id="fileuplaod<?php  echo $question->id; ?>"   onchange="return fileValidation(<?php  echo $question->id; ?>)" name="docs[<?php  echo $question->id; ?>][]" mul1tiple="multiple" class="form-control name_list" /></td><td></td></tr>
															</div>
															
															<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center" style="padding: 21px 10px 10px 10px;">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center p-4">
																	<button type="button" onClick="AddMore(<?php  echo $question->id; ?>)" class="btn blue" style="padding: 8px 39px;">Add More</button>
																</div>
															</div>
															
														</div>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dynamic_field<?php  echo $question->id; ?>" style="padding-left: 45px;"></div>
													<?php }elseif((trim(@$data[0]->status) == '0' || trim(@$data[0]->status) == '2' || trim(@$data[0]->status) == '4')){ 
														if(trim(@$data[0]->admin_status) =="0"){
															
														
													?>
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
															<div class="col-md-8 portlet-body p-6 form-group">
																<label class="control-label" style="color: #697882;"><strong></strong></label>
																<tr id="row"><td><input type="file" id="fileuplaod<?php  echo $question->id; ?>"   onchange="return fileValidation(<?php  echo $question->id; ?>)" name="docs[<?php  echo $question->id; ?>][]" mul1tiple="multiple" class="form-control name_list" /></td><td></td></tr>
															</div>
															
															<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center" style="padding: 21px 10px 10px 10px;">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center p-4">
																	<button type="button" onClick="AddMore(<?php  echo $question->id; ?>)" class="btn blue" style="padding: 8px 39px;">Add More</button>
																</div>
															</div>
															
														</div>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dynamic_field<?php  echo $question->id; ?>" style="padding-left: 40px;"></div>
													<?php }	} ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 p-20">
									<div class="md-checkbox has-success">
										<input type="checkbox" name="checked[<?php  echo $question->id; ?>][]"  <?php //if(!empty(@$data[0]->quest_checked_val)){ echo "checked";}else{ echo "";} ?> id="checkbox<?php echo $question->id;?>" class="md-check">
										<label for="checkbox<?php echo $question->id;?>">
											<span></span>
											<span class="check check_custm"></span>
											<span class="box"></span>
										</label>
									</div>
								</div>
								
							</div>
						   <?php $i++; } ?>
						   <?php } ?>
						</div>
						<!--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
							    <button type="button" class="btn blue" onclick="window.history.back()" style="padding: 8px 39px;">Back</button>
							</div>-->
						<?php if(trim(@$status) != "Approved"){ ?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 p-10 text-center">
								<button type="submit" class="btn blue submit_evicence" style="padding: 8px 39px;">Submit</button>
							</div>
						</div>
						 <?php } ?>
						<!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 p-10 text-center">
								<button type="submit" class="btn blue submit_evicence" style="padding: 8px 39px;">Submit</button>
							</div>
						</div>-->
						</form>
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
 // add more file in upload evidence
	function AddMore(id){
		// event.preventDefault();
          var i = $('.dynamic_field'+id+' tr').length;
		  var k=i+1;;
		    var j=id+k;
		   $('.dynamic_field'+id+'').append('<tr id="row'+i+'"><td style="padding: 10px 5px 5px 0px;"><input type="file" style="width:312px;" id="fileuplaod'+j+'" onchange="return fileValidation('+j+')" name="docs['+id+'][]" mult1iple="multiple" class="form-control name_list" /></td><td style="padding: 10px 5px 5px 0px;vertical-align:top;"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
	}	  
     
	$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      }); 
	// end  more file in upload evidence
	  
	// delete file from  upload_evidence_docs  
	  function Delete_dcos(docs_id){
		  $.ajax({
			  type:"POST",
			  url:"<?php echo base_url() ?>/customer/process/delete_process_docs",
			  data:{docs_id:docs_id},
			  success:function(data){
				  //alert(data);
				 location.reload();
			  }
			});
		}
	  
	  // START SELECT ALL CHECK BOXES
	  $(document).ready(function () {
			$("#ckbCheckAll").click(function () {
				$(".checkBoxClass").prop('checked', $(this).prop('checked'));
			});
			$(".checkBoxClass").change(function(){
				if (!$(this).prop("checked")){
					$("#ckbCheckAll").prop("checked",false);
				}
			});
		});
	  // END SELECT ALL CHECK BOXES
	  
	  //start upload  file validation 
		function fileValidation(id){
			var fileInput = document.getElementById('fileuplaod'+id);
			var filePath = fileInput.value;
			var allowedExtensions = /(\.png|\.jpeg|\.jpg|\.gif|\.pdf|\.docx|\.doc|\.txt)$/i;
			if(!allowedExtensions.exec(filePath)){
				alert('Please upload file having extensions .jpeg/.jpg/.png/.gif/.pdf/.doc/.docx/.txt only.');
				fileInput.value = '';
				return false;
			}else{
				//alert('ttttttt');
			}
			
		}
	
//end upload  file validation 
	  $(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		}); 

	  
 </script>