  <!-- BEGIN CONTENT -->
   <?php echo get_flashdata(); ?>
			<div class="row">
            <div class="col-md-12" style="padding: 15px 0px; width:95%; margin:0px 10px;background: #fff; padding:0px 0px 20px 0px;">
					<div class="portlet box blue">
					 <div class="portlet-title black">
                                    <div class="caption">
                                        View Content </div>
                                    <!--<div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>--->
                                </div>
							
						<div class="portlet-body">
						
								<div class="form-body">	
									
										<div class="row">
										<div class="form-group col-md-6">
											<label> Page Name<span class="required" aria-required="true">
										* </span></label>
												<div class="">
													 <?php
                                                    $name = @$result->name;
                                                    $postvalue = @$_POST['name'];												
													?>
												
													<input type="text" readonly name="name" value="<?php echo (!empty($postvalue) ? $postvalue : $name); ?>" class="form-control">													
												</div>
												<span class="help-block"><?php echo form_error('name'); ?></span>
										</div>
										<div class="form-group col-md-6">
											<label>Page Title <span class="required" aria-required="true">
										* </span></label>
												<div class="">
													 <?php
                                                    $title = @$result->title;
                                                    $postvalue = @$_POST['title'];												
													?>
												
													<input type="text" name="title" value="<?php echo (!empty($postvalue) ? $postvalue : $title); ?>" class="form-control" readonly>													
												</div>
												<span class="help-block"><?php echo form_error('title'); ?></span>
										</div>
										
											<!--<div class="form-group col-md-6">
												<label>Status <span class="required" aria-required="true">
											* </span></label>
													<div class="select-style">
														<select name="status" id="statis-id">
															<option value="active" <?= (@$result->status == 'active') ? 'selected' : '' ?>>Active</option>
															<option value="inactive"  <?= (@$result->status == 'inactive') ? 'selected' : '' ?> >Inactive</option>														
														</select>
														
													</div>
													<span class="help-block"><?php //echo form_error('status'); ?></span>
											</div>-->
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label> Content<span class="required" aria-required="true">
											* </span></label>
													<div class="">
														 <?php
														$content = @$result->content;
														$postvalue = @$_POST['content'];												
														?>
													
														<textarea name="content" value="" class="form-control ckeditor" readonly><?php echo (!empty($postvalue) ? $postvalue : $content); ?></textarea>
													</div>
													<span class="help-block"><?php echo form_error('content'); ?></span>
											</div>
                                                                                    <div class="text-center margin_top_btm_20 col-md-12" > <!--style="float: left;padding-left:10px; width:100%;"--> 
											<a href="javascript:history.back()" class="btn blue">Back</a>
											
										</div>
										</div>
										 					
									
								</div>
							
						</div>
					</div>	
					
			</div>
                  
		</div>
   
			<!-- END PAGE CONTENT-->
	
	
	