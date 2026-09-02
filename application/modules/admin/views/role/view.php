				
<?php echo get_flashdata(); ?>
<div class="row">                   

    <div id="msgShow"></div>
    <div class="col-md-12 col-sm-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                                        <span class="caption-subject bold uppercase"> <?= $role_data->department;?> Department </span>
                                    </div>
                <div class="actions">
                                         <div class="btn-group">
                                             <a href="javascript:history.back()" id="sample_editable_1_new" class="btn sbold green">Back</a>
                                                </div>
                                    </div>
            </div>
            <div class="portlet-body">
           <form action="<?php echo base_url();?>admin/role/save_permission?id=<?=ID_encode(@$role_data->id)?>" method="post">   

                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            
                            <th width="20%" align="center" style="text-align: center;">
                               
                                Module Name
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                               Add
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                Edit
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                View
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                Import 
                            </th>
                               <th width="9%" align="center" style="text-align: center;">
                                Delete
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                Accept
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                Download
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                                <tr class="odd gradeX">
                                   
                                    
                                    <td width="20%" align="center" style="text-align: center;">
                                      <span><input  type="checkbox" id="dash_view"/></span>
                                      <b>Supplier/Sub-contractor  </b>
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view" name="permission[user][add_supplier]" data-name="user_chkd[]"  value="101" <?php if(isset($mypermission['user']['add_supplier']) && $mypermission['user']['add_supplier']=='101'){ echo 'checked';}?> />
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view" name="permission[user][edit_supplier]" data-name="user_chkd[]"  value="102" <?php if(isset($mypermission['user']['edit_supplier']) && $mypermission['user']['edit_supplier']=='102'){ echo 'checked';}?> />
                                    </td>
                                     <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view" name="permission[user][view_supplier]" data-name="user_chkd[]"  value="103" <?php if(isset($mypermission['user']['view_supplier']) && $mypermission['user']['view_supplier']=='103'){ echo 'checked';}?> />
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view" name="permission[Excel_import][import_supplier]" data-name="user_chkd[]"  value="105" <?php if(isset($mypermission['Excel_import']['import_supplier']) && $mypermission['Excel_import']['import_supplier']=='105'){ echo 'checked';}?> />
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view" name="permission[user][deleteSupplier]" data-name="user_chkd[]"  value="104" <?php if(isset($mypermission['user']['deleteSupplier']) && $mypermission['user']['deleteSupplier']=='104'){ echo 'checked';}?> />
                                    </td>
                                     <td width="9%" align="center" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                    </td>
                                      <td width="9%" align="center" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                    </td>

                                </tr>
                                <tr class="odd gradeX">
                                   
                                    
                                    <td width="20%" align="center" style="text-align: center;">
                                      <span><input  type="checkbox" id="dash_view1"/></span>
                                      <b>Invoice/Ticketing  </b>
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                    </td>
                                     <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view1" name="permission[invoice][view_invoice]" data-name="invoice_chkd[]"  value="103" <?php if(isset($mypermission['invoice']['view_invoice']) && $mypermission['invoice']['view_invoice']=='103'){ echo 'checked';}?> />
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <img src="<?php echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                    </td>
                                     <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view1" name="permission[invoice][accept_invoice]" data-name="invoice_chkd[]"  value="105" <?php if(isset($mypermission['invoice']['accept_invoice']) && $mypermission['invoice']['accept_invoice']=='105'){ echo 'checked';}?> />
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <input  type="checkbox" id="dash_view1" name="permission[invoice][download_pdf]" data-name="invoice_chkd[]"  value="106" <?php if(isset($mypermission['invoice']['download_pdf']) && $mypermission['invoice']['download_pdf']=='106'){ echo 'checked';}?> />
                                    </td>
                                     

                                </tr>
                        

                    </tbody>
                </table>
                <input type="hidden" value="<?= $role_data->id;?>" name="department">
                                        <div class="form" style="text-align: center">
                                            <!--<input type="button" id="submit1" value="Submit by Form ID" />-->
            				                <button type="submit" id="submit_button" class="btn blue">Submit</button>
            				                <button type="reset" class="btn btn-danger">Reset</button>
                                        </div>
                                  
                </form>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>                
</div>

<!-- END PAGE CONTENT-->
</div>
</div>
<script>
    $('#dash_view').click(function(){
    //alert($(this).is(':checked'));
    if($(this).is(':checked')){
        $('[data-name="user_chkd[]"]').each(function(){
            $(this).parent().addClass('checked');
            $(this).prop('checked', true);
        });
    } else {
        $('[data-name="user_chkd[]"]').each(function(){
            $(this).parent().removeClass('checked');
            $(this).prop('checked', false);
        });
    }
});

$('#dash_view1').click(function(){
    //alert($(this).is(':checked'));
    if($(this).is(':checked')){
        $('[data-name="invoice_chkd[]"]').each(function(){
            $(this).parent().addClass('checked');
            $(this).prop('checked', true);
        });
    } else {
        $('[data-name="invoice_chkd[]"]').each(function(){
            $(this).parent().removeClass('checked');
            $(this).prop('checked', false);
        });
    }
});
    </script>
