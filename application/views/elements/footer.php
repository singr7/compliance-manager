<script type="text/javascript">
$(function() {
  $('body').on('keydown', '.remove_first_space', function(e) {
    console.log(this.value);
    if (e.which === 32 &&  e.target.selectionStart === 0) {
      return false;
    }  
  });
});

</script>
<a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
            <div class="page-quick-sidebar-wrapper hide" data-close-on-body-click="false">
                <div class="page-quick-sidebar">
                    
                    
                    <ul class="nav nav-tabs">
                         <li class="active">
                          <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Notifications
                                
                                <span class="badge badge-danger">0</span>
                            </a>
                        </li>
                        
                    </ul>
                   
                    
                    
                    <div class="tab-content">
                        
                        <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                            <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                                <ul class="feeds list-items">
                              
                               
                                <li>
                                        <div class="col1">
                                            
                                            <a href="#">
                                            
                                                <div class="cont">
                                                <div class="cont-col1 pull-left">
                                                    <div class="label label-sm label-info">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="cont-col2 pull-left">
                                                    <div class="desc">test
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        <div class="col2 pull-right">
                                            <div class="date"> 000000</div>
                                        </div>
                                    </li>
                               
                                    
                                    
                                    </ul>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> <?= date('Y');?> &copy; Panacea
                </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!-- BEGIN QUICK NAV -->
      
        <!-- END QUICK NAV -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<script src="../assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
<!--        <script src="<?= base_url()?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>-->
        <script src="<?= base_url()?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?= base_url()?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/horizontal-timeline/horizontal-timeline.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
<!--        <script src="<?= base_url()?>assets/global/scripts/app.min.js" type="text/javascript"></script>-->
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
<!--       <script src="<?= base_url()?>assets/pages/scripts/dashboard.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?= base_url()?>assets/layouts/layout4/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/layouts/layout4/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        
        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!--jAlert -->
       <script type="text/javascript" src="<?php echo base_url(); ?>assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <!-- Core files -->
        <script src="<?php echo base_url(); ?>assets/jquery-alert-dialogs/js/jquery.alerts.js" type="text/javascript"></script>
        <link href="<?php echo base_url(); ?>assets/jquery-alert-dialogs/css/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/global/plugins/select2/select2.css"/>

        <!--End jAlert -->
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>

    </body>

</html>