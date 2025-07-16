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
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="sub_start" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->it_date; } ?>">
						<label class="mdl-textfield__label" for="sub_start">Subscription Start</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="sub_end" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->it_date; } ?>">
						<label class="mdl-textfield__label" for="sub_end">Subscription End</label>
					</div>
					
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
		$('#sub_start').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
		$('#sub_end').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
		<?php 
			if(!isset($edit_txn)) {
				echo "var dt = new Date();";
				echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
				echo "$('#sub_start').val(s_dt);";
		
				echo "dt = new Date();";
				echo "s_dt = (dt.getFullYear() + 1)  + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
				
				echo "$('#sub_end').val(s_dt);";
			}
		?>
		

		$('#submit').click(function(e) {
			e.preventDefault();
			
			$.post('<?php echo base_url().$type."/Portal/save_distributor/"; ?>', {'name' : $('#c_name').val(), 'company' : $('#c_company').val(), 'email' : $('#c_email').val(), 'phone' : $('#c_phone').val(), 'address' : $('#c_address').val(), 'gst' : $('#c_gst').val(), 'start' : $('#sub_start').val(), 'end' : $('#sub_end').val()}, function(data, status, xhr) { redirect() }, 'text');
		});

	});

	function redirect() {
		// window.location = '<?php echo base_url().$type."/Portal/list_all/"; ?>';
	}
</script>
</html>