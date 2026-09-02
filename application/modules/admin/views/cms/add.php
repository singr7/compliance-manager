  <!-- BEGIN CONTENT -->
  
 
			<div class="row">
            <div class="col-md-12" style="padding: 15px 0px; width:95%; margin:0px 10px;background: #fff; padding:0px 0px 20px 0px;">
					<div class="portlet box blue">
					 <div class="portlet-title black">
                                    <div class="caption">
                                        <?=lang('add_heading'); ?> </div>
                                    <!--<div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>--->
                                </div>
							<?php echo get_flashdata(); ?>
						<div class="portlet-body">
						
								<div class="form-body">	
									<form action="<?php echo base_url();?>admin/unit/add" method="post" id="unit_add" >								
										<div class="row">
										<div class="form-group col-md-6">
											<label><?=lang('unit_name'); ?> <span class="required" aria-required="true">* </span></label>
												<div class="">
													<?php 													
													$postvalue = @$_POST['name'];													
													?>
												
													<input type="text" name="name" value="<?php echo (!empty($postvalue) ? $postvalue : " "); ?>" class="form-control"  maxlength="35">													
												</div>
												<span class="help-block"><?php echo form_error('name'); ?></span>
										</div>
										
										<div class="form-group col-md-6">
												<label><?=lang('status'); ?><span class="required" aria-required="true">
											* </span></label>
													<div class="select-style">
														<select name="status" id="statis-id">
															<option value="active" <?= (@$_POST['status'] == 'active') ? 'selected' : '' ?>><?=lang('active'); ?></option>
															<option value="inactive"  <?= (@$_POST['status'] == 'inactive') ? 'selected' : '' ?> ><?=lang('inactive'); ?></option>														
														</select>
														
													</div>
													<span class="help-block"><?php echo form_error('status'); ?></span>
											</div>
										</div>
										
										<div class="text-center margin_top_btm_20 col-md-12" > <!--style="float: left;padding-left:10px; width:100%;"--> 
											<button type="submit" class="btn blue"><?=lang('submit'); ?></button>
											<button type="button" class="btn default cancel-button" ><?=lang('cancel'); ?></button>
										</div>  					
									</form>	
								</div>
							
						</div>
					</div>	
					
			</div>
                  
		</div>
   
			<!-- END PAGE CONTENT-->
		
<script>   
	$('.cancel-button').on('click', function() {
		url = '<?php echo base_url();?>admin/country';	
		location = url;
	});
         
   $.validator.addMethod("leters_space",function(value,element){
	if(value=='' || value==null)
	{
		return true;
	}
	return  /^[A-Za-z]+( [A-Za-z]+)*$/.test(value);
	},'')

  $("#cuisine_category_add").validate({
	rules: {
		name:{
			required:true,
		},            
		'status': {
			required: true        
		},            
	},       	              
	messages: {
		name: {
			required: '<span class="help-block"><?=lang('category'); ?></span>',
		},           
		status: {
			required:'<span class="help-block"><?=lang('statis_requerid'); ?></span>'
		},          
	}
});
</script>	
	