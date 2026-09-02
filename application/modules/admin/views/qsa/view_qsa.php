<style>
    .list-group{
        z-index:10;display:none; 
        position:absolute; 
        color:red;
        width: 100%;
        box-shadow: 0px 6px 22px #4f606a30;
    }
    .list-group .list-group-item{border:0px;}
    .msg{
        position:absolute; 
        color:red;
    }

</style>
<div class="row">
                        <div class="col-md-8 ">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <?php echo get_flashdata(); ?>
                                <div class="portlet-body form">
                                   
                                        <div class="form-body">
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="form_control_1" name="full_name" value="<?php if(!empty($qsa_detail->full_name)){ echo ucwords($qsa_detail->full_name);}?>" placeholder="Enter name" readonly="true">
                                                <label for="form_control_1">Name</label>
                                                <label id="form_control_1-error" class="error" for="form_control_1" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="email" name="email" value="<?php if(!empty($qsa_detail->email)){ echo $qsa_detail->email;}?>" placeholder="Enter email" readonly="true">
                                                <label for="form_control_1">Email Id</label>
                                                <label id="email-error" class="error" for="email" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="phonenumber" value="<?php if(!empty($qsa_detail->phone_number)){ echo $qsa_detail->phone_number;}?>" name="phone_number" minlength="7" maxlength="15" placeholder="Enter  phone number" readonly="true">
                                                <label for="phonenumber">Phone Number</label>
                                                <label id="phonenumber-error" class="error" for="phonenumber" style="margin-top: 56px; color:#c8202d"></label>
                                                 
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control  remove_first_space" id="companyname" name="company_name" value="<?php if(!empty($qsa_detail->company_name)){ echo $qsa_detail->company_name;}?>" placeholder="Enter  company name" readonly="true">
                                                <label for="companyname">Company Name</label>
                                                <label id="mobilenum-error" class="error" for="companyname" style="margin-top: 56px; color:#c8202d"></label>
                                                 <div id="mobilenum1" style="color:#c8202d;"></div>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="companynumber" value="<?php if(!empty($qsa_detail->company_number)){ echo $qsa_detail->company_number;}?>" name="company_number" minlength="7" maxlength="15" placeholder="Enter  company contact number" readonly="true">
                                                <label for="companynumber">Company Contact Number</label>
                                                 <label id="companynumber-error" class="error" for="companynumber" style="margin-top: 56px; color:#c8202d"></label>
                                                </div>
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <textarea class="form-control remove_first_space" id="address" name="address"   placeholder="Enter  address" readonly="true"><?php if(!empty($qsa_detail->address)){ echo $qsa_detail->address;}?></textarea>
                                                <label for="address">Address</label>
                                                <label id="mobilenum-error" class="error" for="address" style="margin-top: 72px; color:#c8202d"></label>
                                                 <div id="mobilenum1" style="color:#c8202d;"></div>
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <input type="text" class="form-control remove_first_space" id="form_control_1" name="full_name" value="<?php if(!empty($qsa_detail->status)){ echo ucwords($qsa_detail->status);}?>" placeholder="Enter name" readonly="true">
                                                <label for="form_control_1">Select status</label>
                                                <label id="form_control_9-error" class="error" for="form_control_9" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            <div class="form-actions noborder text-center">
                                            
                                            <button type="button" class="btn default cancel-button">Back</button>
                                        </div>
                                              
                                        </div>
                                        
                                    
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
<script>
    $('.cancel-button').on('click', function() {
                url = '<?php echo base_url(); ?>admin/qsa';	
                location = url;
        });
        </script>
