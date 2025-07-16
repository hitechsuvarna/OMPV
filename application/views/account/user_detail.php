<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Customer Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_name; } ?>">
						<label class="mdl-textfield__label" for="c_name">Customer Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_company" name="c_company" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_company; } ?>">
						<label class="mdl-textfield__label" for="c_company">Company Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_email" name="c_email" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_email; } ?>">
						<label class="mdl-textfield__label" for="c_email">Email</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_phone" name="c_phone" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_phone; } ?>">
						<label class="mdl-textfield__label" for="c_phone">Phone</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_address" name="c_address" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_address; } ?>">
						<label class="mdl-textfield__label" for="c_address">Address</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_gst" name="c_gst" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_gst; } ?>">
						<label class="mdl-textfield__label" for="c_gst">GST Number</label>
					</div>
					
					<?php if(isset($user_info)) { 
							echo '<a href="';
							echo base_url().'Account/reset_password/'.$uid;
							echo '"><button class="mdl-button mdl-button-upside mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Regenerate Password</button></a>';					
						}
					?>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-grid" style="padding: 0px;margin: 0px;">
				<div class="mdl-cell mdl-cell--12-col" style="margin: 0px;">
					<div class="mdl-card mdl-shadow--4dp">
						<div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Subscription</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="mdl-cell mdl-cell--12-col" style="text-align: center;margin: 0px;">
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="c_active" >
										<input type="checkbox" id="c_active" class="mdl-switch__input" readonly="true" <?php if (isset($user_info)) { if ($user_info[0]->i_status== "true") {echo "checked"; } } else { echo "checked";} ?>>
										<span class="mdl-switch__label">Subscription Status
											<?php if (isset($user_info)) {
												if ($user_info[0]->i_status!= "true") {
													echo "<br><b style='color:white;background-color:red;padding:5px;'>".$user_info[0]->i_status."</b>";
												}
											} ?></span>
									</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" id="c_sub_start" name="c_sub_start" class="mdl-textfield__input" readonly="true" value="<?php if(isset($user_info)) { echo $user_info[0]->i_subscription_start; } ?>">
									<label class="mdl-textfield__label" for="c_sub_start">Subscription Start Date (YYYY-MM-DD)</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" id="c_duration" name="c_duration" class="mdl-textfield__input" readonly="true" value="<?php if(isset($user_info)) { echo $user_info[0]->i_duration; } ?>">
									<label class="mdl-textfield__label" for="c_duration">Duration (days)</label>
								</div>
							</div>			
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-grid" style="padding: 0px;margin: 0px;">
				<div class="mdl-cell mdl-cell--12-col" style="margin: 0px;">
					<div class="mdl-card mdl-shadow--4dp">
						<div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Your Logo</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="mdl-cell mdl-cell--12-col">
								<b>Select your logo</b>
								<input type="file" name="attach_file" class="upload">
								<hr>
								<img src="<?php echo $logo; ?>" style="width: 100%;">
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
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

			<?php 
				echo "$.post('".base_url()."Account/update_details/".$uid."', {'name' : name, 'company' : company, 'email' : email, 'phone' : phone, 'address' : address, 'gst' : gst}, function(data, status, xhr) {}, 'text');";
			?>

			var datat = new FormData();
			if($('.upload')[0].files[0]) {
				datat.append("use", $('.upload')[0].files[0]);
				
				flnm = "";
				$.ajax({
					url: "<?php echo base_url().'Account/logo_upload/'.$uid; ?>", // Url to which the request is send
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
						window.location = '<?php echo base_url()."Account/details/"; ?>' + data
					}
				});
			} else {
				window.location = '<?php echo base_url()."Account/details/".$uid; ?>';
			}

		});

	});
</script>
</html>