<?php
$userInfo = $this->session->userdata('userinfo');
//pr($userInfo);
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
    padding-top: 15px !important;
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
.select-year{
    border: 1px solid #ccc;
    background-color: #ccc;
    text-align: center;
    color: #1f1f1f;
	margin-top: 20px;
   
}
.select-year p{
	font-size: 16px;
    font-weight: initial;
}
.year p{
    width: 50%;
    border: 1px solid #ccc;
    padding: 10px 10px;
    text-align: center;
    background-color: #ccc;
}
.row{
	background-color: #fff;
}
.Attestations{
	border-bottom: 1px solid#ccc;
	
}
.caption h3{
    font-weight: 500 !important;
    font-size: 22px;
}

.year{
	margin: 25px 0px 0px 0px;
}
.year > a{
	background-color: #ccc;
    color: #1f1f1f;
    padding: 10px 47px;
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="Attestations">
            <div class="caption">
                    <h3 class="caption-subject bold uppercase"> Attestations & Certifications</h3>
                </div>
                
            </div>
            
        </div>
		<div class="col-md-12 portlet-title">
                <div class="col-md-3 select-year">
				    <p class="years">Select Years</p>
                    
                </div>
                
            </div>
			<div class="col-md-12 portlet-title">
			<?php if(isset($year) && !empty($year)){
					foreach($year as $years){ ?>
				<div class="col-md-3 year">
				    <a  href="<?php echo base_url(); ?>customer/attestaions/attestaions_report?year=<?php echo ID_encode("2018"); ?>" class="years"><?php echo $years->year; ?></a>
                </div>
		<?php } }	?>
                
                
            </div>
			<div class="col-md-12 portlet-title">
                <div class="col-md-3 year">
				    
                    
                </div>
                
            </div>
			<div class="col-md-12 portlet-title">
                <div class="col-md-3 year">
				  
                    
                </div>
                
            </div>
		
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>








<!-- END PAGE CONTENT-->
</div>
</div>	
<!-- Button trigger modal -->