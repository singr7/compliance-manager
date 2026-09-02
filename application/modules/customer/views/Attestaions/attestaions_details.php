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
.select-year{
    border: 1px solid #ccc;
    background-color: #ccc;
    text-align: center;
    color: #1f1f1f;
	margin-top: 20px;
   
}
.select-year p{
	font-size: 16px;
    font-weight: initial;
}
.year p{
    width: 50%;
    border: 1px solid #ccc;
    padding: 10px 10px;
    text-align: center;
    background-color: #ccc;
}
.row{
	background-color: #fff;
}
.Attestations{
	border-bottom: 1px solid#ccc;
	
}
.caption h3{
    font-weight: 500 !important;
    font-size: 22px;
}

.year{
	margin: 14px 0px 0px 0px;
}
.year > a{
	background-color: #ccc;
    color: #1f1f1f;
    padding: 10px 47px;
}
.fa_icon{
	font-size:25px;
}
.mt-10{
	margin-top:10px;
}
.years{
	margin: 14px 0px 0px 0px;
}
.years p{
    width: 88%;
    border: 1px solid #ccc;
    padding: 10px 10px;
    text-align: center;
    background-color: #ccc;
	margin-left: 28px;
}

</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="Attestations">
            <div class="caption">
                    <h3 class="caption-subject bold uppercase"> Attestations & Certifications</h3>
                </div>
                
            </div>
            
        </div>
		<div class="col-md-12 portlet-title">
                <div class="col-md-3 select-year">
				    <p class="years"><?php echo $service_name->service_name; ?></p>
                    
                </div>
                
            </div>
			<?php  
 
			if(!empty($roc)){
					$extension=explode('.',$roc->report_docs);
					if(!empty(@$extension[1])){
					 if(trim($extension[1]) =="pdf"){
						 $_blank="target='download'";
						$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
					 }
					}
					?>
					<div class="col-md-12 portlet-title">
						<div class="col-md-3 mt-10 text-center">
							<div class="col-md-12 mt-10">
								<div class="col-md-12">
									<label class="control-label" style="color: #697882;">
										<strong>
											<a  href='<?php echo base_url()?>uploads/report/<?php echo  @$roc->report_docs;  ?>' download data-toggle="tooltip" title="<?php echo @$roc->report_docs;  ?>"><?php echo $file; ?></a>
											<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_aoc_roc(<?php echo @ID_encode($roc->id).",".@ID_encode($roc->service_id).",".@ID_encode($roc->process_id).",".@ID_encode($roc->year).",".@ID_encode($aoc->customer_id); ?>);'><span class='glyphicon glyphicon-trash'></span></button>
											<p class="mb-0 mt-5" style="color: #697882;"><?php  echo $roc->updated_date	; ?></p>
										</strong>
									</label>
								</div>
						   </div>
						</div>
						<div class="col-md-3 years">
						
						</div>
                
					</div>
						
				<?php 	}elseif(!empty($roc && empty($rot) && empty($aot))){ ?>
				<div class="col-md-12 years">
					<div class="col-md-3 years">
						<p class="year">ROC: No Document Uploaded</p>
					</div>
				</div>
					
				<?php }	?>
				<?php   if(!empty($aoc)){
					$extension=explode('.',$aoc->report_docs);
					if(!empty(@$extension[1])){
					 if(trim($extension[1]) =="pdf"){
						 $_blank="target='download'";
						$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
					 }
					}
					?>
					<div class="col-md-12 portlet-title">
						<div class="col-md-3 mt-10 text-center">
							<div class="col-md-12 mt-10">
								<div class="col-md-12">
									<label class="control-label" style="color: #697882;">
										<strong>
											<a  href='<?php echo base_url()?>uploads/report/<?php echo  @$aoc->report_docs;  ?>' download data-toggle="tooltip" title="<?php echo @$aoc->report_docs;  ?>"><?php echo $file; ?></a>
											<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_aoc_roc(<?php echo @ID_encode($aoc->id).",".@ID_encode($aoc->service_id).",".@ID_encode($aoc->process_id).",".@ID_encode($aoc->year).",".@ID_encode($aoc->customer_id); ?>);'><span class='glyphicon glyphicon-trash'></span></button>
											<p class="mb-0 mt-5" style="color: #697882;"><?php  echo $aoc->updated_date	; ?></p>
										</strong>
									</label>
								</div>
						   </div>
						</div>
						<div class="col-md-3 year">
						
						</div>
                
					</div>
						
				<?php 	}elseif(!empty($aoc && empty($rot) && empty($aot))){ ?>
				<div class="col-md-12 years">
					<div class="col-md-3 years">
						<p class="year">AOC: No Document Uploaded</p>
					</div>
				</div>
					
				<?php }	?>
				<?php 
				if(!empty($rot)){
					$extension=explode('.',$rot->report_docs);
					if(!empty(@$extension[1])){
					 if(trim($extension[1]) =="pdf"){
						 $_blank="target='download'";
						$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
					 }
					}
					?>
					<div class="col-md-12 portlet-title">
						<div class="col-md-3 mt-10 text-center">
							<div class="col-md-12 mt-10">
								<div class="col-md-12">
									<label class="control-label" style="color: #697882;">
										<strong>
											<a  href='<?php echo base_url()?>uploads/report/<?php echo  @$rot->report_docs;  ?>' download data-toggle="tooltip" title="<?php echo @$rot->report_docs;  ?>"><?php echo $file; ?></a>
											<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_aoc_roc(<?php echo @ID_encode($rot->id).",".@ID_encode($rot->service_id).",".@ID_encode($rot->process_id).",".@ID_encode($rot->year).",".@ID_encode($rot->customer_id); ?>);'><span class='glyphicon glyphicon-trash'></span></button>
											<p class="mb-0 mt-5" style="color: #697882;"><?php  echo $rot->updated_date	; ?></p>
										</strong>
									</label>
								</div>
						   </div>
						</div>
						<div class="col-md-3 year">
						
						</div>
                
					</div>
						
				<?php 	}elseif(!empty($rot && empty($roc) && empty($aoc))){ ?>
				<div class="col-md-12 years">
					<div class="col-md-3 years">
						<p class="year">ROT: No Document Uploaded</p>
					</div>
				</div>
					
				<?php }?>
				
				<?php   if(!empty($aot)){
					$extension=explode('.',$aot->report_docs);
					if(!empty(@$extension[1])){
					 if(trim($extension[1]) =="pdf"){
						 $_blank="target='download'";
						$file="<i class='fa fa-file-pdf-o fa_icon'  aria-hidden='true'></i>";
					 }
					}
					?>
					<div class="col-md-12 portlet-title">
						<div class="col-md-3 mt-10 text-center">
							<div class="col-md-12 mt-10">
								<div class="col-md-12">
									<label class="control-label" style="color: #697882;">
										<strong>
											<a  href='<?php echo base_url()?>uploads/report/<?php echo  @$aot->report_docs;  ?>' download data-toggle="tooltip" title="<?php echo @$aot->report_docs;  ?>"><?php echo $file; ?></a>
											<button type='button' class='delete_btn' style='margin-right:10px;font-size:10px;' onClick='Delete_aoc_roc(<?php echo @ID_encode($aot->id).",".@ID_encode($aot->service_id).",".@ID_encode($aot->process_id).",".@ID_encode($aot->year).",".@ID_encode($aot->customer_id); ?>);'><span class='glyphicon glyphicon-trash'></span></button>
											<p class="mb-0 mt-5" style="color: #697882;"><?php  echo $aot->updated_date	; ?></p>
										</strong>
									</label>
								</div>
						   </div>
						</div>
						<div class="col-md-3 year">
						
						</div>
                
					</div>
						
				<?php }elseif(!empty($aot && empty($roc) && empty($aoc))){ ?>
				<div class="col-md-12 years">
					<div class="col-md-3 years">
						<p class="year">AOT: No Document Uploaded</p>
					</div>
				</div>
					
				<?php }	?>
			
			<div class="col-md-12 portlet-title">
                <div class="col-md-3 year">
				    
                    
                </div>
                
            </div>
			<div class="col-md-12 portlet-title">
                <div class="col-md-3 year">
				  
                    
                </div>
                
            </div>
			
				<!--<button type="submit" class="btn green cancel-button" style="padding: 8px 40px;">Back</button>-->
		
        <!-- END EXAMPLE TABLE PORTLET-->
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						    <button type="button" class="btn blue cancel-button" style="padding: 8px 39px;">Back</button>
						</div>
    </div>
	
</div>








<!-- END PAGE CONTENT-->
</div>
</div>	
<!-- Button trigger modal -->
<script>
// start delete file from  ROC AOC  
	  function Delete_aoc_roc(docs_id,service_id,process_id,year,customer_id){
		    var confirmation = confirm("Do you want to delete?");
		   if(confirmation){
		  $.ajax({
			  type:"POST",
			  url:"<?php echo base_url() ?>customer/attestaions/Delete_aoc_roc",
			  data:{docs_id:docs_id,service_id:service_id,process_id:process_id,year:year,customer_id:customer_id},
			  success:function(data){
				//  $('.caption').html(data);
				 location.reload();
			  }
			});
		   }
		}
		// end delete file from  ROC AOC		


    $('.cancel-button').on('click', function () {
        url = '<?php echo base_url(); ?>customer/attestaions/attestaions_report?year=<?php echo $_GET['year']; ?>';
        location = url;
    });
   
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
}); 

</script>