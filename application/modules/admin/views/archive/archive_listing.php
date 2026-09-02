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
.pwd12 > input{
    padding-right: 20px;
    width: auto;
}
.pwd12{
    position:relative;
}
.pwd12 > a{
    position: absolute;
    right: 1px;
    top: 1px;
    color: #fff;
    background-color: #0c5d80;
    padding: 2px 2px;
}
</style>
<div id='msgShow'></div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <span class="caption-subject bold uppercase"> Archived List</span>
                </div>
                
                    
            </div>
            
          
               
         
                <div class="portlet-body">
                
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="datatable">
                    <thead>
                        <tr>
                            <th> S.No. </th>
                            <th> Customer Name </th>
                            <th> QSA  </th>
                            <th> Consultant  </th>
                            <th> QA  </th>
                            <th> Process  </th>
                            <th> Start Date </th>
                            <th> End Date </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                       
                        <?php if(isset($archived_data) && !empty($archived_data)){  ?>
                        <?php $i=1; foreach($archived_data as $archived){ ?>
                        <tr class="odd gradeX">
                                    
                                    <td class="center"><?php echo $i;?></td>
                                    <td class="center"><?php echo $archived->CustomerName;?></td>
                                    <td class="center"><?php echo $archived->QSAName;?></td>
                                     <td class="center"><?php echo $archived->ConsultantName;?></td>
                                    <td class="center"><?php echo $archived->QaName;?></td>
                                    <td class="center"><a href="<?= base_url();?>admin/customer/view_process?id=<?php echo ID_encode($archived->process_id);?>"><?php echo $archived->ProcessName;?></a></td>
                                    <td class="center"><?php echo $archived->start_date;?></td>
                                     <td class="center"><?php echo $archived->end_date;?></td>
                                 </tr>
                        <?php $i++; } ?>
                        <?php } ?>


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
<!-- Button trigger modal -->

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
                                    "pageLength": 100,
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