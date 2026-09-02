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
    width: 77%;
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
    font-size: 14px;
}
.year{
	margin: 25px 0px 0px 0px;
}
.year > a{
	color: #1f1f1f;
    padding: 10px 47px;
	margin-left: 23px;
	border: 1px solid #ccc;
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12 Attestations">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="col-md-10">
			 <div class="Attest1ations">
				<div class="caption">
					<h3 class="caption-subject bold uppercase"> Attestations & Certifications</h3>
				</div>
				
			</div>
		</div>
		<div class="col-md-2">
			 <div class="Attest1ations">
					<div class="caption">
						<h3 class="caption-subject">Year: <?php echo ID_decode($_GET['year']); ?></h3>
					</div>
					
			</div>
		</div>
       
    </div>
		
		<div class="col-md-12">
		<?php if(isset($process) &&  !empty($process)){
			foreach($process as $processname){
				
			//	pr($processname);
			?>
			 <div class="col-md-3 year">
				    <p class="years"><?php echo $processname->process_name; ?></p>
               <?php if(isset($processname->service) && !empty($processname->service)){
				   $count=0; 
					foreach($processname->service as $services){
                      if($count =="0"){ ?>
					 <a class="years"  href="<?php echo base_url(); ?>customer/attestaions/attestaions_details?year=<?php echo ID_encode($services->year); ?>&&service_id=<?php echo ID_encode($services->service_id); ?>&&process_id=<?php echo ID_encode($services->process_id); ?>" class="years"><?php echo $services->ServiceName; ?></a><br><br><br>
				<?php 	} $count++; } 
				} ?>
				 </div>
			<?php } }  ?>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						    <button type="button" class="btn blue cancel-button" style="padding: 8px 39px;">Back</button>
						</div>
			</div>
		<!-- END EXAMPLE TABLE PORTLET-->
		
    </div>
</div>








<!-- END PAGE CONTENT-->
</div>
</div>	
<!-- Button trigger modal -->
<script>
    $('.cancel-button').on('click', function () {
        url = '<?php echo base_url(); ?>customer/attestaions';
        location = url;
    });
    </script>
