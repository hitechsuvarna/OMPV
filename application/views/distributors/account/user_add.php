<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">User Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_name; } ?>">
						<label class="mdl-textfield__label" for="c_name">Name</label>
					</div>
					<!-- <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_desig" name="c_desig" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_company; } ?>">
						<label class="mdl-textfield__label" for="c_desig">Designation</label>
					</div>
					 --><div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_email" name="c_email" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_email; } ?>">
						<label class="mdl-textfield__label" for="c_email">Email</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_phone" name="c_phone" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_phone; } ?>">
						<label class="mdl-textfield__label" for="c_phone">Phone</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_address" name="c_address" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_address; } ?>">
						<label class="mdl-textfield__label" for="c_address">Address</label>
					</div>
					<?php if(isset($user_info)) { 
					    if($id != $oid) {
					        echo '<div class="mdl-grid">';
							echo '<div class="mdl-cell mdl-cell--6-col"><a href="'.base_url().$type.'/Account/delete_user/'.$id.'"><button class="mdl-button mdl-button-upside mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accen"><i class="material-icons">delete</i> Delete User</button></a></div>';
							if ($user_info[0]->iu_status == "blocked") {
							    echo '<div class="mdl-cell mdl-cell--6-col"><a href="'.base_url().$type.'/Account/block_user/'.$id.'/1"><button class="mdl-button mdl-button--colored"><i class="material-icons">how_to_reg</i> Unblock User</button></a></div>';    
							} else {
							    echo '<div class="mdl-cell mdl-cell--6-col"><a href="'.base_url().$type.'/Account/block_user/'.$id.'/0"><button class="mdl-button mdl-button--colored"><i class="material-icons">block</i> Block User</button></a></div>';
							}
							echo '<div class="mdl-cell mdl-cell--6-col"><a href="'.base_url().$type.'/Account/logout_user/'.$id.'"><button class="mdl-button mdl-button--colored"><i class="material-icons">power_settings_new</i> Logout User</button></a></div>';
							echo '</div>';
					    }
					}
					?>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">User Access</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_product">
							<input type="checkbox" id="ch_product" class="mdl-switch__input" <?php if(isset($user_info)) if($user_info[0]->iua_u_products == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Product</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_pricing">
							<input type="checkbox" id="ch_pricing" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_pricing == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Pricing</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_dealers">
							<input type="checkbox" id="ch_dealers" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_dealers == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Dealers</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_vendors">
							<input type="checkbox" id="ch_vendors" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_vendors == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Vendors</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_orders">
							<input type="checkbox" id="ch_orders" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_orders == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Orders</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_delivery">
							<input type="checkbox" id="ch_delivery" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_delivery == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Delivery</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_inventory">
							<input type="checkbox" id="ch_inventory" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_inventory == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Inventory</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_godown">
							<input type="checkbox" id="ch_godown" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_godown == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Godown</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_purchase">
							<input type="checkbox" id="ch_purchase" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_purchase == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Purchase</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_purchase_pricing">
							<input type="checkbox" id="ch_purchase_pricing" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_purchase_pricing == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Purchase Pricing</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_invoice">
							<input type="checkbox" id="ch_invoice" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_invoice == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Invoice</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_credit_note">
							<input type="checkbox" id="ch_credit_note" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_credit_note == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Credit & Debit Note</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_ledgers">
							<input type="checkbox" id="ch_ledgers" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_ledgers == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Accounting</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_expense">
							<input type="checkbox" id="ch_expense" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_expenses == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Expenses</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_payments">
							<input type="checkbox" id="ch_payments" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_payments == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Payments</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_tax">
							<input type="checkbox" id="ch_tax" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_tax == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Taxes</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_users">
							<input type="checkbox" id="ch_users" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_tax == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Users</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_settings">
							<input type="checkbox" id="ch_settings" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_settings == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Settings</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_analyze">
							<input type="checkbox" id="ch_analyze" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_analyze == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Analyze</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_bank">
							<input type="checkbox" id="ch_bank" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_bank_accounts == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Bank Accounts</span>
						</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="ch_inventory_reset">
							<input type="checkbox" id="ch_inventory_reset" class="mdl-switch__input"  <?php if(isset($user_info)) if($user_info[0]->iua_u_inventory_reset == 'true') echo 'checked';  ?>>
							<span class="mdl-switch__label">Inventory Reset</span>
						</label>
					</div>
					
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">User Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
				    <div class="mdl-grid" style="margin:10px; border-radius:5px; border:1px solid #ccc;">
				        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
				            <h6>Upload Document 1</h6>
				        </div>
				        <div class="mdl-cell mdl-cell--6-col">
				            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        						<input type="file" name="attach_file" class="upload">
        					</div>
				        </div>
				        <div class="mdl-cell mdl-cell--6-col">
				            <?php if(isset($user_img[0])) { echo '<div><button class="mdl-button"><a href="'.base_url().'assets/uploads/'.$oid.'/users/'.$id.'/'.$user_img[0]->iui_img.'"><i class="material-icons">cloud_download</i> Download</a></button></div>'; }?>        
				        </div>
				    </div>
					<div class="mdl-grid" style="margin:10px; border-radius:5px; border:1px solid #ccc;">
				        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
				            <h6>Upload Document 2</h6>
				        </div>
				        <div class="mdl-cell mdl-cell--6-col">
				            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        						<input type="file" name="attach_file" class="upload">
        					</div>
				        </div>
				        <div class="mdl-cell mdl-cell--6-col">
				            <?php if(isset($user_img[1])) { echo '<div><button class="mdl-button"><a href="'.base_url().'assets/uploads/'.$oid.'/users/'.$id.'/'.$user_img[1]->iui_img.'"><i class="material-icons">cloud_download</i> Download</a></button></div>'; }?>        
				        </div>
				    </div>
					<div class="mdl-grid" style="margin:10px; border-radius:5px; border:1px solid #ccc;">
				        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
				            <h6>Upload Document 3</h6>
				        </div>
				        <div class="mdl-cell mdl-cell--6-col">
				            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        						<input type="file" name="attach_file" class="upload">
        					</div>
				        </div>
				        <div class="mdl-cell mdl-cell--6-col">
				            <?php if(isset($user_img[2])) { echo '<div><button class="mdl-button"><a href="'.base_url().'assets/uploads/'.$oid.'/users/'.$id.'/'.$user_img[2]->iui_img.'"><i class="material-icons">cloud_download</i> Download</a></button></div>'; }?>        
				        </div>
				    </div>
					
				</div>
			</div>
		</div>
		
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
		<div class="mdl-snackbar__text"></div>
		<button class="mdl-snackbar__action" type="button"></button>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('#submit').click(function(e) {
			e.preventDefault();
			$.post('<?php if(isset($user_info)) { echo base_url().$type."/Account/update_user/".$id; } else { echo base_url().$type."/Account/save_user/"; } ?>', {
				'name' : $('#c_name').val(),
				'email':$('#c_email').val(),
				'phone':$('#c_phone').val(),
				'address':$('#c_address').val(),
				'per_product':$('#ch_product')[0].checked,
				'per_pricing':$('#ch_pricing')[0].checked,
				'per_dealers':$('#ch_dealers')[0].checked,
				'per_vendors' : $('#ch_vendors')[0].checked,
				'per_orders':$('#ch_orders')[0].checked,
				'per_delivery':$('#ch_delivery')[0].checked,
				'per_inventory':$('#ch_inventory')[0].checked,
				'per_godown':$('#ch_godown')[0].checked,
				'per_purchase':$('#ch_purchase')[0].checked,
				'per_purchase_pricing':$('#ch_purchase_pricing')[0].checked,
				'per_invoice':$('#ch_invoice')[0].checked,
				'per_credit':$('#ch_credit_note')[0].checked,
				'per_ledgers':$('#ch_ledgers')[0].checked,
				'per_expense':$('#ch_expense')[0].checked,
				'per_payments':$('#ch_payments')[0].checked,
				'per_tax':$('#ch_tax')[0].checked,
				'per_users':$('#ch_users')[0].checked,
				'per_settings':$('#ch_settings')[0].checked,
				'per_analyze':$('#ch_analyze')[0].checked,
				'per_bank':$('#ch_bank')[0].checked,
				'per_inventory_reset':$('#ch_inventory_reset')[0].checked,
			}, function(data, status, xhr) {
				validate(data);
			}, 'text');
		});

	});
	
	function validate(id) {
	    if(id == "exists") {
	        var snackbarContainer = document.querySelector('#demo-snackbar-example');
	        var ert = {
				message: 'Email ID Already Exists.',timeout: 2000,
			};
			snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
	        
	    } else {
	        image_upload(id);
	    }
	}
	
	var datat = new FormData();
	function image_upload(id) {
		for(var i=0; i < $('.upload').length; i++) {
		    if($('.upload')[i].files[0]) {
		        console.log($('.upload')[i].files[0]);
    		    datat.append(i, $('.upload')[i].files[0]);
		    }
		}
		var url = "<?php echo base_url().$type.'/Account/image_upload/'; ?>" + id + "/" + 1;
		
		flnm = "";
		$.ajax({
			url: url, // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: datat, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				console.log("Recd: " + data);
				flnm = data.toString();
				$('.upload').val('');
				redirect();
			}
		});
		
	}
	
	function redirect() {
	    window.location = '<?php echo base_url().$type."/Account/users/"; ?>';
	}
</script>
</html>