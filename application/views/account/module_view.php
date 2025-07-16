<main class="mdl-layout__content">
	<div class="mdl-grid" style="margin-bottom: 60px;">
		<!-- GENERAL DETAILS -->
		<?php 
			echo '<table  class="mdl-data-table mdl-js-data-table mdl-shadow--2dp"><thead><tr>';
			for ($i=0; $i < count($module_column) ; $i++) { 
				echo '<th class="mdl-data-table__cell--non-numeric mdl-data-table__header--sorted-descending">'.$module_column[$i]->icemc_column.'</th>';
			}
			echo "</thead></tr>";
			
			for ($i=0; $i < count($module_table) ; $i++) { 
				$tmpid = $module[0]->icem_col_prefix."_id";
				echo "<tr id='".$module_table[$i]->$tmpid."' class='rec'>";

				for ($j=0; $j < count($module_column) ; $j++) { 
					$wer = $module[0]->icem_col_prefix.$module_column[$j]->icemc_column;
					echo '<td mdl-data-table__cell--non-numeric">'.$module_table[$i]->$wer.'</th>';
				}
				echo "</tr>";
			}
			echo "</table>";
		?>
	</div>
	<a href="<?php echo base_url().'Modx/application_generate/'.$mid.'/add'; ?>">
	<button class="lower-button mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent">
		<i class="material-icons">add</i>
	</button>
	</a>
</main>
</div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('table').on('click', '.rec', function(e) {
			e.preventDefault();
			var tid = $(this).prop('id');
			window.location = '<?php echo base_url()."ModX/application_generate/".$mid."/edit/"; ?>'+tid;
		});
	});
</script>
</html>