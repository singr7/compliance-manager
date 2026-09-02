<?php $this->load->view('elements/header') ?>
<?php $this->load->view('elements/left_panel') ?>



<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
<!--                <h1>Dashboard
                    <small>statistics, charts, recent events and reports</small>
                </h1>-->
            </div>
            <!-- END PAGE TITLE -->
            <!-- BEGIN PAGE TOOLBAR -->

            <!-- END PAGE TOOLBAR -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">

            <?php
            if (@$breadcum != '') {
                foreach (@$breadcum as $b_key => $b_val) {
                    if ($b_key != '') {
                        ?>
                        <li class=""> <a href="<?= base_url() . $b_key ?>"><?= $b_val ?></a>
                            <i class="fa fa-circle"></i>
                        </li>
                    <?php } else if ($b_key == '') { ?>
                        <li class="active"> <?= $b_val ?> </li>
                        <?php
                        }
                    }
                }
                ?>

        </ul>
        <!-- END PAGE BREADCRUMB -->
        <?php $this->load->view($page) ?>
    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<!-- BEGIN QUICK SIDEBAR -->




<?php $this->load->view('elements/footer') ?>