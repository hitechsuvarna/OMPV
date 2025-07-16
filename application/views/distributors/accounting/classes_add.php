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

	.cart_table_details {
		width: 100%;
	}
	.amount {
		text-align: right;
	}

	#order_number {
		color: #ff0000;
	}

	#total_amount {
		color: #ff0000;
	}

	.product_qty {
        border: 1px solid #999;
        border-radius: 3px;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
        width: 70px;
    }
    
    #print_data {
        display : none;
    }
    
    .detail_center {
        text-align:center;
    }
    
    .detail_right {
        text-align:right;
    }

    ::placeholder {
    	color: #999 !important;
	}
</style>


<main class="mdl-layout__content" style="display: non;">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Class Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->iacc_name; ?>">
						<label class="mdl-textfield__label" for="vendors">Class Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					    <select id="type" class="mdl-textfield__input">
					        <option value="credit" <?php if(isset($detail)) if($detail[0]->iacc_type=="credit") echo 'selected'; ?>>Credit</option>
					        <option value="debit" <?php if(isset($detail)) if($detail[0]->iacc_type=="debit") echo 'selected'; ?>>Debit</option>
					    </select>
						<label class="mdl-textfield__label" for="vendors">Class Type</label>
					</div>
					
					<?php if (isset($detail)) echo '<button class="mdl-button mdl-js-button mdl-button--colored" id="del"><i class="material-icons">delete</i> DELETE</button>'; ?>
				</div>

			</div>
		</div>
	</div>
	<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">done</i>
	</button>
</div>
</div>

</body>
<script>
	var tag_data = [], prop_data = [], cnt=0;
	$(document).ready(function() {

    	$('#del').click(function(e) {
    		e.preventDefault();
    		window.location = "<?php if (isset($detail)) echo base_url().$type.'/Accounting/delete_classes/'.$cid; ?>";
    	});
        
    	
    	$('#submit').click(function(e) {
			e.preventDefault();
			$(this).attr('disabled','disabled');
			
			$.post('<?php if(isset($detail)) { echo base_url().$type."/Accounting/save_classes/".$cid; } else { echo base_url().$type."/Accounting/save_classes/"; } ?>', {
			    'name': $('#vendors').val(),
			    'type' : $('#type').val()
			 }, function(d,s,x) {
			 	window.location = "<?php echo base_url().$type.'/Accounting/classes'; ?>";
			 }, "text");
	    }); 
	});
</script>
</html>