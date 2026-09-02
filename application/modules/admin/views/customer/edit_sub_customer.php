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
                            <input type="text" class="form-control remove_first_space" id="fullname" name="full_name" value="<?php if(isset($subCustomerDetails->full_name) && !empty($subCustomerDetails->full_name)){ echo $subCustomerDetails->full_name;};?>" placeholder="Enter Name">
                            <label for="fullname">Name&nbsp;<span style='color:red;'>*</span></label>
                            <label id="fullname-error" class="error" for="fullname" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control remove_first_space" id="email" name="email" value="<?php if(isset($subCustomerDetails->email) && !empty($subCustomerDetails->email)){ echo $subCustomerDetails->email;};?>" placeholder="Enter Email Id">
                            <label for="email">Email Id&nbsp;<span style='color:red;'>*</span></label>
                            <label id="email-error" class="error" for="email" style="margin-top: 56px; color:#c8202d"></label>
                        </div>
                            
                            <div class="form-group form-md-line-input has-info">
                                <select class="form-control" id="processselect" name="process_id[]" multiple>

                                    <?php if (isset($process) && !empty($process)) { ?>
                                       
                                        <?php foreach ($process as $customerprocess) { ?>
                                            <option value="<?php echo $customerprocess->id; ?>" <?php if(in_array($customerprocess->id, $subCustomerProcess)){ echo 'selected';};?>><?php echo $customerprocess->process_name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <label for="framework">Select Process</label>
                                <label id="framework-error" class="error" for="framework" style="margin-top: 56px; color:#c8202d"></label>
                            </div> 
                           <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="form_control_9" name="status">
                                                    <option value="">Please select status</option>
                                                    <option value="active" <?php if($subCustomerDetails->status=='active'){ echo 'selected';};?>>Active</option>
                                                    <option value="inactive" <?php if($subCustomerDetails->status=='inactive'){ echo 'selected';};?>>Inactive</option>
                                                  </select>
                                                <label for="form_control_1">Select status &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="form_control_9-error" class="error" for="form_control_9" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>  
                        </div>
                        
                        
						
			<div class="col-md-8">
                        <div class="form-actions  noborder" style="text-align: center">
                            <button type="submit"  class="btn blue">Save</button>
                            
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
         url = '<?php echo base_url(); ?>admin/customer/sub_customer_listing?id=<?php echo ID_encode($customer_id);?>';
        location = url;
    });


$.validator.addMethod("leters_space",function(value,element){
        if(value=='' || value==null)
        {
                return true;
        }
        return  /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/.test(value);
        },'')
        
$.validator.addMethod("check_email", function (value, element) {
            if (value == '' || value == null)
            {
                return true;
            }
            return  /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(value);
        }, '') 
   
   
        $("#customer").validate({

            rules: {
                full_name: {
                    required: true,
                    //leters_space:true,
                },
                email: {
                    required: true,
                    check_email:true,
		    
                },
                'process_id[]':{
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
                    check_email:'Please Enter valid Email',
                     
                },
               
                status: {
                    required: 'Status  is required'

                },
                
                
            }
        });
   
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <script type="text/javascript">
        $(document).ready(function() {
          $('#processselect').multiselect();
         });
  </script>                           