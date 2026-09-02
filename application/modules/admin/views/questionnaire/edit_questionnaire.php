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
                                            
                                            <div class="form-group form-md-line-input has-success mbl_input">
                                                <textarea class="form-control remove_first_space" id="question" name="question"   placeholder="Enter  question"><?php if(!empty($question_detail->question)){ echo $question_detail->question;}?></textarea>
                                                <label for="question">Question &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="question-error" class="error" for="question" style="margin-top: 72px; color:#c8202d"></label>
                                                 
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
<?php $serviceId = ID_encode($question_detail->Service);?>
<script>
   $('.cancel-button').on('click', function() {
                var serid = <?php echo $serviceId;?>;
                url = '<?php echo base_url(); ?>admin/questionnaire/questionnaire_list?id='+serid;	
                location = url;
        });
$("#editquestion").validate({
	
        rules: {
		
                full_name:{
                        required:true,
                        leters_space:true,
                },
           
                 

		
        },       	              
        messages: {
		
                full_name: {
                         required:'Name is required',
                         leters_space:'Name contain only alphabets',
                },
                
		
               
            }
});

</script>