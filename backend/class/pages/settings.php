<?php


$users = <<<HTML
    <div class="section__content section__content--p30">
	<div class="container-fluid">
	    <div class="row">
    		<div class="col-lg-12">
    			<div class="card">
    				<div class="card-header">
    					<strong>Add Staff Member</strong>
    				</div>
        			<div class="card-body card-block">
        				<div class="row">
    						<div class="col-6">
    							<form action="" id="add-user" method="post" class="form-horizontal">
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="name" class=" form-control-label">First name</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="text" id="name" name="name" placeholder="Enter first name" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="email" class=" form-control-label">Email</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="email" id="email" name="email" placeholder="Enter email" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="password" class=" form-control-label">Password</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="password" id="password" name="password" placeholder="Enter a password" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="password-ver" class=" form-control-label"></label>
    								</div>
    								<div class="col col-md-6">
    									<input type="password" id="password-ver" name="password-ver" placeholder="Re-enter password" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="rate" class=" form-control-label">Hourly rate</label>
    								</div>
    								<div class="col col-md-6">
    									<input type="text" id="rate" name="rate" placeholder="$/hr" class="form-control">
    								</div>
    							</div>
    							<div class="row form-group">
    								<div class="col col-md-3">
    									<label for="rate" class=" form-control-label">Access</label>
    								</div>
    								<div class="col col-md-6">
    									<select name="access" id="access" class="form-control">
    										<option value="0">Access</option>
    										<option value="1">Staff</option>
    										<option value="2">Supervisor</option>
    										<option value="3">Admin</option>
    									</select>
    								</div>
    							</div>
    						</form>
        					</div>
    						<div class="col-6">
        						<div class="row form-group">
        							<div class="col col-lg-2">
        							    <label for="availability" form-control-label">Availabilities</label>
        							</div>
        							<div class="col col-lg-8" style="margin-top: -1.8%;">
        							    <div class="table-responsive">
                                            <table id="availability" class="table table-borderless">
                                                <tbody>
                                                    **_TABLE_**
                                                </tbody>
                                            </table>
                                        </div>
        							</div>
        						</div>
        					</div>
    					</div>
    				</div>
    				<div class="card-footer">
    				    <div class="col-12" >
    				        <div class="row" style="display: flex; align-items: center;">
    				            <div class="col-lg-4" id="staffFormStatus" style="margin-top: 1%;"></div>
				                <div class="col-lg-8">
    				                <div style="float:right;">
    						            <button form="add-user" type="submit" class="btn btn-primary btn-sm">Submit</button>
    						        </div>
    						    </div>
				            </div>
    					</div>
					</div>
    			</div>
    		</div>
    	</div>
    	<div class="row">
        	<div class="col-lg-6">
        		<div class="card">
        			<div class="card-header">
        				<strong>Staff Settings</strong>
        			</div>
        			<div class="card-body card-block">
        				<div class="table-responsive table-data">
        					<table class="table">
        						<thead>
                                	<tr>  
                                		<td>Name</td>
                                		<td>Role</td>
                                		<td>Action</td>
                                	</tr>
                                </thead>
                                </tbody>
        					</table>
        				</div>
        			</div>
        			<div class="card-footer" style="text-align:right;">
    					<button form="add-user" type="submit" class="btn btn-primary btn-sm">
    						Submit
    					</button>
    				</div>
        		</div>
        	</div>
        </div>
	</div>
 </div>
 <script>
    $(document).ready(function(){
        var avail = {"monday": {}, "tuesday": {}, "wednesday": {}, "thursday": {}, "friday": {}, "saturday": {}, "sunday": {}};
        $("select[name='Start'], select[name='Finish']").on("change", function() {
            if (this.id == "access") {
                //chillin
            } else {
                var day = $(this).closest('tr').attr('id');
                if (day == "monday") {
                    avail.monday[this.id] = this.value;
                } else if (day == "tuesday") {
                    avail.tuesday[this.id] = this.value;
                } else if (day == "wednesday") {
                    avail.wednesday[this.id] = this.value;
                } else if (day == "thursday") {
                    avail.thursday[this.id] = this.value;
                } else if (day == "friday") {
                    avail.friday[this.id] = this.value;
                } else if (day == "saturday") {
                    avail.saturday[this.id] = this.value;
                } else if (day == "sunday") {
                    avail.sunday[this.id] = this.value;
                } else {
                    console.log('some form of issue lmao cya');
                }
            }
        });
        $("#add-user").submit(function(e) {
            e.preventDefault();
            var data = $('#add-user').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            $.ajax({
                type: 'POST',
                url: 'backend/ajax/settingsfunc.php',
                dataType: 'json',
                data:
                    {
                        message: "ADD_USER",
                        data: JSON.stringify(data)
                    },
                success: function(result){}, 
                error: function(){}
            });
        });
        $(document).ready(function() {
            $('.js-select2').select2({
                minimumResultsForSearch: -1
            });
        });
    });
 </script>
HTML;


?>