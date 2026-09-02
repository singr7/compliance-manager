<!-- BEGIN SIDEBAR -->
            <?php
$uri1 = @uri_segment(1);
$uri2 = @uri_segment(2);
$uri3 = @uri_segment(3);

$userInfo = $this->session->userdata('userinfo');
$user_type = $userInfo->user_type;
if(isset($_GET['id']) && !empty($_GET['id'])){
$uri4 = @ID_decode($_GET['id']);
}
if(isset($_GET['process_id']) && !empty($_GET['process_id'])){
$uri4 = @ID_decode($_GET['process_id']);
}
?>
 <?php $services = _get_all_service();  ?>
 <?php $testingservices = _get_all_testing_service(); ?>
 
<?php $userData = $this->session->userdata("userinfo");?>
<div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        
                     <?php if($userData->user_type == 1){ ?>   
                        <li class="nav-item start ">
                            <a href="<?= base_url();?>admin" class="nav-link nav-toggle">
                                 <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        
                        <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'qsa' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">Manage QSAs</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'qsa' && $uri3 == '') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/qsa" class="nav-link ">
									
                                        <span class="title">QSAs List</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
				 <li class="nav-item start <?php if ($uri1 == 'admin' && $uri3 == 'add_qsa') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/qsa/add_qsa" class="nav-link ">
                                        
                                        <span class="title">Add QSAs</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>

                                
                            </ul>
                        </li>
                        <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'qas' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">Manage QAs</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'qas' && $uri3 == '') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/qas" class="nav-link ">
									
                                        <span class="title">QAs List</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
				 <li class="nav-item start <?php if ($uri1 == 'admin' && $uri3 == 'add_qas') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/qas/add_qas" class="nav-link ">
                                        
                                        <span class="title">Add QAs</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>

                                
                            </ul>
                        </li>
                        
                        <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'consultants' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">Manage Consultants</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'consultants' && $uri3 == '') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/consultants" class="nav-link ">
									
                                        <span class="title">Consultants List</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
				 <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'consultants' && $uri3 == 'add_consultants') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/consultants/add_consultants" class="nav-link">
                                        
                                        <span class="title">Add Consultants</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>

                                
                            </ul>
                        </li>
                        
                        <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'customer' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">Manage Customers</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'customer' && $uri3 == '' ||  $uri3 == 'sub_customer_listing' ||  $uri3 == 'view_customer' ||  $uri3 == 'edit_customer' ||  $uri3 == 'asign_process' ||  $uri3 == 'view_process') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/customer" class="nav-link ">
									
                                        <span class="title">Customers List</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
									<li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'customer' && $uri3 == 'add_customer') {echo 'active';}?><?php if ($uri1 == 'admin' && $uri2 == 'consultants' && $uri3 == 'add_consultants') {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/customer/add_customer" class="nav-link ">
                                        
                                        <span class="title">Add Customers</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>

                                
                            </ul>
                        </li>
                        
                        <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'compliances' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Compliances</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <?php foreach($services as $service){ ?>
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'compliances' && $uri3 == 'compliances_listing' && $uri4 == $service->id ) {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/compliances/compliances_listing?id=<?php echo ID_encode($service->id);?>" class="nav-link ">
									
                                        <span class="title"><?php echo ucwords($service->service_name);?></span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <?php } ?>

                                
                            </ul>
                        </li>
                        
                        <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'testing' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Testing</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                               <?php foreach($testingservices as $testingservice){   ?>
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'testing' && $uri3 == 'testing_list' && $uri4 == $testingservice->id ) {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/testing/testing_list?id=<?php echo ID_encode($testingservice->id);?>" class="nav-link ">
										<span class="title"><?php echo $testingservice->service_name; ?></span>
                                        <span class="title"></span>
                                    </a>
                                </li>
							   <?php } ?>
                               <!-- <li class="nav-item start">
                                    <a href="#" class="nav-link ">
										<span class="title">External PT</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="#" class="nav-link ">
										<span class="title">Internal PT</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="#" class="nav-link ">
										<span class="title">Internal VA</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="#" class="nav-link ">
										<span class="title">Application PT</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="#" class="nav-link ">
										<span class="title">Segmentation PT</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="#" class="nav-link ">
										<span class="title">Card Data Scan</span>
                                        <span class="title"></span>
                                    </a>
                                </li>-->
				 
                                
                            </ul>
                        </li>
			<li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'questionnaire' ) {echo 'active open';}?>">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Questionnaire</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                           
                            
                            <ul class="sub-menu">
                                
                                <?php foreach($services as $service){ ?>
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'questionnaire' && $uri3 == 'questionnaire_list' && $uri4 == $service->id ) {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/questionnaire/questionnaire_list?id=<?php echo ID_encode($service->id);?>" class="nav-link ">
									
                                        <span class="title"><?php echo ucwords($service->service_name);?></span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <?php } ?>
								
								<?php foreach($testingservices as $testingservice){ ?>
                                <li class="nav-item start <?php if ($uri1 == 'admin' && $uri2 == 'questionnaire' && $uri3 == 'questionnaire_list' && $uri4 == $testingservice->id ) {echo 'active';}?>">
                                    <a href="<?= base_url();?>admin/questionnaire/questionnaire_list?id=<?php echo ID_encode($testingservice->id);?>" class="nav-link ">
									
                                        <span class="title"><?php echo ucwords($testingservice->service_name);?></span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <?php } ?>
				 
                            </ul>
                        </li>
                        
                         <li class="nav-item start">
                            <a href="<?php echo base_url();?>admin/archive" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Archived Document</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        
                        <!--<li class="nav-item start">
                            <a href="<?php echo base_url();?>admin/report" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Reports</span>
                                <span class="selected"></span>
                            </a>
                        </li>-->
                       
