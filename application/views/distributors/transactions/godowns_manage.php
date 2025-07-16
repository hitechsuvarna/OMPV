<style type="text/css">
	.order_table {
		width: 100%;
		border: none;
		padding: 10px;
		border: 1px solid #999;
	}

	.order_table > tbody > tr {
		width: 100%;
	}

	.order_table > tbody > tr > td {
		border-bottom: 1px solid #999;
		padding: 10px;
		width: 100%;
	}

	.cart_table_details {
		width: 100%;
	}
	.amount {
		text-align: right;
	}

	.order_number {
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
</style>

<main class="mdl-layout__content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__supporting-text" style="text-align:left;">
                    <h4>Create a new godown</h4>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:100%;">
        				<input type="text" id="godown" class="mdl-textfield__input" value="<?php if(isset($edit_godown)) { echo $edit_godown[0]->ig_godown_name; } ?>">
        				<label class="mdl-textfield__label" for="godown">Enter Godown Name</label>
        			</div>
        			<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="save_godown">
                        Save Godown
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="mdl-grid" >
		<div class="mdl-cell mdl-cell--12-col">
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%;" id="godown_table">
                <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric" style="width:auto;">Action</th>
                        <th class="mdl-data-table__cell--non-numeric">Godown</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric" style="width:auto;"><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete"><i class="material-icons">delete</i></button></td>
                        <td class="mdl-data-table__cell--non-numeric">Godown 1</td>
                    </tr>
                </tbody>
            </table>
		</div>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
	    $('#save_godown').click(function(e) {
	        e.preventDefault();
	        
	        $.post('<?php if(isset($edit_godown)) { echo base_url().$type."/Transactions/update_godown/".$gid; } else { echo base_url().$type."/Transactions/save_godown"; } ?>', { 'godown' : $('#godown').val() }, function(d,s,x) { $('#godown').val(''); load_godowns(); }, "text"); 
	    });
	    
	    $('#godown_table').on('click','.edit', function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Transactions/edit_godown/'; ?>" + $(this).prop('id');
	    });
	    
	    $('#godown_table').on('click','.delete', function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Transactions/delete_godown/'; ?>" + $(this).prop('id');
	    });
	    
	});
	
	load_godowns();
	
	function load_godowns() {
	    $('#godown_table > tbody').empty();
	    $.post('<?php echo base_url().$type.'/Transactions/load_godowns'; ?>',
	        {}, 
	        function(d,s,x) { 
	            var a=JSON.parse(d), o="";
	            for(var i=0;i<a.length;i++) {
	                o+='<tr id="' + a[i].ig_id + '"><td class="mdl-data-table__cell--non-numeric" style="width:auto;"><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + a[i].ig_id + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + a[i].ig_id + '"><i class="material-icons">delete</i></button></td><td class="mdl-data-table__cell--non-numeric">' + a[i].ig_godown_name + '</td></tr>'; 
	            }
	            $('#godown_table > tbody').append(o);
	        }, "text");
	}
	
	
</script>
</html>