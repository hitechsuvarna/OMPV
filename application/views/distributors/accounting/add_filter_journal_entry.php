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
	
	.purchase_table > tfoot  {
		box-shadow: 0px 5px 5px #ccc;
	}

	.purchase_table > tfoot > tr > th {
		padding: 10px;
	}
	
</style>
<div class="mdl-grid" style="width: 95% !important;">
	<div class="mdl-cell mdl-cell--12-col">
		<div class="mdl-card mdl-shadow--4dp">
			<div class="mdl-card__title">
				<h2 class="mdl-card__title-text">Entry Details</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<div class="mdl-grid">
				    <div class="mdl-cell mdl-cell--4-col">
				        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input type="text" id="j_from" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->account_from; ?>">
							<label class="mdl-textfield__label" for="j_from">From</label>
						</div>
				    </div>
				    <div class="mdl-cell mdl-cell--4-col">
				        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input type="text" id="j_to" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->account_to; ?>">
							<label class="mdl-textfield__label" for="j_to">To</label>
						</div>
				    </div>
				    <div class="mdl-cell mdl-cell--4-col">
				        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input type="text" id="j_date" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->date; ?>">
							<label class="mdl-textfield__label" for="j_date">Txn Date</label>
						</div>
				    </div>
				    <div class="mdl-cell mdl-cell--8-col">
				        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
							<input type="text" id="j_details" class="mdl-textfield__input" style="width: 100%;" value="<?php if(isset($detail)) echo $detail[0]->account_description; ?>">
							<label class="mdl-textfield__label" for="j_details">Details</label>
						</div>
				    </div>
				    <div class="mdl-cell mdl-cell--4-col">
				        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
							<input type="text" id="j_amt" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->amount; ?>">
							<label class="mdl-textfield__label" for="j_amt">Amount</label>
						</div>
				    </div>

				    <?php if (!isset($detail)) {
				    	echo '<div class="mdl-cell mdl-cell--8-col"></div>';
					    echo '<div class="mdl-cell mdl-cell--4-col"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect" id="j_add_entry" style="width: 100%;"><i class="material-icons">add</i> Add to List</button></div>';
					    echo '<div class="mdl-cell mdl-cell--12-col">
						        <table class="purchase_table" id="add_records">
									<thead>
										<tr>
											<th>Date</th>
											<th>From</th>
											<th>To</th>
											<th>Txn Details</th>
											<th>Amount</th>
										</tr>
									</thead>
									<tbody>
								    </tbody>
							    </table>
						    </div>';
				    } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<button class="lower-button mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit"><i class="material-icons">done</i></button>
<script>
    var records = [];
    var load_records = [];
    var selected_je = <?php if (isset($detail)) { echo $jid; } else { echo 0;} ?>;
	$(document).ready(function() {
	    $('#j_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    <?php if (!isset($detail)) {
	    	echo "var dt = new Date();var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();$('#j_date').val(s_dt);";
	    }?>
	    
	    
		
		
		var ledger_data = [];
    	<?php for ($i=0; $i < count($ledgers) ; $i++) { 
    			echo "ledger_data.push('".$ledgers[$i]->iacl_name."');";
    	} ?>
    	$( "#j_from" ).autocomplete({
            source: ledger_data
        });
        $( "#j_to" ).autocomplete({
            source: ledger_data
        });
        
		$('#j_add_entry').click(function(e) {
		    e.preventDefault();
		    records.push({ 'dt' : $('#j_date').val(), 'f' : $('#j_from').val(), 't' : $('#j_to').val(), 'd' : $('#j_details').val(), 'a' : $('#j_amt').val() , 'id' : selected_je });    
		    reset_fields(false);
		});
		
		function fill_table() {
		    $('#add_records > tbody').empty(); var a="";
		    for(var i=0;i<records.length;i++) {
		        a+='<tr id="' + i + '"><td>' + records[i].dt + '</td><td>' + records[i].f + '</td><td>' + records[i].t + '</td><td>' + records[i].d + '</td><td>' + records[i].a + '</td></tr>';
		    }
		    $('#add_records > tbody').append(a);
		}
		
		function reset_fields(from) {
		    if(from == true) {
		        $('#j_from').val('');
		        $('#add_records > tbody').empty();
		        records = [];
		        $('#j_from').focus();
		        selected_je = "";
		        display_entry();
		    } else {
		        $('#j_to').focus();
		    }
		    $('#j_to').val('');
		    $('#j_details').val('');
		    $('#j_amt').val('');
		    $('#mytags > .tagit-choice').remove();
		    fill_table();
		    
		}
		
		$('#submit').click(function(e) {
		    e.preventDefault();
		    $(this).attr('disabled');
		    if(selected_je != 0) {
		    	records.push({ 'dt' : $('#j_date').val(), 'f' : $('#j_from').val(), 't' : $('#j_to').val(), 'd' : $('#j_details').val(), 'a' : $('#j_amt').val() , 'id' : selected_je });    
		    
    		    $.post('<?php echo base_url().$type."/Accounting/record_journal_entry"; ?>', {
    		        'r' : records,
    		        'selected' : 'true'
    		    }, function(d,s,x) {
    		        redirect('<?php echo $ref; ?>');
    		    }, "text");
		    } else {
		        $.post('<?php echo base_url().$type."/Accounting/record_journal_entry"; ?>', {
    		        'r' : records,
    		        'selected' : 'false'
    		    }, function(d,s,x) {
    		        redirect('<?php echo $ref; ?>');
    		    }, "text");
		    }
		});

		function redirect(ref) {
			if (ref=="j") {
				window.location = "<?php echo base_url().$type.'/Accounting/journal_entries'; ?>";
			} else if(ref=="l") {
				window.location = "<?php echo base_url().$type.'/Accounting/ledger_details/'.$refid; ?>";
			} else if(ref=="g") {
				window.location = "<?php echo base_url().$type.'/Accounting/group_details/'.$refid; ?>";
			} else if(ref=="tr") {
				window.location = "<?php echo base_url().$type.'/Accounting/trial_balance/'.$refid; ?>";
			}

		}
	});
</script>
