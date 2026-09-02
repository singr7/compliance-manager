<style>
      .mbl_select{
        width: 30%;
        display: inline-block;
    }
    .mbl_input{
        width: 65%;
        display: inline-block;
        float: right;
    }
</style>
<link href="<?= base_url(); ?>assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-sidebar hide" style="width:250px;">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <?php
                    if(@$result->profile_image!='')
                    {
                        $src_profile_image  =   base_url()."uploads/profile_image/".@$result->profile_image;
                    }else{
                        $src_profile_image  =   base_url()."assets/admin/pages/media/profile/profile_user_default.png"; 
                    }    
                    ?> 
                    <img src="<?php echo $src_profile_image; ?>" class="img-responsive" alt="">
                </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <?= ucfirst(currentuserinfo()->first_name .' '.currentuserinfo()->last_name) ?>
                    </div>
                    <div class="profile-usertitle-job">

                    </div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR BUTTONS -->
                <div class="profile-userbuttons">

                </div>
                <!-- END SIDEBAR BUTTONS -->
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li>

                        </li>
                        <li>

                        </li>

                        <li>

                        </li>
                    </ul>
                </div>
                <!-- END MENU -->
            </div>
            <!-- END PORTLET MAIN -->
        </div>
        <!-- END BEGIN PROFILE SIDEBAR -->
        <!-- BEGIN PROFILE CONTENT -->
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <?= get_flashdata() ?>
                    <div id="errorMsg"></div>
                    <div class="portlet light">
<!--                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase">Update Profile</span>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                </li>
                                <li>
                                    <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                </li>
                                
                            </ul>
                        </div>-->
                        <div class="portlet-body form">
                            <div class="tab-content">
                                <!-- PERSONAL INFO TAB -->
                                <div class="tab-pane active" id="tab_1_1">
                                    <?php echo form_open('', array('class' => 'form-horizontal','id'=>'profileForm')); ?>
                                        
                                    <div class="form-body">
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="form_control_1" name="full_name" value="<?= $result->full_name?>" placeholder="Full Name" style='margin-left: 15px;margin-top: 20px; width: 97%;'>
                                                <label for="form_control_1">Full Name &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_1" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                       
                                          
                                           <div class="form-group form-md-line-input has-success">
                                               <input type="text" class="form-control codedecimalnumeric2 remove_first_space" id="form_control_3" name="phone_number" value="<?= $result->phone_number?>" placeholder="Phone No."  minlength="7" maxlength="15" style='margin-left: 15px;margin-top: 20px; width: 97%;'>
                                                <label for="form_control_1">Phone No. &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_3" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                        <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="form_control_4"  name="email" value="<?=$result->email?>" placeholder="Email Address" class="form-control remove_first_space" readonly style='margin-left: 15px;margin-top: 20px; width: 97%;'>
                                               <label for="form_control_1">Email &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_1-error" class="error" for="form_control_4" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                        
                                            
                                            
                                            
                                        </div>
                                        <div class="form-actions noborder">
                                            <button type="submit" class="btn blue">Submit</button>
                                            <button type="button" class="btn default cancel-button">Cancel</button>
                                        </div>
                                    
                                    
                                    
                                    <?php echo form_close();?>
                                </div>
                                <!-- END PERSONAL INFO TAB -->
                                <!-- CHANGE AVATAR TAB -->
                                <div class="tab-pane" id="tab_1_2">
                                    <!--<p>
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </p>-->
                                    <?=form_open_multipart('', array('id' => 'imgChangeForms'));?>
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="img1" alt=""/>
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                                </div>
                                                <div class="select-img21">
                                                    <span class="btn default btn-file  select-profile-imgs">
                                                        <span class="fileinput-new">
                                                            Select Image </span>
                                                        <span class="fileinput-exists">
                                                            Change </span>
                                                        <input type="file" name="userfile"   id="userfile" data="<?=@$result->profile_image?>"  onchange="readURL(this);">
                                                    </span>
<!--                                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                                                        Remove </a>-->
                                                </div>
                                            </div>
                                           <!-- <div class="clearfix margin-top-10">
                                                <span class="label label-danger"> NOTE! </span>
                                                <span class="note-span10"> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                            </div>-->
                                        </div>
                                        <div class="margin-top-10">
                                            <button  type="button" class="btn blue bt-img">Submit </button>
                                            
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                                <!-- END CHANGE AVATAR TAB -->
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PROFILE CONTENT -->
 </div>
        </div>  

<!-- END PAGE CONTENT-->


<!-- END CONTENT -->


<script>

$('.cancel-button').on('click', function() {
		url = '<?php echo base_url();?>consultant/dashboard';	
		location = url;
	}); 

$(document).on('click','ul li',function(){
	$('.alert-success').remove();       // important remove success message
})
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('img1').src =  e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}    
    
$(document).on('click','.bt-img',function(){
    var th          =   $("input[name='userfile']");
    var old_file    =   th.attr('data');
    var fd = new FormData();
    $.each(th[0].files, function (i, file) {
      fd.append('userfile', file);
    });

               
    $.ajax({
        url: '<?= base_url()?>admin/profile/changeImage',
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        context: this,
        type: 'POST',
        success: function (dat) {//alert(dat);
          
            dat = JSON.parse(dat);
            if (dat['status'] == 'success')
            {
                location.href="<?=base_url();?>/admin/profile";
            }else{
                var error=dat['error_msg'];
                $("#errorMsg").html('<div class="alert alert-danger flash-row"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><i class="ace-icon fa fa-check green"></i><strong>Error!</strong> '+error+' </div>');
                $('html, body').animate({scrollTop: 0}, 'slow');
            }  
        }    			
    });
});    
   


$.validator.addMethod("mobno", function(value,element) {
        
        return  /^[0-9]{15}$/.test(value);
},''); 


$.validator.addMethod("leters_space",function(value,element){
	if(value=='' || value==null)
	{
		return true;
	}
	return  /^[A-Za-z]+( [A-Za-z]+)*$/.test(value);
	},'') 

$("#profileForm").validate({
    rules: {  
        first_name:{
            required: true,
	    maxlength: "40",	
        },last_name:{
            required: true,
	    maxlength: "40",
        },mobile_number:{
                required: true,
               // mobno: true
        },email:{
               required: true,
               email:true
        },
        country_code: {
                 required: true,
		
                },
        
         
    },
    messages:{
            first_name:{
               required: 'First Name is required.',
               maxlength :'Maximunm First Name length should be 40',	
            },
            last_name:{
               required: 'Last Name is required.',
              maxlength :'Maximunm Name length should be 40',
            },mobile_number:{
                required: 'Mobile no. is required.',
               // mobno: 'Mobile Number should be 10 digit number.',
            },email:{
                required: 'Email Address is required.',
                email: 'Invalid email address.',
            },
            country_code: {
                 required: 'Country code is required.',
		
                },
          
    }
    
});        
</script>

