<?php
$userInfo = $this->session->userdata('userinfo');

if ($userInfo->user_type == 3) {
    $usertype = "Supplier";
} else if ($userInfo->user_type == 4) {
    $usertype = "Sub-Contractor";
}
?>
<?php echo get_flashdata(); ?>


<style>
  #popup_container {
    position: fixed;
    top: calc(50% - 56px);
    z-index: 99999;
    left: calc(50% - 150px);
}
 .mbl_select{
        width: 30%;
        display: inline-block;
    }
    .mbl_input{
        width: 65%;
        display: inline-block;
        float: right;
    }
    .error{
        color:#c8202d;
    }
</style>
<div class="row">
    <div class="col-md-12 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered grid">

            <div class="portlet-body form">
                <form role="form" action="" method="POST" id="customer" enctype="multipart/form-data" >
                    <div class="form-body">
                        <div class="col-md-6 ">
                        
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control remove_first_space" id="fullname" name="full_name" value="<?php echo $customer_details->full_name;?>" placeholder="Enter Name">
                            <label for="fullname">Namee&nbsp;<span style='color:red;'>*</span></label>
                            <label id="fullname-error" class="error" for="fullname" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control remove_first_space" id="email" name="email" value="<?php echo $customer_details->email;?>" placeholder="Enter Email Id">
                            <label for="email">Email Id&nbsp;<span style='color:red;'>*</span></label>
                            <label id="email-error" class="error" for="email" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control remove_first_space" id="phoneno" name="phone_number" value="<?php echo $customer_details->phone_number;?>" placeholder="Enter phone number">
                            <label for="form_control_1">Phone no.&nbsp;<span style='color:red;'>*</span></label>
                            <label id="phoneno-error" class="error" for="phoneno" style="margin-top: 56px; color:#c8202d"><?php echo form_error('payee_name'); ?></label>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control remove_first_space" id="companyname" name="company_name"  value="<?php echo $customer_details->company_name;?>" placeholder="Enter company name">
                            <label for="companyname">Company Name&nbsp;<span style='color:red;'>*</span></label>
                            <label id="companyname-error" class="error" for="companyname" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                          <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control remove_first_space" id="form_control_4" name="company_number" value="<?php echo $customer_details->company_number;?>" placeholder="Enter company contact number">
                            <label for="form_control_1">Company Conatct no.&nbsp;<span style='color:red;'>*</span></label>
                            <label id="form_control_3-error" class="error" for="form_control_4" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                            <div class="form-group form-md-line-input has-success">
                               <textarea class="form-control remove_first_space" id="address" name="address" placeholder="Enter address"><?php echo $customer_details->address;?></textarea>
                            <label for="address">Address&nbsp;<span style='color:red;'>*</span></label>
                            <label id="address-error" class="error" for="address" style="margin-top: 72px; color:#c8202d"></label>
                        </div>
                            
                           <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="form_control_9" name="status">
                                                    <option value="">Please select status</option>
                                                    <option value="active" <?php if($customer_details->status =='active'){ echo "Selected";};?>>Active</option>
                                                    <option value="inactive" <?php if($customer_details->status =='inactive'){ echo "Selected";};?>>Inactive</option>
                                                  </select>
                                                <label for="form_control_1">Select status &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_9-error" class="error" for="form_control_9" style="margin-top: 56px; color:#c8202d"></label>
                                            </div> 
                            
                        </div>
                        
                        <div class="col-md-6 ">
                          
                          
                        
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control" id="unquieid" name="unique_id" value="<?php echo $customer_details->unique_id;?>" placeholder="Enter unqiue id" readonly="true">
                            <label for="unquieid">Unique Id<span style='color:red;'>*</span></label>
                            <label id="unquieid-error" class="error" for="unquieid" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                            
                            
                            <table class="table table-bordered" id="myTable">
                                            <thead>
                                               <tr>
                                                    <th style="display: none"></th>
                                                    <th>Add Process</th>
                                                  </tr>
                                            </thead>
                                            <tbody class="dumy_cont">
                                                
                                                <?php if(isset($customer_details->customer_process) && !empty($customer_details->customer_process)){ ?>
                                                <?php foreach($customer_details->customer_process as $process_key => $process_val ){ ?>
                                                   <tr class="st_tr">
                                                    <td style="display: none"><input type="hidden" name="varient_name[]" id="menu_varient_name_1" value="<?php echo @$process_val->id;?>"></td>
                                                    <td> <input type="text" class="form-control form-filter input-sm remove_first_space" value="<?php echo @$process_val->process_name;?>" name="customer_process[]"> </td>
                                                    <td><a href="<?= base_url(); ?>admin/customer/deleteCustomerProcess?id=<?= ID_encode(@$process_val->id) ?>" class="btn blue">Remove</a></td>
                                                   </tr>
                                                <?php } ?>
                                                <?php }else{ ?>
                                                    <tr class="st_tr">
                                                    <td style="display: none"><input type="hidden" name="varient_name[]" id="menu_varient_name_1" value="-----"></td>
                                                    <td> <input type="text" class="form-control form-filter input-sm remove_first_space" value="<?php echo @$process_val->process_name;?>" name="customer_process[]"> </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                            
                            
			 <button id="my_click" type="button" name="" value="save" style="margin-left:10px;" class="btn blue">Add More</button>		
						
						
						
						
                        
                  </div>
						
			<div class="col-md-12">
                        <div class="form-actions  noborder" style="text-align: center">
                            <button type="submit" name="submitForm" value="save" class="btn blue">Save</button>
                            
                            <button type="button" class="btn default cancel-button">Cancel</button>
                        </div>
                    </div>
                        
                    </div>
                    
                        
                </form>
            </div>
           
        </div>

    </div>

</div>
<script>
    $('.cancel-button').on('click', function () {
        url = '<?php echo base_url(); ?>admin/customer';
        location = url;
    });
    
 $.validator.addMethod("leters_space",function(value,element){
        if(value=='' || value==null)
        {
                return true;
        }
        return  /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/.test(value);
        },'')


        $("#customer").validate({

            rules: {
                full_name: {
                    required: true,
                    //leters_space:true,
                },
                email: {
                    required: true,
                    email:true,
		    //remote:{url:"<?php echo base_url(); ?>"+"admin/qsa/signup_email",type:"POST"},
                },
                phone_number: {
                    required: true,
                    
                },
                company_name: {
                    required: true,

                },
                company_number: {
                    required: true,

                },
                address: {
                    required: true,
                    

                },
                status: {
                    required: true,

                },

            },
            messages: {

                full_name: {
                    required: 'Name is required',
                    //leters_space:'Name contain only alphabets',
                    
                },
                email: {
                    required: 'Email is required',
                    email:'Please Enter valid Email',
                     remote: "Email already in use"
                },
                phone_number: {
                    required: "Phone no. is required",
                },
                company_name: {
                    required: 'Company name is required'

                },

                company_number: {
                    required: 'Company contact number is required'

                },
                address: {
                    required: 'Address is required'

                },
                status: {
                    required: 'Status  is required'

                },
                unique_id: {
                    required: 'Unique id is required'
                },
                
            }
        });
   
</script> 
<script type="text/javascript">
	$(document).ready(function() {
	
	$("#my_click").click(function(){
		var rowCount = $('#myTable tr').length;
               $(".dumy_cont tr:last-child").after('<tr class="hahahah" id="rem'+rowCount+'"><td style="display:none"><input type="hidden" class="form-control form-filter input-sm" name="varient_name[]"></td><td><input type="text" class="form-control form-filter input-sm" name="customer_process[]"></td><td><a href="javascript:;" class="btn blue" onClick="remove('+rowCount+')">Remove</a></td></tr>');

	
    });
	})
        
        function remove(id){
        $('#rem'+id).remove(); 
    }
</script>
