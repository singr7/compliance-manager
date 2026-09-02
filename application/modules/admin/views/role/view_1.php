<style>
    .role_name ul{ margin: 0; padding:0;}
    .role_name ul li{ list-style: none; float: left; color: #444; font-weight:bold; font-size: 14px; padding-left: 30px;}
    .role_name ul li span{ font-weight: normal;}
</style>
<?php
$userData = $this->session->userdata("userinfo");
// pr($permAssigned); die;   
?>
<div class="page-container">
    <div class="page-content">
        <div class="container">
            <div class="row margin-top-10">
                <div class="col-md-12"> 
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption"> Assign Permission
                                <div class="role_name pull-right"> 
                                    <ul>
                                        
                                                                                                                  
                                       
                                        <li>Department: <span> <?php echo $role_data->department; ?>  </span></li>                                    
                                    </ul> 
                                </div> 
                                <?php if (!empty($this->session->flashdata('alert'))) {
                                    ?>
                                    <div class="alert alert-success ">
                                        <button class="close" data-dismiss="alert"></button>
                                        <span id="danger_msg">
                                            <?php //$sess = $this->session->flashdata('alert');
                                            //echo $sess['m']; 
                                            ?>
                                        </span>
                                    </div>
<?php } ?>

                            </div>                            
                        </div>
                       
<?php echo form_open("", array("method" => "post", "onSubmit" => "return validiate_perm()")); ?>
                        <div class="portlet-body">
                            <div class="cleafix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="supplier">
                                        <!--                                        <h3 class="sub-head"> Permission </h3>-->
                                        <div class="table-scrollable permission">                                           
                                            <table class="table  table-bordered table-hover ">

                                                <tbody>
                                                    <tr>
                                                        <th width="28%">
<!--                                                                Select/Deselect All<input id="selectall" onclick="selectAll(this)" value="1" type="checkbox">-->
                                                        </th>                                                           
                                                        <th width="12%" style="text-align:center !important;" align="center"> View </th>
                                                        <th width="12%" style="text-align:center !important;" align="center"> Add </th>
                                                        <th width="12%" style="text-align:center !important;" align="center"> Edit </th>
                                                        <th width="12%" style="text-align:center !important;" align="center"> Download </th>
                                                        <th width="12%" style="text-align:center !important;" align="center"> Accept </th>
                                                        <th width="12%" style="text-align:center !important;" align="center"> Assign Permission </th>
                                                    </tr>
                                                    <?php
                                                    //foreach ($modules as $mod) {
                                                        //$permActionIds = array();
                                                        //if (!empty($permAssigned) && array_key_exists($mod->id, $permAssigned)) {
                                                            //$permActionIds = $permAssigned[$mod->id];
                                                        //}
                                                       // $method = isset($mod->method) && !empty($mod->method) ? explode(',', $mod->method) : array();
                                                        ?>                                                        
                                                        <tr class="sub-heading" id="tr<?php// echo $mod->id; ?>">
                                                            <td width="28%"> Cost Controll Department<?php //echo $mod->submodule; ?>  </td>
                                                            <td width="12%" style="text-align:center !important;">
                                                                <?php
                                                               // if (in_array('1', $method)) {
                                                                    ?>
                                                                    <input name="view[<?php //echo $mod->id; ?>]" value="1" <?php //echo in_array("1", $permActionIds) ? "checked" : ""; ?> type="checkbox" id="rv_<?php //echo $mod->id; ?>" class="rv ckd_prm">
                                                                <?php
                                                               // } else {
                                                                    ?>
                                                                    <img src="<?php // echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                                                <?php// } ?>
                                                            </td>
                                                            <td width="12%" style="text-align:center !important;">
                                                                <?php
                                                                //if (in_array('2', $method)) {
                                                                    ?>
                                                                    <input name="add[<?php // echo $mod->id; ?>]" value="2" <?php // echo in_array("2", $permActionIds) ? "checked" : ""; ?> type="checkbox" id="ra_<?php //echo $mod->id; ?>" class="red ckd_prm">
                                                                <?php
                                                                //} else {
                                                                    ?>
                                                                    <img src="<?php // echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                                                <?php// } ?>
                                                            </td>
                                                            <td width="12%" style="text-align:center !important;">
                                                                <?php
                                                               // if (in_array('3', $method)) {
                                                                    ?>
                                                                    <input name="edit[<?php // echo $mod->id; ?>]" value="3" <?php //echo in_array("3", $permActionIds) ? "checked" : ""; ?>  type="checkbox"  id="re_<?php //echo $mod->id; ?>" class="red ckd_prm">
                                                                <?php
                                                               // } else {
                                                                    ?>
                                                                    <img src="<?php //echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                                                <?php// } ?>
                                                            </td>
                                                            <td width="12%" style="text-align:center !important;">
                                                                <?php
                                                               // if (in_array('4', $method)) {
                                                                    ?>
                                                                    <input name="delete[<?php //echo $mod->id; ?>]" value="4" <?php // echo in_array("4", $permActionIds) ? "checked" : ""; ?>  type="checkbox" id="rd_<?php // echo $mod->id; ?>" class="red ckd_prm" >
                                                                <?php
                                                                //} else {
                                                                    ?>
                                                                    <img src="<?php// echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                                                <?php// } ?>
                                                            </td>
                                                            <td width="12%" style="text-align:center !important;">
                                                                <?php
                                                                //if (in_array('5', $method)) {
                                                                    ?>
                                                                    <input name="export[<?php //echo $mod->id; ?>]" value="5" <?php //echo in_array("5", $permActionIds) ? "checked" : ""; ?>  type="checkbox" id="rex_<?php //echo $mod->id; ?>" class="red ckd_prm" >
                                                                <?php
                                                                //} else {
                                                                    ?>
                                                                    <img src="<?php //echo base_url(); ?>assets/global/img/close.png" class="close-size">
                                                                <?php// } ?>
                                                            </td>
                                                            <td width="12%" style="text-align:center !important;">
                                                                <?php
                                                                //if (in_array('6', $method)) {
                                                                    ?>
                                                                    <input name="permission[<?php //echo $mod->id; ?>]" value="6" <?php //echo in_array("6", $permActionIds) ? "checked" : ""; ?>  type="checkbox" id="rp_<?php //echo $mod->id; ?>" class="red ckd_prm" >
                                                                <?php
                                                                //} else {
                                                                    ?>
                                                                    <img src="<?php //echo base_url(); ?>assets/global/img/close.png" class="close-size" />
                                                                <?php //} ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    //}
                                                    ?>                                                        
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="">
                                    <div class="form-action top_border margin-top-20">

                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn green" name="submit" ><i class="fa fa-save"></i> Apply</button>                                        
                                            <?php if ($this->uri->segment('4') == 'role') { ?>
                                                <a href="<?php echo base_url("role/list_items"); ?>"><button type="button" class="btn default"><i class="fa fa-ban"></i> Cancel</button> </a>
                                            <?php } else if ($this->uri->segment('4') == 'user') { ?>
                                                <a href="<?php echo base_url("staff/list_items"); ?>"><button type="button" class="btn default"><i class="fa fa-ban"></i> Cancel</button> </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    function selectAll(theObj) {
        if ($(theObj).prop("checked") == true) {
            $("input[type=checkbox]").each(function () {
                $(this).attr("checked", true);
                $(this).parent("span").addClass("checked");
            });
        } else {
            $("input[type=checkbox]").each(function () {
                $(this).attr("checked", false);
                $(this).parent("span").removeClass("checked");
            });
        }
    }
    $(document).ready(function () {

        $(".red").on("change", function () {
            var oid = $(this).attr("id");
            var d = oid.split('_')[1];
            if ($(this).prop("checked") == true) {
                $("#rv_" + d).attr("checked", true);
                $("#rv_" + d).parent("span").addClass("checked");
            }
        });

        $(".rv").on("change", function () {
            var oid = $(this).attr("id");
            var d = oid.split('_')[1];
            if ($(this).prop("checked") == false) {
                if ($("#re_" + d).prop("checked") == true || $("#rd_" + d).prop("checked") == true || $("#ra_" + d).prop("checked") == true || $("#rex_" + d).prop("checked") == true || $("#rp_" + d).prop("checked") == true) {
                    var c = confirm("This will remove All exist Permissions");
                    if (c) {
                        $("#re_" + d).attr("checked", false);
                        $("#re_" + d).parent("span").removeClass("checked");
                        $("#rd_" + d).attr("checked", false);
                        $("#rd_" + d).parent("span").removeClass("checked");
                        $("#ra_" + d).attr("checked", false);
                        $("#ra_" + d).parent("span").removeClass("checked");
                        $("#rex_" + d).attr("checked", false);
                        $("#rex_" + d).parent("span").removeClass("checked");
                        $("#rp_" + d).attr("checked", false);
                        $("#rp_" + d).parent("span").removeClass("checked");

                    } else {
                        $("#rv_" + d).attr("checked", true);
                        $("#rv_" + d).parent("span").addClass("checked");
                    }
                }
            }
        });

        $('#role').change(function () {
            $('.none').hide();
            $('#' + $(this).val()).show();
        });

    });
    function validiate_perm()
    {
        if ($('.ckd_prm:checked').length < 1)
        {
            alert('Please select atleast one module');
            return false;
        }
    }
</script>
