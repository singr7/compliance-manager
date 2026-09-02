<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Panacea</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #4 for " name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= base_url() ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?= base_url() ?>assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?= base_url() ?>assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?= base_url() ?>assets/pages/css/login-2.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <?php
    $fs_email = '';
    $fs_password = '';
    $fs_remember = '';
    if (get_cookie('email', false) != null) {
        $fs_email = get_cookie('email', false);
    }

    if (get_cookie('password', false) != null) {
        $fs_password = get_cookie('password', false);
    }

    if (get_cookie('email', false) != null) {
        $fs_remember = get_cookie('remember', false);
    }

    $fs_email_decr = custom_encryption($fs_email, 'ak!@#s$on!', 'decrypt');
    $fs_password_decr = custom_encryption($fs_password, 'ak!@#s$on!', 'decrypt');
    ?>



    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="javascript:void(0);">
                <img src="<?= base_url() ?>assets/pages/img/logo.png" alt="" /> 
                
            </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form  class="login-form" id="login-form1" action="" method="post" >
                <div class="form-title">
    <!--                    <span class="form-title">Welcome.</span>-->
                    <span class="form-subtitle">Please login</span>
                </div>
                <?php echo get_flashdata(); ?>
                
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="email" value="<?php if (@$fs_remember && @$fs_email_decr != '') {
                    echo @$fs_email_decr;
                } ?>" /> </div>
                <span class="help-block"><?php echo form_error('email'); ?></span>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" value="<?php if (@$fs_remember && @$fs_password_decr != '') {
                    echo @$fs_password_decr;
                } ?>" /> </div>
                <span class="help-block"><?php echo form_error('password'); ?></span>
                <div class="form-actions">
                    <button type="submit" class="btn red btn-block uppercase">Login</button>
                </div>
                <div class="create-account">
                    <p>
                        <a href="javascript:;"  id="register-btn">Click here</a> to install certificate
                    </p>
                </div>  
                <div class="form-actions">
                    <div class="pull-left">
                        <label class="rememberme mt-checkbox mt-checkbox-outline">
                            <input  type="checkbox" name="remember" value="1" <?php if (@$fs_remember) {
                    echo "checked";
                } ?>/>Remember Me
                            <span></span>
                        </label>
                    </div>
                    
                    
                    <div class="pull-right forget-password-block">
                        <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                    </div>
                    
                </div>
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="forget-form" id="forgot" method="post">
                <div class="form-title">
                    <span class="form-title">Forgot Password ?</span>
                    <span class="form-subtitle">Enter your e-mail to reset it.</span>
                </div>
                <div id="msgShow"></div>
                <div class="form-group">
                    <input class="form-control placeholder-no-fix" type="text" id="forgotemail" autocomplete="off" placeholder="Email" name="email" /> </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn btn-default">Back</button>
                    <button type="button" onclick="forgot()" class="btn btn-primary uppercase pull-right" id="forget"><span class="processing hide"><i class="fa fa-spin fa-spinner" style="color:#fff"></i></span>&nbsp;&nbsp;Submit</button>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
            
            
            
            <!-- BEGIN REGISTRATION FORM -->
            <form class="register-form" action="upload_certificate" method="post" enctype="multipart/form-data" >
                <div class="form-title">
                    <span class="form-title">Install Certificate</span>
                </div>
                <p class="hint">Please select your certificate below:</p>
                
                
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Select Certificate</label>
                    <input class="form-control placeholder-no-fix" type="file" autocomplete="off" id="register_password" placeholder="certificate" name="certificate" /> </div>
                
                
                <div class="form-actions">
                    <button type="button" id="register-back-btn" class="btn btn-default">Back</button>
                    <button type="submit" id="register-submit-btn" class="btn red uppercase pull-right">Submit</button>
                </div>
            </form>
            <!-- END REGISTRATION FORM -->
            
        </div>
        <div class="copyright hide"> <?= date('Y'); ?> ï¿½ Kooheji. </div>
        <!-- END LOGIN -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<script src="../assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?= base_url() ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?= base_url() ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="<?= base_url() ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?= base_url() ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?= base_url() ?>assets/pages/scripts/login.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
                        $(document).ready(function ()
                        {
                            $('#clickmewow').click(function ()
                            {
                                $('#radio1003').attr('checked', 'checked');
                            });
                        })
        </script>
        <script>
            jQuery(document).ready(function () {
                Metronic.init(); // init metronic core components
                Layout.init(); // init current layout
                Login.init();
                Demo.init();
                setTimeout(function () {
                    $("#errorMsg").hide();
                }, 4000);
            });



            $("#login-form1").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    },
                },
                messages: {
                    email: {
                        required: '<span class="help-block" style="color:#e73d4a;">Please enter your username!</span>',
                        email: '<span class="help-block" style="color:#e73d4a;">Please enter valid username!</span>'
                    },
                    password: {
                        required: '<span class="help-block" style="color:#e73d4a;">Password is required!</span>'
                    },
                }
            });

            function forgot() {
                $(".forget-form").find('button').find('.processing').removeClass('hide');
                $(".forget-form").find('button').prop('disabled', true);
                var email = $("#forgotemail").val();

                if (email == '') {
                    $('#msgShow').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;Email is required<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    $(".forget-form").find('button').find('.processing').addClass('hide');
                    $(".forget-form").find('button').prop('disabled', false);
                    return false;
                }
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/auth/forgot",
                    type: "POST",
                    data: {email: email},
                    success: function (dat)
                    {
                        dat = JSON.parse(dat);
                        if (dat['status'] == "error") {
                            $(".forget-form").find('button').find('.processing').addClass('hide');
                            $(".forget-form").find('button').prop('disabled', false);
                            $('#msgShow').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;' + dat['msg'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                            $('#msgShowResult').html(dat['result']);


                        } else if (dat['status'] == "success") {
                            window.location.reload();
                        }
                    }
                });
            }
        </script>
    </body>

</html>