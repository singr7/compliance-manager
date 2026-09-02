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
                                <h3>Add <?php echo $testing_name->service_name;?> Project(Testing)</h3>
                                <?php echo get_flashdata(); ?>
                                <div class="portlet-body form">
                                    <form role="form" action="" method="POST" id="project">
                                        <div class="form-body">
                                            <input type="hidden" name="testing_id" value="<?php echo ID_encode($testing_name->id);?>">
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="customer_id" name="customer_id" onchange="get_process()">
                                                    <option value="">Please select customer</option>
                                                    <?php foreach($customer_list as $customer){ ?>
                                                    <option value="<?php echo $customer->id;?>"><?php echo $customer->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="customer_id">Select Customer &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="customer_id-error" class="error" for="customer_id" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            <div id="process">
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="processid" name="process_id">
                                                    <option value="">Please select Process</option>
                                                    
                                                  </select>
                                                <label for="processid">Select Process &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="processid-error" class="error" for="processid" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="qsa" name="qsa_id">
                                                    <option value="">Please select QSA</option>
                                                   <?php foreach($qsa_list as $qsa){ ?>
                                                    <option value="<?php echo $qsa->id;?>"><?php echo $qsa->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="qsa">Select QSA &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="qsa-error" class="error" for="qsa" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="consultants" name="consultant_id">
                                                    <option value="">Please select Consultant</option>
                                                    <?php foreach($consultant_list as $consultant){ ?>
                                                    <option value="<?php echo $consultant->id;?>"><?php echo $consultant->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="consultants">Select Consultant &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="consultants-error" class="error" for="consultants" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="qa" name="qa_id">
                                                    <option value="">Please select QA</option>
                                                   <?php foreach($qa_list as $qa){ ?>
                                                    <option value="<?php echo $qa->id;?>"><?php echo $qa->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="qa">Select QA &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="qa-error" class="error" for="qa" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="star_date" name="start_date" placeholder="Enter start date" readonly="true" >
                                                <label for="star_date">Start Date &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="star_date-error" class="error" for="star_date" style="margin-top: 56px; color:#c8202d"></label>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $('.cancel-button').on('click', function() {
                url = '<?php echo base_url(); ?>admin/compliances/compliances_listing?id=<?php echo ID_encode($service_name->id);?>';	
                location = url;
        });
        
$( "#star_date" ).datepicker({
     dateFormat: 'yy-m-d',
     startDate: '+0d',
      minDate: '0',
 });

function get_process(){
    var customerid = $("#customer_id").val();
    $.ajax({
        url:'<?php echo base_url(); ?>'+'admin/compliances/get_customer_process',
        data:{
            customerid:customerid,
        },
        type:"Post",
    success:function(dat){
     $("#process").empty().html(dat);
    }
    });
}



$("#project").validate({
	
        rules: {
		
                customer_id:{
                        required:true,
                        
                },
                
                process_id: {
                 required: true,
                 
                },
		
                qsa_id: {
                 required: true,
		
                },
                consultant_id: {
                 required: true,
		
                },
                qa_id: {
                 required: true,
                 
                },
                start_date: {
                 required: true,
		
                },
               
        },       	              
        messages: {
		
                customer_id: {
                         required:'Please select customer',
                         
                },
                
		
                process_id: {
                        required:'Please select process',
                        
                },
		
                qsa_id: {
                        required:'Please select QSA',
			
                },
		
                consultant_id: {
                        required:'Please select consultants',
			
                },
                qa_id: {
                        required:'Please select QA',
			
                },
                start_date: {
                        required:'Please select start date',
			
                },
               
                
            }
});
</script>