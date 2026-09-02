<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);die;
?>				
<?php echo get_flashdata(); ?>

<style>
    .table-scrollable .dataTable td>.btn-group, .table-scrollable .dataTable th>.btn-group {
        position: relative;
        margin-top: -2px;
    }
    .form-group-custom .col-md-6 label {
        float: left;
        width: 36%;
    }
    .form-group-custom {
        position: absolute;
        right: 0px;
        z-index: 9;
        background: #fff;
        width: 58%;
        float: right;
        top: -29px;
    }
    .select-style {
        width: 60%;
        float: right;
        margin-top: -9px;
    }
    .custom-height-top{
        position: relative;
    }
    input#fn_from_date, input#fn_to_date {
        padding: 5px 10px;
        border-radius: 3px;
        border: 1px solid #ddd;
    }
    .input-small {
        width: 164px!important;
    }
    .stuts_jbdt{position: absolute; left: 10px;}
    .portlet.light .portlet-body {
        
    }
    .table-scrollable {
        overflow-x: auto;
    }
    .supplier{
        padding: 6px 17px;
        border-radius: 3px;
        border: 1px solid #ddd;
        margin-top: 5px;
    }

    .p-10{
        padding:10px;
    }
    .p-20{
        padding:20px;
    }
    .row_end{
        border-bottom:1px solid #ccc;
    }
    .border{
        border: 1px solid #ccc;
    }
</style>
<div id='msgShow'></div>
<div class="row" style="margin-left:0px;margin-right:0px;">
    <div class="col-md-12 portlet light bordered" style="padding: 0px 20px 5px;">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered" style="border:none !important;box-shadow:none;padding: 12px 20px 0px;margin-bottom:0px;padding: 10px 0;">
            <div class="portlet-title" style="margin-bottom:0px;">
                <div class="caption font-dark">
				   <span class="caption-subject bold uppercase"><?php echo $service_name->service_name; ?></span>
                    </div>
					
                    </div>
            

            <div class="portlet-body" style="padding-top:0px;">
                <div class="row p-10">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
                                <div class="col-md-12 portlet-body text-center p-10 border" style="background-color: #ccc;">
                                    <label class="control-label" style="color: #697882;"><a href="<?= base_url();?>customer/process/upload_evidences?id=<?php echo ID_encode($service_name->id);?>"><strong>Upload Evidences</strong></a></label>
                                </div>

                            </div>
                            
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-10">
                                <div class="col-md-12 portlet-body text-center p-10 border" style="background-color: #ccc;">
                                    
                                    <label class="control-label" style="color: #697882;"><a href="<?= base_url();?>customer/process/view_evidences?id=<?php echo ID_encode($service_name->id);?>"><strong>View Evidences</strong></a></label>
                                </div>

                            </div>
                            
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
    </div>
