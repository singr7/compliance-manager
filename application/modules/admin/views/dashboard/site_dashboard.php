<style>
    .dashboard-stat2 {
        padding: 15px 15px 10px;
    }
    .dashboard-stat2 .display .number small {
        height: 38px;
        display: block;
    }
</style>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <a href="<?php echo base_url();?>admin/qsa"><div class="display">
                                    <div class="number">
                                        <h3 class="font-green-sharp">
                                            <span data-counter="counterup" data-value="<?php if(isset($total_qsa) && !empty($total_qsa)){ echo $total_qsa;}else{ echo '0'; };?>"></span>
                                            <!--<small class="font-green-sharp">$</small>-->
                                        </h3>
                                        <small>Total QSAs</small>
                                    </div>
                                    <div class="icon">
                                       <i class="icon-user"></i>
                                    </div>
                                    </div></a>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <a href="<?php echo base_url();?>admin/qas"><div class="display">
                                    <div class="number">
                                        
                                       
                                        <h3 class="font-red-haze">
                                            <span data-counter="counterup" data-value="<?php if(isset($total_qa) && !empty($total_qa)){ echo $total_qa;}else{ echo '0'; };?>"></span>
                                        </h3>
                                         
                                        <small>Total QAs</small>
                                         
                                        
                                    </div>
                                    <div class="icon">
                                         <i class="icon-user"></i>
                                    </div>
                                </div></a>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <a href="<?php echo base_url();?>admin/consultants"><div class="display">
                                    <div class="number">
                                        
                                       
                                        <h3 class="font-blue-sharp">
                                            <span data-counter="counterup" data-value="<?php if(isset($total_consultants) && !empty($total_consultants)){ echo $total_consultants;}else{ echo '0'; };?>"></span>
                                        </h3>
                                        <small>Total Consultants</small>
                                        
                                    </div>
                                    <div class="icon">
                                         <i class="icon-user"></i>
                                    </div>
                                </div></a>
                                
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="dashboard-stat2 bordered">
                                <a href="<?php echo base_url();?>admin/customer"><div class="display">
                                    <div class="number">
                                        
                                        <h3 class="font-purple-soft">
                                            <span data-counter="counterup" data-value="<?php if(isset($total_customer) && !empty($total_customer)){ echo $total_customer;}else{ echo '0'; };?>"></span>
                                        </h3>
                                        <small>Total Customers</small>
                                        
                                        
                                    </div>
                                    <div class="icon">
                                         <i class="icon-user"></i>
                                    </div>
                                    </div></a>
                                
                            </div>
                        </div>
                    </div>               