<link href="<?= base_url(); ?>assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
<style>
label.error{
	color:#c8202d !important;
}
.form-horizontal .form-group.form-md-line-input {
    padding-top: 10px;
    margin: 0 15px 20px;
}
.form-horizontal .form-group.form-md-line-input .form-control~.form-control-focus, .form-horizontal .form-group.form-md-line-input .form-control~label {
    width: auto;
	left: 0px;
    right: 15px;
}
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
<!-- BEGIN PAGE CONTENT-->
<div class="row">
                        <div class="col-md-8 ">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered no-padding">
                                <?php echo get_flashdata(); ?>
                                <div class="portlet-body form">
                                    <?php echo form_open('', array('class' => 'form-horizontal','id'=>'changePasswordForm')); ?>
                                   <div class="form-body">
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="password" class="form-control" id="form_control_1" name="password"  placeholder="Current Password" style="margin-top:20px;">
                                                <label for="form_control_1">Current Password &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_1" style="margin-top: 60px; color:#c8202d"></label>
                                            </div>
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="password" class="form-control" id="password1" name="new_password"  placeholder="New Password" style="margin-top:20px;">
                                                <label for="password1">New Password &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="password1-error" class="error" for="password1" style="margin-top: 60px; color:#c8202d"></label>
<!--                                            <div class="">
                                                <ul  id="d1" class="list-group">
                                                    <li class="list-group-item list-group-item-success">Password Conditions</li>
                                                    <li class="list-group-item" id=d12><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Upper Case Letter</li>
                                                    <li class="list-group-item" id=d13 ><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Lower Case Letter </li>
                                                    <li class="list-group-item" id=d14><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Special Characters </li>
                                                    <li class="list-group-item" id=d15><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> One Number</li>
                                                    <li class="list-group-item" id=d16><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Length minimum 8 Characters</li>
                                                </ul>

                                            </div>-->
                                                
                                            </div>
                                        <div class="form-group form-md-line-input has-success">
                                                <input type="password" class="form-control" id="form_control_3" name="confirm_password"  placeholder="Retype new password" style="margin-top:20px;">
                                                <label for="form_control_1">Retype new password &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_3" style="margin-top: 60px; color:#c8202d"></label>
                                            </div>
                                            
                                        </div>
                                   
                                         <div class="form-actions text-center noborder">
                                            <button type="submit" class="btn blue">Submit</button>
                                            <button type="button" class="btn default cancel-button">Cancel</button>
                                        </div>
                                    
    
                                    <?php echo form_open();?>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>







                
<script>
$('.cancel-button').on('click', function() {
                url = '<?php echo base_url(); ?>consultant/dashboard';	
                location = url;
        });
$("input[name='password']").keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
 $("input[name='new_password']").keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
$("input[name='confirm_password']").keypress(function( e ) {
    if(e.which === 32) 
        return false;
});

//Our validation script will go here.
    $(document).ready(function(){
    
       //custom validation rule - text only
       $.validator.addMethod("alphnumOnly", 
                              function(value, element) {
                                  return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/.test(value);
                              }, 
                              "Password must contain at least one numeric, two uppercase, two lower character and two special symbols."
      );
$.validator.addMethod("is_password_match", function(value,element) {
 var newPass =   $("input[name='new_password']").val();
 if(newPass==value){
     return true;
 }
 return false;
},'');  


$("#changePasswordForm").validate({
    rules: {  
        password:{
            required: true,	
        },new_password:{
            required: true,
		//alphnumOnly: true,
        },confirm_password:{
            required: true,
	   // alphnumOnly: true,
            equalTo : "#password1"
        }
        
         
    },
    messages:{
            password:{
               required: 'Current Password Required',
               
                           },
            new_password:{
               required: 'New Password Required',
               alphnumOnly: '',
            },
            confirm_password:{
                required: 'Retype Password Required',
                alphnumOnly: 'Password must contain at least one numeric, two upper, two lower character, two special symbol and minimum 8 character',
               
            }
          
    }
    
});        
})
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

})
</script>

