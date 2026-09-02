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
                                    <form role="form" action="" method="POST" id="addqsa">
                                        <div class="form-body">
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="form_control_1" name="full_name" placeholder="Enter name">
                                                <label for="form_control_1">Name &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_1" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="email" name="email" placeholder="Enter email" >
                                                <label for="form_control_1">Email Id &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="email-error" class="error" for="email" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="phonenumber" name="phone_number" minlength="7" maxlength="15" placeholder="Enter  phone number">
                                                <label for="phonenumber">Phone Number &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="phonenumber-error" class="error" for="phonenumber" style="margin-top: 56px; color:#c8202d"></label>
                                                 
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control  remove_first_space" id="companyname" name="company_name"  placeholder="Enter  company name">
                                                <label for="companyname">Company Name </label>
                                                <label id="mobilenum-error" class="error" for="companyname" style="margin-top: 56px; color:#c8202d"></label>
                                                 <div id="mobilenum1" style="color:#c8202d;"></div>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="companynumber" name="company_number" minlength="7" maxlength="15" placeholder="Enter  company contact number">
                                                <label for="companynumber">Company Contact Number</label>
                                                 <label id="companynumber-error" class="error" for="companynumber" style="margin-top: 56px; color:#c8202d"></label>
                                                </div>
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <textarea class="form-control remove_first_space" id="address" name="address"  placeholder="Enter  address"></textarea>
                                                <label for="address">Address &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="mobilenum-error" class="error" for="address" style="margin-top: 72px; color:#c8202d"></label>
                                                 <div id="mobilenum1" style="color:#c8202d;"></div>
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="form_control_9" name="status">
                                                    <option value="">Please select status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                  </select>
                                                <label for="form_control_1">Select status &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_9-error" class="error" for="form_control_9" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                              
                                        </div>
                                        <div class="form-actions noborder text-center">
                                            <button type="submit" class="btn blue">Submit</button>
                                            <button type="button" class="btn default cancel-button">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
<script>
    $('.cancel-button').on('click', function() {
                url = '<?php echo base_url(); ?>admin/qas';	
                location = url;
        });
        
  //Our validation script will go here.
    $(document).ready(function(){
    
       //custom validation rule - text only
      $.validator.addMethod("alphnumOnly", 
                              function(value, element) {
                                  return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/.test(value);
                              }, 
                              //"Password must contain at least one numeric, two uppercase, two lower character and two special symbols."
      );	
	  
        // only letters with space

$.validator.addMethod("leters_space",function(value,element){
        if(value=='' || value==null)
        {
                return true;
        }
        return  /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/.test(value);
        },'')

// only numbers and letters 

$.validator.addMethod("leters_numbers_space",function(value,element){
        if(value=='' || value==null)
        {
                return true;
        }
        return  /^[a-zA-Z0-9,]+(\s{0,1}[a-zA-Z0-9, ])*$/.test(value);
        },'')

$("#addqsa").validate({
	
        rules: {
		
                full_name:{
                        required:true,
                        //leters_space:true,
                },
                
                email: {
                 required: true,
                 email:true,
		 remote:{url:"<?php echo base_url(); ?>"+"admin/qsa/signup_email",type:"POST"},
                },
		
                phone_number: {
                 required: true,
		
                },
//                company_name: {
//                 required: true,
//		
//                },
//                company_number: {
//                 required: true,
//                 
//                },
                country_code: {
                 required: true,
		
                },
               
                address:{
                        required:true,
			
                },
                 status:{
                  required:true,   
        }

		
        },       	              
        messages: {
		
                full_name: {
                         required:'Name is required',
                         //leters_space:'Name contain only alphabets',
                },
                
		
                email: {
                        required:'Email is required !',
                        email:'Please Enter valid Email',
                        remote: "Email already in use"
                },
		
                phone_number: {
                        required:'Phone number is required!',
			
                },
		
                company_name: {
                        required:'Company name is required!',
			
                },
                company_number: {
                        required:'Company contact number is required!',
			
                },
                address: {
                        required:'Address is required!',
			
                },
               
                status: {
                    required:'Please select status!'
		
        },
            }
});
}) 

function checkEmail()
{
	var formdata=$("#email").serialize();
	$.ajax({
		type: "POST",
		url: '<?php echo base_url(); ?>'+'admin/user/signup_email',
		data: formdata,
		success:function(response)
		{
			//alert(response);return false;
			if(response == 1)
			{
				$('#email-error').html('Email already registered. Please enter an alternative email.');
			}
			else 
			{
				$('#email-error').html(' ');
			}
		}
	 }); 
}


</script>