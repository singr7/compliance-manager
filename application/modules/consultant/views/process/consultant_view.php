<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);
?>				
<?php echo get_flashdata(); ?>

<style>
.p-5{
	padding:0px;
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
    font-weight: 600;
    border: none;
	text-align: center;
}
.questionnaire_heading label{
	font-weight: 600 !important;
    border-bottom: 1px solid #5aa3e1ad;
	
}
.mb-0{
	margin-bottom:0px;
}
.questionnaire_heading label{
	color: #5c9bd1;
    text-transform: uppercase;
    font-size: 22px;
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
$status=Admin_status_change(ID_decode($_GET['service_id']),$process_id->id,$customer_id->id); ?>
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
					<span class="caption-subject"><?php echo ucfirst($userInfo->full_name); ?></span>|
                    <span class="caption-subject"><?php echo ucfirst($process_id->process_name); ?></span>|
                    <span class="caption-subject"><?php echo ucfirst($service_id->service_name); ?></span>
                     <span class="status1" style="margin-left: 309px;font-size: 14px;"> Status: <?php if(isset($status) && !empty($status)){
							if(trim($status)=="Approved"){ echo "Completed";}else{ echo "In Progress"; } } ?> </span>
					<?php if(isset($start_date[0]->end_date) && !empty($start_date[0]->end_date)){ ?>
								<span class="status" style="font-size: 14px;margin-left: 174px;" > End Date:  <?php echo $start_date[0]->end_date; ?></span>
					<?php } ?>
					<?php if(isset($testing_start_date[0]->end_date) && !empty($testing_start_date[0]->end_date)){ ?>
								<span class="status" style="font-size: 14px;margin-left: 174px;" > End Date:  <?php echo $testing_start_date[0]->end_date; ?></span>
					<?php } ?>
                    </div>
            </div>
			<form action="<?php echo base_url(); ?>consultant/multiple_upload_consultant" method="POST" enctype="multipart/form-data">
		    <div class="row p-10">
			   <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
									<div class="col-md-12 portlet-body p-10 questionnaire_heading">
										<label class="control-label">Questionnaire</label>
									</div>
									
								</div>
							</div>
			 <?php if(isset($get_questionair) && !empty($get_questionair)){ ?>
				   <?php $i=1; foreach($get_questionair as $question){ 
				    $data=common_function($question->id,$question->service_id,$process_id->id,$customer_id->id);
					$docsData=common_function_for_docs($question->id,$question->service_id,$process_id->id,$customer_id->id);
				   ?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 p-10">
						<div class="portlet box red basic">
							<div class="portlet-title">
								<div class="caption">
									Question <?php echo $i;?> : <?php echo $question->question;?>
									<input type="hidden" name="question_id[<?php  echo $question->id; ?>][]" value="<?php  echo $question->id; ?>">
									<input type="hidden" name="servide_id" value="<?php echo $question->service_id ?>">
									<input type="hidden" name="process_id" value="<?php echo $process_id->id ?>">
									
								</div>
								<div class="tools">
								<a href="javascript:;" class="<?php if(isset($data[0]->questionnaire_id) && !empty($data[0]->questionnaire_id)){ echo "collapse";}else{ echo "expand";};?>" data-original-title="" title=""> </a>
	
								</div>
									</div>
						<div class="portlet-body tabs-below" style="display: <?php if(isset($data[0]->questionnaire_id) && !empty($data[0]->questionnaire_id)){ echo "block";}else{ echo "none";};?>; width: 100%; float: left; clear: both;">
							<div class="tab-content" style="width: 100%;float: left;clear: both; ">
								<div class="tab-pane active" id="tab_4_1" style="width: 100%; float: left; clear: both;">
								<?php if(empty($data)){ echo "<p style='color: #697882;text-align:center;font-size:19px;'>No Document Uploaded.</p>"; }; ?>
								
								 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
									<?php 
									if(isset($docsData) && !empty($docsData)){
										foreach($docsData as $docsDatas){ 
									$file="";
									$_blank="";
									$extension=explode('.',$docsDatas->docs);
									if(!empty($extension[0])){
									 if(trim($extension[1]) =="jpeg"){ 
										$file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="jpg"){
										 $file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="gif"){
										 $file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="png"){
										$file="<i class='fa fa-file-image-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="pdf"){
										$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="doc"){
										 $file="<i class='fa fa-file-text-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="docx"){
										  $file="<i class='fa fa-file-text-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="docx"){
										  $file="<i class='fa fa-file-text-o fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="txt"){
										  $file="<i class='fa fa-file-text fa_icon' aria-hidden='true'></i>";
									 }elseif(trim($extension[1]) =="txt"){
										 $_blank="target='download'";
										 $file="<i class='fa fa-file-text fa_icon' aria-hidden='true'></i>";
										 }
									}
							
								?>
								<div class="col-md-4 portlet-body form-group mb-0" style="margin-bottom:0px;">
								<label class="control-label" style="color: #697882;">
								<strong>
									<a  href='<?php echo base_url()?>uploads/evidence/<?php echo $docsDatas->docs  ?>' data-toggle="tooltip" title="<?php echo $docsDatas->docs;  ?>" download><?php echo $file; ?></a>
									</strong></label>
									<p class="mb-0" style="color: #697882;margin: 10px 0;"><?php echo date("d-m-Y H:i", strtotime($docsDatas->updated_date)); ?></p>
								</div>
								
									<?php  } }  ?>
									</div>
								</div>
									<?php
								$cusData=customer_comments(@$question->id,@$question->service_id,$process_id->id,$customer_id->id);
									if(isset($cusData) && !empty($cusData)){ 
										foreach($cusData as $cusDatas){
											$customerName=user_name(@$cusDatas->login_user_id);
												$CustomerFirestName=explode(' ',$customerName->full_name); ?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
											<div class="col-md-4 portlet-body">
												<label class="control-label" style="color: #697882;"><strong><?php echo $CustomerFirestName[0]; ?></strong></label>
											</div>
											<div class="col-md-8 portlet-body">
												<label class="control-label" style="color: #697882;"><?php echo $cusDatas->comments."&nbsp;(".date("d-m-Y H:i", strtotime($cusDatas->updated_date)).")"; ?></label>
											</div>
											
										</div>
									</div>
							<?php } }?>
								 <?php if((trim(@$data[0]->status) == '0' || trim(@$data[0]->status) == '2' || trim(@$data[0]->status) == '4' ||  trim(@$data[0]->qa_status) == '2' || trim(@$data[0]->qa_status) == '4')){	$Name=explode(' ',$userInfo->full_name);
											if(trim(@$data[0]->admin_status) =="0"){
											$Name=explode(' ',$userInfo->full_name); ?>
									
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
												<div class="col-md-4 portlet-body">
													<label class="control-label" style="color: #697882;"><strong> <?php echo $Name[0]; ?> : </strong></label>
												</div>
												<div class="col-md-8 portlet-body">
													<textarea class="form-control" placeholder="comments" id="comments<?php echo $question->id; ?>" name='comments[<?php echo $question->id;?>][]' rows="3" style="background-color: #f3f5f9;"></textarea>
												</div>
												
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-5">
												<div class="col-md-4 portlet-body">
												</div>
												<div class="col-md-8 portlet-body">
												<input type="button" value="Reply"  class="btn blue" style="padding: 8px 18px;margin-right:10px;" onClick="ConsultantComment(<?php echo $question->id.",".ID_encode($question->service_id).",".ID_encode($process_id->id);?>)">
											</div>
											</div>
										</div>
								 <?php  }  }?>
									<?php 
										if(!empty(@$data)){ ?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-10">
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
												<?php 
												 if(trim(@$data[0]->status) == "0" || trim(@$data[0]->status) == "2" || trim(@$data[0]->status) == "3" || trim(@$data[0]->status) == "4" ){ 
													if(trim(@$data[0]->admin_status) =="0"){
												 ?>
												<div class="col-md-12 portlet-body">
													<input type="button" value="Accept & Assign to QA"  class="btn blue" style="    margin-bottom: 11px;"onClick="status(<?php echo ID_encode(1);?>,<?php echo ID_encode($question->id);?>,<?php echo ID_encode($question->service_id);?>,<?php echo ID_encode($process_id->id);?>)">
													<input type="button"   value="Disapproved"  class="btn red" style="margin-bottom: 11px;"  onClick="status(<?php echo ID_encode(2);?>,<?php echo ID_encode($question->id);?>,<?php echo ID_encode($question->service_id);?>,<?php echo ID_encode($process_id->id);?>)">
													<input type="button"  value="Mark Incomplete"  class="btn yellow" style="margin-bottom: 11px;" onClick="status(<?php echo ID_encode(4);?>,<?php echo ID_encode($question->id);?>,<?php echo ID_encode($question->service_id);?>,<?php echo ID_encode($process_id->id);?>)">
												</div>
												 <?php  } } ?>
										</div>
									</div>
									
								<?php } ?>
									
							 <?php if((trim(@$data[0]->status) == '0' || trim(@$data[0]->status) == '2' || trim(@$data[0]->status) == '4' ||  trim(@$data[0]->qa_status) == '2' || trim(@$data[0]->qa_status) == '4')){ 
									if(trim(@$data[0]->admin_status) =="0"){
							 ?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="col-md-8 portlet-body p-6 form-group">
											<label class="control-label" style="color: #697882;"><strong></strong></label>
											<tr id="row"><td><input type="file" id="fileuplaod<?php  echo $question->id; ?>"   onchange="return fileValidation(<?php  echo $question->id; ?>)" name="docs[<?php  echo $question->id; ?>][]" mult1iple="multiple" class="form-control name_list" /></td><td></td></tr>
											<!--<input type="file" id="exampleInputFile1">-->
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center" style="padding: 13px 10px 10px 0px;margin-top: 12px;">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
												<button type="button" onClick="AddMore(<?php  echo $question->id; ?>)" class="btn blue" style="padding: 8px 39px;">Add More</button>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dynamic_field<?php  echo $question->id; ?>">
										</div>
									</div>
								</div>
							 <?php } } ?>
												</div>
												
											</div>
										</div>
									</div>
								</div>
								<?php
								if(trim(@$data[0]->status)=="0"){ 
									if(trim(@$data[0]->admin_status) =="0"){
								?>
									<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 p-20">
										<div class="md-checkbox has-success">
											<input type="checkbox" name="checked[<?php  echo $question->id; ?>][]"  id="checkbox<?php echo $question->id;?>" class="md-check">
											<label for="checkbox<?php echo $question->id;?>">
												<span></span>
												<span class="check check_custm"></span>
												<span class="box"></span>
											</label>
										</div>
									</div>
								<?php	} } ?>
							</div>
						   <?php $i++; } ?>
						   <?php } ?>
							
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
							    <button type="button" class="btn blue cancel-button" style="padding: 8px 39px;">Back</button>
							</div>
							<?php if(trim(@$status) != "Approved"){ ?>
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 p-10 text-center">
								<button type="submit" class="btn blue submit_evicence" style="padding: 8px 39px;">Submit</button>
							</div>
						</div>
							<?php } ?>
						</form>                              
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

<script>
// start chnage the status
	function status(approveid,question_id,service_id,process_id){
		$.ajax({
			type:"POST",
			url:"<?php echo base_url();?>/consultant/status_change_by_consultant",
			data:{approveid:approveid,question_id:question_id,service_id:service_id,process_id:process_id},
			success:function(success){
				location.reload();
			}
		});
		
	}
	// end chnage the status
// start reply to comment to customer
	function ConsultantComment(question_id,service_id,process_id){
		var comment=$("#comments"+question_id).val();
		if(comment==""){
			alert('Please comment is required.');
			return false;
		}else{
			$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>consultant/Comment_by_consualtant",
				data:{question_id:question_id,service_id:service_id,process_id:process_id,comment:comment},
				success:function(success){
					//alert(success);
					location.reload();
				}
			});
		}
	}
