<style type="text/css">
	.ond-card-name {
		box-shadow: 1px 1px 2px #999!important;
		border-radius: 2px!important;
		/*width: 100%;*/
		padding: 10px;
	}
</style>

<main class="mdl-layout__content">
	<div class="mdl-grid">
		<!-- GENERAL DETAILS -->
		<?php 
			for ($i=0; $i < count($tax_group) ; $i++) { 
				echo '<div class="ond-card-name mdl-cell mdl-cell--2-col" id="'.$tax_group[$i]->ittxg_id.'"><span class="mdl-list__item-primary-content"><i class="material-icons mdl-list__item-icon">star</i>'.$tax_group[$i]->ittxg_group_name.'</span></div>';
			}
		?>
	</div>
	<a href="<?php echo base_url().$type.'/Products/tax_group_add'; ?>">
	<button class="lower-button mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent">
		<i class="material-icons">add</i>
	</button>
	</a>
</main>
</div>
</body>

<script type="text/javascript">
	$(document).ready(function() {

		$('.ond-card-name').click(function(e) {
			e.preventDefault();
			window.location = "<?php echo base_url().$type.'/Products/tax_group_edit/'; ?>" + $(this).prop('id');

		})
	})
</script>
</html>