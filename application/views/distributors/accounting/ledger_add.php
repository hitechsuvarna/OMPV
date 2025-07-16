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
					<h2 class="mdl-card__title-text">Ledger Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->ledger_name; ?>">
						<label class="mdl-textfield__label" for="vendors">Ledger Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="type" class="mdl-textfield__input">
						    <option value="0">Select</option>
						    <option value="contact" <?php if(isset($detail)) if($detail[0]->type == 'contact') echo 'selected'; ?>>Dealers / Vendors</option>
						    <option value="tax" <?php if(isset($detail)) if($detail[0]->type == 'tax') echo 'selected'; ?>>Taxes</option>
						    <option value="module" <?php if(isset($detail)) if($detail[0]->type == 'module') echo 'selected'; ?>>Modules</option>
						</select>
						<label class="mdl-textfield__label" for="type">Select Type</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="entity" class="mdl-textfield__input" value="<?php if(isset($detail)) if($detail[0]->type == 'contact') { echo $detail[0]->contact; } else  if($detail[0]->type == 'tax') { echo $detail[0]->tax; } else  if($detail[0]->type == 'module') { echo $detail[0]->module; } ?>">
						<label class="mdl-textfield__label" for="entity">Search to Link</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        				<input type="text" id="mygroups" class="mdl-textfield__input" value="<?php if(isset($detail)) if($detail[0]->grp != null) echo $detail[0]->grp; ?>">
        				<label class="mdl-textfield__label" for="mygroups">Part of a Group</label>
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
	var tag_data = [], tag2_data =[], prop_data = [], cnt=0, type_data=[], sel_type="";
	$(document).ready(function() {
		<?php if (isset($detail_prop)) { echo "cnt=".count($detail_prop);} ?>

		<?php for ($i=0; $i < count($prop) ; $i++) { 
    			echo "prop_data.push('".$prop[$i]->inald_property."');";
    	} ?>
    	$('#del').click(function(e) {
    		e.preventDefault();
    		window.location = "<?php if (isset($detail)) echo base_url().$type.'/Accounting/delete_ledger/'.$lid; ?>";
    	});
    	
        <?php for($i=0;$i<count($groups);$i++) { echo 'tag_data.push("'.$groups[$i]->iacg_name.'");'; } ?>
        $( "#mygroups" ).autocomplete({
            source: tag_data
        });

        
        
        $('#type').change(function(e) {
            sel_type=$(this).val();
            $('#entity').autocomplete({ 
                source : '<?php echo base_url().$type."/Accounting/fetch_link_type/"; ?>' + sel_type
            });
        })

        $('#add_prop').click(function(e) {
        	e.preventDefault();
        	cnt++;
        	$('.prop').append('<div class="mdl-cell mdl-cell--12-col"><input type="text" id="p' + cnt + '" class="mdl-textfield__input prop_title" placeholder="Property"><input type="text" id="v' + cnt + '" class="mdl-textfield__input prop_value" placeholder="Value"></div>');
        	$('#p'+cnt).autocomplete({
	            source: prop_data
	        }).focus();

        });
    	
    	$('#submit').click(function(e) {
			e.preventDefault();
			$(this).attr('disabled','disabled');
			
			prop_t_arr = [];
		    $('.prop_title').each(function(index) {
				prop_t_arr.push($(this).val());
			});

			prop_v_arr = [];
		    $('.prop_value').each(function(index) {
				prop_v_arr.push($(this).val());
			});



			$.post('<?php if(isset($detail)) { echo base_url().$type."/Accounting/save_ledger/".$lid; } else { echo base_url().$type."/Accounting/save_ledger/"; } ?>', {
			    'name': $('#vendors').val(),
			    'groups' : $('#mygroups').val(),
			    'link_type' : $('#type').val(),
			    'link_name' : $("#entity").val(),
			    'p_t' : prop_t_arr,
			    'p_v' : prop_v_arr
			 }, function(d,s,x) {
			 	window.location = "<?php echo base_url().$type.'/Accounting/ledgers'; ?>";
			 }, "text");
	    }); 
	});
</script>
</html>