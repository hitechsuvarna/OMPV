<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Tax Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_name" name="p_name" class="mdl-textfield__input" value="<?php if(isset($edit_tax)) { echo $edit_tax[0]->itx_name; } ?>">
						<label class="mdl-textfield__label" for="p_name">Enter Tax Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_percent" name="p_percent" class="mdl-textfield__input" value="<?php if(isset($edit_tax)) { echo $edit_tax[0]->itx_percent; } ?>">
						<label class="mdl-textfield__label" for="p_percent">Enter Tax Percent</label>
					</div>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col"></div>
	</div>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
		<?php if(isset($edit_tax)) {
			echo "<a href='".base_url().$type.'/Products/delete_tax/'.$tid."'";
			echo '<button class="mdl-button mdl-button-upside mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Delete Tax</button>';
			echo "</a>";
		}?>
		</div>
		<div class="mdl-cell mdl-cell--4-col"></div>
	</div>
	<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">done</i>
	</button>
</main>
</div>
</body>
<script>
	$(document).ready(function() {
		$('#p_name').focus();	

		$('#submit').click(function(e) {
			e.preventDefault();

			var tax_name = $('#p_name').val();
			var tax_percent = $('#p_percent').val();

			$.post('<?php if (isset($edit_tax)) { echo base_url().$type."/Products/update_tax/".$tid; } else { echo base_url().$type."/Products/save_tax"; } ?>', {
				'name' : tax_name,
				'percent' : tax_percent
			}, function(data, status, xhr) {
				window.location = '<?php echo base_url().$type."/Products/tax"; ?>';
			}, 'text');

		});
	});
</script>
</html>