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
                           
                        <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="asignedcustomeid" name="process_id">
                                                    <option value="">Please select Process</option>
                                                    <?php if(isset($process) && !empty($process)){?>
                                                        <?php foreach($process as $cust_process){ ?>
                                                    <option value="<?php echo $cust_process->id;?>"><?php echo $cust_process->process_name;?> </option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                  </select>
                                                <label for="asignedcustomeid">Select Process &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="asignedcustomeid-error" class="error" for="asignedcustomeid" style="margin-top: 56px; color:#c8202d"></label>
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
        
        $("#customer").validate({

            rules: {
                
                process_id: {
                    required: true,

                },

            },
            messages: {

               
                process_id: {
                    required: 'Please select process'

                },
                
                
            }
        });
   
</script> 