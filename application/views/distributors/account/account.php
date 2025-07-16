<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Customer Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_name; } ?>">
						<label class="mdl-textfield__label" for="c_name">Customer Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_company" name="c_company" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_company; } ?>">
						<label class="mdl-textfield__label" for="c_company">Company Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
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
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_gst" name="c_gst" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->ic_gst_number; } ?>">
						<label class="mdl-textfield__label" for="c_gst">GST Number</label>
					</div>
					<?php if($section == "Distributor") 
						echo '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="c_bank" name="c_bank" class="mdl-textfield__input" value="';
						if(isset($user_info)) echo $user_info[0]->ic_bank_name;
						echo '"> <label class="mdl-textfield__label" for="c_bank">Bank Name</label> </div> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="c_branch" name="c_branch" class="mdl-textfield__input" value="';
						if(isset($user_info)) echo $user_info[0]->ic_bank_branch; 
						echo '"> <label class="mdl-textfield__label" for="c_branch">Branch</label> </div> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="c_acc" name="c_acc" class="mdl-textfield__input" value="';
						if(isset($user_info)) echo $user_info[0]->ic_bank_accno; 
						echo '"> <label class="mdl-textfield__label" for="c_acc">Account Number</label> </div> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="c_ifsc" name="c_ifsc" class="mdl-textfield__input" value="';
						if(isset($user_info)) echo $user_info[0]->ic_bank_ifsc;
						echo '"> <label class="mdl-textfield__label" for="c_ifsc">IFSC</label> </div>';
						
						echo '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <p style="margin: 10px;text-align: left;">Upload Company Logo</p><br> <input type="file" name="attach_file" class="upload"> <img src="'.base_url().'assets/uploads/'.$oid.'/logo/'.$logo.'" id="p_image" style="width: 100%;"></div>';
                        echo '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <p style="margin: 10px;text-align: left;">Upload Company Secondary Logo</p><br> <input type="file" name="attach_file" class="upload_sec"> <img src="'.base_url().'assets/uploads/'.$oid.'/logo/'.$logo_sec.'" id="p_image" style="width: 100%;"></div>';
                        
						if(isset($users)) { 
							echo '<a href="';
							echo base_url().$type.'/Account/reset_password/'.$oid.'/'.$rid;
							echo '"><button class="mdl-button mdl-button-upside mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Regenerate Password</button></a>';					
						}
					?>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col"></div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('#submit').click(function(e) {
			e.preventDefault();
			
			var name = $('#c_name').val();
			var company = $('#c_company').val();
			var email = $('#c_email').val();
			var phone = $('#c_phone').val();
			var address = $('#c_address').val();
			var gst = $('#c_gst').val();

			$.post('<?php echo base_url().$type."/Account/update_details/".$rid; ?>', {'name' : name, 'company' : company, 'email' : email, 'phone' : phone, 'address' : address, 'gst' : gst, 'bank' : $('#c_bank').val(), 'branch' : $('#c_branch').val(), 'acc' : $('#c_acc').val(), 'ifsc' : $('#c_ifsc').val() }, function(data, status, xhr) { upload_image(); }, 'text');
		});

	});

	function upload_image() {
		var datat = new FormData();
		if($('.upload')[0].files[0]) {
			datat.append("use", $('.upload')[0].files[0]);
			
			flnm = "";
			$.ajax({
				url: "<?php echo base_url().$type.'/Account/logo_upload/'.$rid; ?>", // Url to which the request is send
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
					upload_secondary_image();
				}
			});
		} else {
			upload_secondary_image();
		}
	}
	
	function upload_secondary_image() {
		var datat = new FormData();
		if($('.upload_sec')[0].files[0]) {
			datat.append("use", $('.upload_sec')[0].files[0]);
			
			flnm = "";
			$.ajax({
				url: "<?php echo base_url().$type.'/Account/logo_upload_secondary/'.$rid; ?>", // Url to which the request is send
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
		} else {
			redirect();
		}
	}
	
	

	function redirect() {
		window.location = '<?php echo base_url().$type."/Account/account"; ?>';
	}
</script>
</html>