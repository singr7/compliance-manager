<div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="processid" name="process_id">
                                                    <option value="">Please select Process</option>
                                                    <?php foreach($customr_process as $process){ ?>
                                                    <option value="<?php echo $process->id;?>"><?php echo $process->process_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="form_control_1">Select Process &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="processid-error" class="error" for="processid" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
<script>
    $('select[name="process_id"]').select2();
    </script>