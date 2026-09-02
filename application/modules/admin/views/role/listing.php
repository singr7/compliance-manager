				
<?php echo get_flashdata(); ?>
<div class="row">                   

    <div id="msgShow"></div>
    <div class="col-md-12 col-sm-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                                        <span class="caption-subject bold uppercase"> Department Listing </span>
                                    </div>
<!--                <div class="actions">
                                         <div class="btn-group">
                                             <a href="#" id="sample_editable_1_new" class="btn sbold green">Add New<i class="fa fa-plus"></i> </a>
                                                </div>
                                    </div>-->
            </div>
            <div class="portlet-body">
                <form method="get"  id="filter_id">
                    <div class="hide">
                        <div class="row custom-height-top">
                            <div class="form-group-custom">
                                <div class="col-md-4">
                                    <label>Status</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="select-style">
                                        <select name="status" class="custom_filter " id="id-status" >														
                                            <option value="active" <?php echo @$_GET['status'] == 'active' ? "selected" : ""; ?>>Active</option>
                                            <option value="inactive" <?php echo @$_GET['status'] == 'inactive' ? "selected" : ""; ?>>Inactive</option>
                                            <option value="delete" <?php echo @$_GET['status'] == 'delete' ? "selected" : ""; ?>>Delete</option>														
                                        </select>
                                    </div>
                                </div>                                      
                            </div>
                        </div>
                    </div>
                </form>


                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th class="table-checkbox hide">
                                <input type="checkbox" class="group-checkable" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/>
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                S.No.
                            </th>
                            <th width="9%" align="center" style="text-align: center;">
                                Department
                            </th>
                            
                            <th width="9%" align="center" style="text-align: center;">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($department_listing) && !empty($department_listing)) {
                           $i=1;  foreach ($department_listing as $role_key => $dept_val) {

                                ?>
                                <tr class="odd gradeX">
                                   
                                   <td class="hide"><?php if (in_array($dept_val->id, $selected)) { ?>
                                            <input type="checkbox" name="selected[]" class="checkboxes" value="<?php echo $dept_val->id; ?>" checked="checked"/>
                                        <?php } else { ?>
                                            <input type="checkbox" name="selected[]" class="checkboxes" value="<?php echo $dept_val->id; ?>"/>
                                        <?php } ?>
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                         <?= $i;?>
                                    </td>
                                    <td width="9%" align="center" style="text-align: center;">
                                        <?php echo ucwords($dept_val->department); ?>
                                    </td>
                                    
                                    <td width="9%" align="center" style="text-align: center;">
                                         <a href="<?= base_url();?>admin/role/view_role?id=<?= ID_encode($dept_val->id) ?>">
                                                                    <i class="icon-eye"></i>  </a>
                                                </td>

                                </tr>
                        <?php $i++; } } ?>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>                
</div>

<!-- END PAGE CONTENT-->
</div>
</div>
<script type="text/javascript">
    function deleteDepartment(id){
       var r=confirm("Do you want to delete this?")
        if (r==true)
          window.location = url+"admin/department/delete?id="+id;
        else
          return false;
        } 

    $(".custom_filter").on("change", function () {
        var status = $(this).val();
        if (status == 'none')
        {

            window.location.href = "<?php echo base_url(); ?>admin/department";
        } else
        {
            $("#filter_id").submit();
        }
    });

    function restoreData(arr) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/department/restoreData',
            type: 'POST',
            data: {arr: arr},
            dataType: 'JSON',
            success: function (res) {
                console.log(res);
                if (res.status == 'success') {
                    window.location.reload();
                } else {
                    alert(res.message);
                }
            }
        });
    }

</script>

<script>
    var TableManaged = function () {
        var initTable2 = function () {
            var table = $('#datatable');
            table.dataTable({
                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                                        "infoEmpty": "No entries found",
                                        "infoFiltered": "(filtered1 from _MAX_ total entries)",
                                        "lengthMenu": "Show _MENU_ entries",
                                        "search": "Search",
                                        "zeroRecords": "No Matching Records Found",
                                        "lengthMenu": " _MENU_ Record",
                                                "paging": {
                                                    "previous": "Prev",
                                                    "next": "Next"
                                                }
                                    },
                                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
                                    // So when dropdowns used the scrollable div should be removed. 
                                    //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

                                    "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.

                                    "lengthMenu": [
                                        [20, 50, 100],
                                        [20, 50, 100] // change per page values here
                                    ],
                                    // set the initial value
                                    "pageLength": 50,
                                    "columnDefs": [{// set default column settings
                                            'orderable': false,
                                            'targets': [0, 2, 3]
                                        }, {
                                            "searchable": false,
                                            "targets": [0]
                                        }],
                                    // "order": [
                                    //    [1, "asc"]
                                    //] // set first column as a default sort by asc
                                "ordering": false,
                                        columnDefs: [{  orderable: false, targets:  "no-sort"}]
                                });

                                var tableWrapper = jQuery('#datatable_wrapper');

                                table.find('.group-checkable').change(function () {
                                    var set = jQuery(this).attr("data-set");
                                    var checked = jQuery(this).is(":checked");
                                    jQuery(set).each(function () {
                                        if (checked) {
                                            $(this).attr("checked", true);
                                        } else {
                                            $(this).attr("checked", false);
                                        }
                                    });
                                    jQuery.uniform.update(set);
                                });

                                tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
                            }



                            return {
                                //main function to initiate the module
                                init: function () {
                                    if (!jQuery().dataTable) {
                                        return;
                                    }
                                    initTable2();
                                    $(".form-group-custom").removeClass('hide');
                                }

                            };

                        }();
                        jQuery(document).ready(function () {
                            setTimeout(function () {
                                TableManaged.init();
                                $(".table-checkbox").removeClass('sorting_asc');
                            }, 500);

                        });
</script>