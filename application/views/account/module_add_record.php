<main class="mdl-layout__content">
	<div class="mdl-grid" style="margin-bottom: 60px;">
		<!-- GENERAL DETAILS -->
		<?php 
			$flg = "1";
			$skp = false;
			for ($i=0; $i < count($module_column) ; $i++) { 
				if($flg=="1") {
					echo '<div class="mdl-cell mdl-cell--2-col"></div>';
				}
				
				$col_name = $module[0]->icem_col_prefix.$module_column[$i]->icemc_column;
				echo '<div class="mdl-cell mdl-cell--4-col">';
				echo '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">';
				echo '<input type="text" id="'.$module_column[$i]->icemc_column.'" name="column[]" class="mdl-textfield__input" value="';
				if(isset($edit_module_table)) {
					echo $edit_module_table[0]->$col_name;
				}
				echo '">';
				echo '<label class="mdl-textfield__label" for="'.$module_column[$i]->icemc_column.'">'.$module_column[$i]->icemc_column.'</label>';
				echo '</div>';
				echo '</div>';
				
				if($flg=="0") {
					echo '<div class="mdl-cell mdl-cell--2-col"></div>';
				}

				if($flg=="1") {
					$flg="0";
				} else if($flg=="0") {
					$flg="1";
				}
			}
		?>
	</div>
	<div class="mdl-grid" style="">
		<?php
			if(isset($edit_module_table)) {
				echo '<div class="mdl-cell mdl-cell--4-col"></div>';
				echo '<div class="mdl-cell mdl-cell--4-col" style="text-align:center;"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="delete" style="width:100%;">Delete Record</button></div>';
				echo '<div class="mdl-cell mdl-cell--4-col"></div>';
			}
		?>
	</div>
	<button class="lower-button mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">done</i>
	</button>
</main>
</div>
</body>
<script>
	$(document).ready(function(){

		$('#submit').click(function(e) {
			e.preventDefault();

			var c_new_val = [];
			$("input[name^='column'").each(function(){
				c_new_val.push({'i': $(this).prop('id'), 'v' : $(this).val()}); 
			});

			console.log(c_new_val);

			<?php 

				if(isset($edit_module_table)) {
					echo '$.post("'.base_url().'ModX/application_update/'.$mid.'/'.$recid.'", { "mod_val" : c_new_val }, function(data, status, xhr) { window.location = "'.base_url().'ModX/application_generate/'.$mid.'/view"; }, "text");';
				} else {
					echo '$.post("'.base_url().'ModX/application_save/'.$mid.'", { "mod_val" : c_new_val }, function(data, status, xhr) { window.location = "'.base_url().'ModX/application_generate/'.$mid.'/view"; }, "text");';	
				}
				

			?>
		});

		$('#delete').click(function(e) {
			e.preventDefault()

			<?php 
				echo '$.post("'.base_url().'ModX/application_record_delete/'.$mid.'/'.$recid.'", { }, function(data, status, xhr) { window.location = "'.base_url().'ModX/application_generate/'.$mid.'/view"; }, "text");';
			?>
		});

	});
</script>
</html>