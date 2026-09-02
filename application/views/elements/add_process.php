<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add Process</h4>
      </div>
      <div class="modal-body">
        <div class="row">
                        <div class="col-md-12 ">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                
                                <div class="portlet-body form">
                                    <form method='Post' action='<?= base_url();?>admin/customer/add_process' id='addcustomerprocess'>
                                   <div class="form-body">
                                      
                                       <div class="form-group form-md-line-input has-success">
                                           <input type="text" class="form-control" id="addprocess" name="process_name"  placeholder="Add Process" required="true">
                                               <label for="addprocess">Add Process</label>
                                                <label id="addprocess-error" class="error" for="addprocess" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                       
                                       
                                            
                                        </div>
                                         
                                        <input type='hidden' id='formid' name='customer_id'>
                                    <div class="form-actions noborder" style="text-align: center;">
                                            <button type="submit" class="btn blue">Submit</button>
                                            <button type="button" class="btn default cancel-button" data-dismiss="modal" aria-hidden="true"">Cancel</button>
                                        </div>
                                    
    
                                    </form>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
      </div>
      
    </div>
  </div>
</div>
