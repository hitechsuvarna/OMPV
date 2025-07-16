<style type="text/css">
	.purchase_table {
		width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        
	}

	@media only screen and (max-width: 760px) {
		.purchase_table {
			display: block;
        	overflow: auto;
		}
	}

	.purchase_table > thead > tr {
		box-shadow: 0px 5px 5px #ccc;
	}

	.purchase_table > thead > tr > th {
		padding: 10px;
	}

	.purchase_table > tbody > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tbody > tr > td {
		padding: 15px;
	}
</style>
<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--6-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="yr_code" name="yr_code" class="mdl-textfield__input" value="<?php if(isset($edit_fy)) { echo $edit_fy[0]->ify_year_code; } ?>">
						<label class="mdl-textfield__label" for="yr_code">Year Code</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="yr_from" name="yr_from" class="mdl-textfield__input" value="<?php if(isset($edit_fy)) { echo $edit_fy[0]->ify_start_date; } ?>">
						<label class="mdl-textfield__label" for="yr_from">Start Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="yr_to" name="yr_to" class="mdl-textfield__input" value="<?php if(isset($edit_fy)) { echo $edit_fy[0]->ify_end_date; } ?>">
						<label class="mdl-textfield__label" for="yr_to">End Date</label>
					</div>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--6-col">
			<table class="purchase_table">
				<thead>
					<tr>
						<th>Year Codes</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $t="";for ($i=0; $i < count($fy); $i++) { 
						$t.= '<tr><td>';
						if($fy[$i]->ify_active == "true") {
							$t.='<i class="material-icons" style="color:green;">check_circle</i>';
						}
						$t.= $fy[$i]->ify_year_code.'</td><td><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored choose" id="'.$fy[$i]->ify_id.'">Choose</button><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored edit" id="'.$fy[$i]->ify_id.'"><i class="material-icons">create</i></button></td></tr>';
					} echo $t;?>
					

				</tbody>
			</table>
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
		$('#yr_from').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        $('#yr_to').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        
		$('#submit').click(function(e) {
			e.preventDefault();
			$.post('<?php if(isset($edit_fy)) { echo base_url().$type."/Account/update_financial_year/".$fyid; } else { echo base_url().$type."/Account/update_financial_year/"; } ?>', {'cd' : $('#yr_code').val(), 'f' : $('#yr_from').val(), 't' : $('#yr_to').val() }, function(data, status, xhr) { window.location = "<?php echo base_url().$type.'/Account/manage_financial'; ?>" }, 'text');
		});

		$('.purchase_table').on('click','.edit', function(e) {
			e.preventDefault();
			window.location = "<?php echo base_url().$type.'/Account/manage_financial/'; ?>" + $(this).prop('id');
		});

		$('.purchase_table').on('click','.choose', function(e) {
			e.preventDefault();
			window.location = "<?php echo base_url().$type.'/Account/choose_financial/'; ?>" + $(this).prop('id');
		});


	});
</script>
</html>