// end reply to comment to customer	

	// start delete file from  upload_evidence_docs  
	  function Delete_uplaod_by_consultant(docs_id,question_id,service_id,process_id){
		  $.ajax({
			  type:"POST",
			  url:"<?php echo base_url() ?>consultant/Delete_uplaod_by_consultant",
			  data:{docs_id:docs_id,question_id:question_id,service_id:service_id,process_id:process_id},
			  success:function(data){
				 location.reload();
			  }
			});
		}
		// end delete file from  upload_evidence_docs  
			// start delete file from  upload_evidence_docs  
			/*
	  function Delete_uplaod_by_consultant(docs_id,question_id,service_id,process_id){
		  $.ajax({
			  type:"POST",
			  url:"<?php echo base_url() ?>Consultant/Delete_uplaod_by_consultant",
			  data:{docs_id:docs_id,question_id:question_id,service_id:service_id,process_id:process_id},
			  success:function(data){
				 location.reload();
			  }
			});
		}
		*/
		// end delete file from  upload_evidence_docs  
		
		// start request for modification for uplaoding document in customer view evidende
function RequestModificationForAdmin(id,question_id,service_id,process_id){
		$.ajax({
				type:"POST",
				url:"<?php echo base_url();?>consultant/RequestModificationForAdmin",
				data:{request_id:id,question_id:question_id,service_id:service_id,process_id:process_id},
				success:function(request){
				   location.reload();
				}
			});
	
	}
// end request for modification for uplaoding document in customer view evidende

// add more file in upload evidence
	function AddMore(id){
		// event.preventDefault();
          var i = $('.dynamic_field'+id+' tr').length;
		    var k=i+1;;
		    var j=id+k;
		   $('.dynamic_field'+id+'').append('<tr id="row'+i+'"><td style="padding: 5px 5px 5px 0px;"><input type="file" style="width:290px;" id="fileuplaod'+j+'" onchange="return fileValidation('+j+')" name="docs['+id+'][]" mult1iple="multiple" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
	}	  
     
	$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      }); 
	// end  more file in upload evidence
	
		
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
</script>
<script>
    $('.cancel-button').on('click', function () {
        url = '<?php echo base_url(); ?>consultant/dashboard';
        location = url;
    });
</script>