<!--			<li class="nav-item start <?php //if ($uri1 == 'admin' && $uri2 == 'cms') {echo 'active';}?>">
                            <a href="<?= base_url();?>admin/cms" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Manage CMS</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        -->
                     <?php }else if($userData->user_type == 5){?>
                        
                        <li class="nav-item start <?php if ($uri1 == 'customer' && $uri2 == 'dashboard') {echo 'active';}?>">
                            <a href="<?= base_url();?>customer/dashboard" class="nav-link nav-toggle">
                                 <i class="icon-home"></i>
                                <span class="title">Dashboard</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        
                        <?php $process = _get_customer_process($userData->id);?>
                        
                        <li class="nav-item start active open">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Process</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                <?php if(isset($process) && !empty($process)){ ?>
                                <?php foreach($process as $process_name){ ?>
                                <li class="nav-item start <?php if ($uri1 == 'customer' && $uri2 == 'process' && $uri4 == $process_name->id) {echo 'active';}?>">
                                    <a href="<?= base_url();?>customer/process?process_id=<?php echo ID_encode($process_name->id);?>" class="nav-link ">
										<span class="title"><?php echo ucwords($process_name->process_name);?></span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php }else{ ?>
                                <?php $process = _get_sub_customer_process($userData->id);?>
                                <?php foreach($process as $process_name){ ?>
                                <li class="nav-item start <?php if ($uri1 == 'customer' && $uri2 == 'process' && $uri4 == $process_name->id) {echo 'active';}?>">
                                    <a href="<?= base_url();?>customer/process?process_id=<?php echo ID_encode($process_name->id);?>" class="nav-link ">
										<span class="title"><?php echo ucwords($process_name->process_name);?></span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php } ?>
				 
                                
                            </ul>
                        </li>
						<li class="nav-item start">
                            <a href="<?php echo base_url();?>customer/attestaions" class="nav-link nav-toggle">
                                <i class="icon-settings"></i>
                                <span class="title">Attestaions & Certifications</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        
                       <!-- <li class="nav-item start">
                            <a href="<?php echo base_url();?>customer/attestaions" class="nav-link nav-toggle">
                                <i class="icon-home"></i>
                                <span class="title">Attestaions & Certifications</span>
                                <span class="selected"></span>
                                <span class="arrow open"></span>
                            </a>
                            <ul class="sub-menu">
                                
                                <li class="nav-item start">
                                    <a href="javascript:;" class="nav-link ">
									
                                        <span class="title">2018</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="javascript:;" class="nav-link ">
									
                                        <span class="title">2017</span>
                                        <span class="title"></span>
                                    </a>
                                </li>
                                <li class="nav-item start">
                                    <a href="javascript:;" class="nav-link ">
									
                                        <span class="title">2016</span>
                                        <span class="title"></span>
                                    </a>
                                </li>

                                
                            </ul>
                        </li>-->
                       
                     <?php } ?>
			
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->