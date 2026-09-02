<style>
    .list-group{
        z-index:10;display:none; 
        position:absolute; 
        color:red;
        width: 100%;
        box-shadow: 0px 6px 22px #4f606a30;
    }
    .list-group .list-group-item{border:0px;}
    .msg{
        position:absolute; 
        color:red;
    }

</style>
<div class="row">
                        <div class="col-md-8 ">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <h3>Add <?php echo $service_name->service_name;?> Project(compliance)</h3>
                                <?php echo get_flashdata(); ?>
                                <div class="portlet-body form">
                                    <form role="form" action="" method="POST" id="project">
                                        <div class="form-body">
                                            <input type="hidden" name="service_id" value="<?php echo ID_encode($service_name->id);?>">
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="customer_id" name="customer_id" onchange="get_process()">
                                                    <option value="">Please select customer</option>
                                                    <?php foreach($customer_list as $customer){ ?>
                                                    <option value="<?php echo $customer->id;?>"><?php echo $customer->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="customer_id">Select Customer &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="customer_id-error" class="error" for="customer_id" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            <div id="process">
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="processid" name="process_id">
                                                    <option value="">Please select Process</option>
                                                    
                                                  </select>
                                                <label for="processid">Select Process &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="processid-error" class="error" for="processid" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="qsa" name="qsa_id">
                                                    <option value="">Please select QSA</option>
                                                   <?php foreach($qsa_list as $qsa){ ?>
                                                    <option value="<?php echo $qsa->id;?>"><?php echo $qsa->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="qsa">Select QSA &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="qsa-error" class="error" for="qsa" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="consultants" name="consultant_id">
                                                    <option value="">Please select Consultant</option>
                                                    <?php foreach($consultant_list as $consultant){ ?>
                                                    <option value="<?php echo $consultant->id;?>"><?php echo $consultant->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="consultants">Select Consultant &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="consultants-error" class="error" for="consultants" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            <div class="form-group form-md-line-input has-info">
                                                <select class="form-control remove_first_space" id="qa" name="qa_id">
                                                    <option value="">Please select QA</option>
                                                   <?php foreach($qa_list as $qa){ ?>
                                                    <option value="<?php echo $qa->id;?>"><?php echo $qa->full_name;?></option>
                                                    <?php } ?>
                                                  </select>
                                                <label for="qa">Select QA &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="qa-error" class="error" for="qa" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                            
                                            
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <input type="text" class="form-control remove_first_space" id="star_date" name="start_date" placeholder="Enter start date" readonly="true" >
                                                <label for="star_date">Start Date &nbsp;<span style='color:red;'>*</span></label>
                                                <label id="star_date-error" class="error" for="star_date" style="margin-top: 56px; color:#c8202d"></label>
                                            </div>
                                            
                                            
                                              
                                        </div>
                                        <div class="form-actions noborder">
                                            <button type="submit" class="btn blue">Submit</button>
                                            <button type="button" class="btn default cancel-button">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $('.cancel-button').on('click', function() {
                url = '<?php echo base_url(); ?>admin/compliances/compliances_listing?id=<?php echo ID_encode($service_name->id);?>';	
                location = url;
        });
        
$( "#star_date" ).datepicker({
     dateFormat: 'yy-m-d',
     startDate: '+0d',
      //minDate: '0',
 });

function get_process(){
    var customerid = $("#customer_id").val();
    $.ajax({
        url:'<?php echo base_url(); ?>'+'admin/compliances/get_customer_process',
        data:{
            customerid:customerid,
        },
        type:"Post",
    success:function(dat){
     $("#process").empty().html(dat);
    }
    });
}



$("#project").validate({
	
        rules: {
		
                customer_id:{
                        required:true,
                        
                },
                
                process_id: {
                 required: true,
                 
                },
		
                qsa_id: {
                 required: true,
		
                },
                consultant_id: {
                 required: true,
		
                },
                qa_id: {
                 required: true,
                 
                },
                start_date: {
                 required: true,
		
                },
               
        },       	              
        messages: {
		
                customer_id: {
                         required:'Please select customer',
                         
                },
                
		
                process_id: {
                        required:'Please select process',
                        
                },
		
                qsa_id: {
                        required:'Please select QSA',
			
                },
		
                consultant_id: {
                        required:'Please select consultants',
			
                },
                qa_id: {
                        required:'Please select QA',
			
                },
                start_date: {
                        required:'Please select start date',
			
                },
               
                
            }
});


</script>
<script>
	$( function() {
		$.widget( "custom.combobox", {
			_create: function() {
				this.wrapper = $( "<span>" )
					.addClass( "custom-combobox" )
					.insertAfter( this.element );

				this.element.hide();
				this._createAutocomplete();
				this._createShowAllButton();
			},

			_createAutocomplete: function() {
				var selected = this.element.children( ":selected" ),
					value = selected.val() ? selected.text() : "";

				this.input = $( "<input>" )
					.appendTo( this.wrapper )
					.val( value )
					.attr( "title", "" )
					.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: $.proxy( this, "_source" )
					})
					.tooltip({
						classes: {
							"ui-tooltip": "ui-state-highlight"
						}
					});

				this._on( this.input, {
					autocompleteselect: function( event, ui ) {
						ui.item.option.selected = true;
						this._trigger( "select", event, {
							item: ui.item.option
						});
					},

					autocompletechange: "_removeIfInvalid"
				});
			},

			_createShowAllButton: function() {
				var input = this.input,
					wasOpen = false;

				$( "<a>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All Items" )
					.tooltip()
					.appendTo( this.wrapper )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "custom-combobox-toggle ui-corner-right" )
					.on( "mousedown", function() {
						wasOpen = input.autocomplete( "widget" ).is( ":visible" );
					})
					.on( "click", function() {
						input.trigger( "focus" );

						// Close if already visible
						if ( wasOpen ) {
							return;
						}

						// Pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
					});
			},

			_source: function( request, response ) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				response( this.element.children( "option" ).map(function() {
					var text = $( this ).text();
					if ( this.value && ( !request.term || matcher.test(text) ) )
						return {
							label: text,
							value: text,
							option: this
						};
				}) );
			},

			_removeIfInvalid: function( event, ui ) {

				// Selected an item, nothing to do
				if ( ui.item ) {
					return;
				}

				// Search for a match (case-insensitive)
				var value = this.input.val(),
					valueLowerCase = value.toLowerCase(),
					valid = false;
				this.element.children( "option" ).each(function() {
					if ( $( this ).text().toLowerCase() === valueLowerCase ) {
						this.selected = valid = true;
						return false;
					}
				});

				// Found a match, nothing to do
				if ( valid ) {
					return;
				}

				// Remove invalid value
				this.input
					.val( "" )
					.attr( "title", value + " didn't match any item" )
					.tooltip( "open" );
				this.element.val( "" );
				this._delay(function() {
					this.input.tooltip( "close" ).attr( "title", "" );
				}, 2500 );
				this.input.autocomplete( "instance" ).term = "";
			},

			_destroy: function() {
				this.wrapper.remove();
				this.element.show();
			}
		});

		$( "#combobox" ).combobox();
		$( "#toggle" ).on( "click", function() {
			$( "#combobox" ).toggle();
		});
	} );
	</script>
       