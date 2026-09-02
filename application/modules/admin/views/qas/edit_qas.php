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
                                    <form role="form" action="" method="POST" id="editqsa">
                                        <div class="form-body">
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="form_control_1" name="full_name" value="<?php if(!empty($qsa_detail->full_name)){ echo $qsa_detail->full_name;}?>" placeholder="Enter name">
                                                <label for="form_control_1">Name &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_1" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="email" name="email" value="<?php if(!empty($qsa_detail->email)){ echo $qsa_detail->email;}?>" placeholder="Enter email" >
                                                <label for="form_control_1">Email Id &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="email-error" class="error" for="email" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="phonenumber" value="<?php if(!empty($qsa_detail->phone_number)){ echo $qsa_detail->phone_number;}?>" name="phone_number" minlength="7" maxlength="15" placeholder="Enter  phone number">
                                                <label for="phonenumber">Phone Number &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="phonenumber-error" class="error" for="phonenumber" style="margin-top: 56px; color:#c8202d"></label>
                                                 
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control  remove_first_space" id="companyname" name="company_name" value="<?php if(!empty($qsa_detail->company_name)){ echo $qsa_detail->company_name;}?>" placeholder="Enter  company name">
                                                <label for="companyname">Company Name </label>
                                                <label id="mobilenum-error" class="error" for="companyname" style="margin-top: 56px; color:#c8202d"></label>
                                                 <div id="mobilenum1" style="color:#c8202d;"></div>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="companynumber" value="<?php if(!empty($qsa_detail->company_number)){ echo $qsa_detail->company_number;}?>" name="company_number" minlength="7" maxlength="15" placeholder="Enter  company contact number">
                                                <label for="companynumber">Company Contact Number </label>
                                                 <label id="companynumber-error" class="error" for="companynumber" style="margin-top: 56px; color:#c8202d"></label>
                                                </div>
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <textarea class="form-control remove_first_space" id="address" name="address"   placeholder="Enter  address"><?php if(!empty($qsa_detail->address)){ echo $qsa_detail->address;}?></textarea>
                                                <label for="address">Address &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="mobilenum-error" class="error" for="address" style="margin-top: 72px; color:#c8202d"></label>
                                                 <div id="mobilenum1" style="color:#c8202d;"></div>
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="form_control_9" name="status">
                                                    <option value="">Please select status</option>
                                                    <option value="active" <?php if($qsa_detail->status == 'active'){ echo "selected";}?>>Active</option>
                                                    <option value="inactive" <?php if($qsa_detail->status == 'inactive'){ echo "selected";}?>>Inactive</option>
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

$("#editqsa").validate({
	
        rules: {
		
                full_name:{
                        required:true,
                       // leters_space:true,
                },
                
                email: {
                 required: true,
                 email:true,
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
                        // leters_space:'Name contain only alphabets',
                },
                
		
                email: {
                        required:'Email is required !',
                        email:'Please Enter valid Email',
                        remote: "Email already in use"
                },
		
                phone_number: {
                        required:'Phone number is required!',
			
                },
		
//                company_name: {
//                        required:'Company name is required!',
//			
//                },
//                company_number: {
//                        required:'Company contact number is required!',
//			
//                },
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

function confirmmanager(){
var ismanager = $("#manager").val();
if(ismanager == 1){
    var r = confirm("Do you want to asign as department manager");
    if (r) {
      return true;
    }else{
        $("#manager").val('0');
            }
  }
}


</script>
<script>
$(document).ready(function() {
////////////////////
$('#t1').keyup(function(){
var str=$('#t1').val();
var upper_text= new RegExp('[A-Z]');
var lower_text= new RegExp('[a-z]');
var number_check=new RegExp('[0-9]');
var special_char= new RegExp('[!/\'^£$%&*()}{@#~?><>,|=_+¬-\]');

var flag='T';

if(str.match(upper_text)){
$('#d12').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Upper Case Letter ");
$('#d12').css("color", "green");
}else{$('#d12').css("color", "red");
$('#d12').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter ");
flag='F';}

if(str.match(lower_text)){
$('#d13').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Lower Case Letter ");
$('#d13').css("color", "green");
}else{$('#d13').css("color", "red");
$('#d13').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter ");
flag='F';}

if(str.match(special_char)){
$('#d14').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Special Characters ");
$('#d14').css("color", "green");
}else{
$('#d14').css("color", "red");
$('#d14').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Characters ");
flag='F';}

if(str.match(number_check)){
$('#d15').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> One Number ");
$('#d15').css("color", "green");
}else{
$('#d15').css("color", "red");
$('#d15').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number ");
flag='F';}


if(str.length>7){
$('#d16').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> Length minimum 8 Characters ");

$('#d16').css("color", "green");
}else{
$('#d16').css("color", "red");
$('#d16').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length minimum 8 Characters ");

flag='F';}


if(flag=='T'){
$("#d1").fadeOut();
$('#display_box').css("color","green");
$('#display_box').html("<span class='glyphicon glyphicon-ok' aria-hidden='true'></span> "+str);
}else{
$("#d1").show();
$('#display_box').css("color","red");
$('#display_box').html("<span class='glyphicon glyphicon-remove' aria-hidden='true'></span> "+str);
}
});
///////////////////
$('#t1').blur(function(){
$("#d1").fadeOut();
});
///////////
$('#t1').focus(function(){
$("#d1").show();
});
////////////

});

function checkMobile(){
var countrycode = $("#countrycode").val();
var mobilenum = $("#mobilenum").val();
if(countrycode == ''){
    $("#countrycode-error").html('Country code is required');
    return false;
        }
if(mobilenum == ''){
    $("#mobilenum-error").html('Mobile number is required');
    return false;
        }        
$.ajax({
    url:'<?php echo base_url(); ?>admin/user/check_mobile',
    type:"POST",
    data:{
        countrycode:countrycode,
        mobilenum:mobilenum,
    },
    success:function(result){
        if(result == 0){
        alert('Mobile number is already in used');
        $("#mobilenum").val('');
         return false;
        }else if(result == 1){
           $("#mobilenum1").html(''); 
           
        }    
    }
    
});
}
</script>