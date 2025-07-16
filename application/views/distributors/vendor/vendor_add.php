<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Vendor Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($edit_vendor)) { echo $edit_vendor[0]->ic_name; } ?>">
						<label class="mdl-textfield__label" for="c_name">Vendor Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_company" name="c_company" class="mdl-textfield__input" value="<?php if(isset($edit_vendor)) { echo $edit_vendor[0]->ic_company; } ?>">
						<label class="mdl-textfield__label" for="c_company">Contact Person</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_email" name="c_email" class="mdl-textfield__input" value="<?php if(isset($edit_vendor)) { echo $edit_vendor[0]->ic_email; } ?>">
						<label class="mdl-textfield__label" for="c_email">Email</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_phone" name="c_phone" class="mdl-textfield__input" value="<?php if(isset($edit_vendor)) { echo $edit_vendor[0]->ic_phone; } ?>">
						<label class="mdl-textfield__label" for="c_phone">Phone</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_address" name="c_address" class="mdl-textfield__input" value="<?php if(isset($edit_vendor)) { echo $edit_vendor[0]->ic_address; } ?>">
						<label class="mdl-textfield__label" for="c_address">Address</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_gst" name="c_gst" class="mdl-textfield__input" value="<?php if(isset($edit_vendor)) { echo $edit_vendor[0]->ic_gst_number; } ?>">
						<label class="mdl-textfield__label" for="c_gst">GST Number</label>
					</div>
					<?php if (isset($edit_vendor)) {
						echo '<div><button class="mdl-button mdl-js-button mdl-button--primary" id="delete"> DELETE </button></div>'; } ?>
					
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
		$('#submit').click(function(e) {e.preventDefault(); save_record(); });

		$('#delete').click(function(e) { e.preventDefault(); delete_record(); });
	});

	function delete_record() {
		$.post('<?php if (isset($edit_vendor)) { echo base_url().$type.'/Vendors/delete_vendor/'.$did; } else { echo "#"; } ?>', {}, function(d,s,x) { window.location = "<?php echo base_url().$type.'/Vendors'; ?>"}, "text");
	}

	function save_record() {
		$.post('<?php if(isset($edit_vendor)) { echo base_url().$type."/Vendors/update_vendor/".$did; } else { echo base_url().$type."/Vendors/save_vendor"; } ?>', {
			'name' : $('#c_name').val(),
			'company' : $('#c_company').val(),
			'email' : $('#c_email').val(),
			'phone' : $('#c_phone').val(),
			'address' : $('#c_address').val(),
			'gst' : $('#c_gst').val()
		}, function(data, status, xhr) {
			redirect();
		}, 'text');
	}


	
	function redirect() {
		window.location = "<?php echo base_url().$type.'/Vendors'; ?>";
	}
</script>
</html>