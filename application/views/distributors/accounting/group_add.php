<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
</style>


<main class="mdl-layout__content" style="display: non;">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Groups Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($detail)) echo $detail[0]->iacg_name; ?>">
						<label class="mdl-textfield__label" for="vendors">Group Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        				<input type="text" id="mygroups" class="mdl-textfield__input" value="<?php if(isset($detail)) if($detail[0]->parent_name != null) echo $detail[0]->parent_name; ?>">
        				<label class="mdl-textfield__label" for="mygroups">Part of a Group</label>
        			</div>
        			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        				<input type="text" id="myclasses" class="mdl-textfield__input" value="<?php if(isset($detail)) if($detail[0]->class_name != null) echo $detail[0]->class_name; ?>">
        				<label class="mdl-textfield__label" for="myclasses">Part of Class</label>
        			</div>
				</div>
				<div  class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
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
    $(document).ready(function(e) {
        
        var tag_data = [];
		<?php for($i=0;$i<count($groups);$i++) { echo 'tag_data.push("'.$groups[$i]->iacg_name.'");'; } ?>
        $( "#mygroups" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(tag_data, request.term);
                response(results.slice(0, 10));
            },
            select : function(e, u) {
                $.post('<?php echo base_url().$type."/Accounting/fetch_group_classes"; ?>', {
                    'g': u.item.value
                }, function(d,s,x) {
                    $('#myclasses').val(d);
                })
            }
        });
        
		var tag_data2 = [];
		<?php for($i=0;$i<count($classes);$i++) { echo 'tag_data2.push("'.$classes[$i]->iacc_name.'");'; } ?>
        $( "#myclasses" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(tag_data2, request.term);
                response(results.slice(0, 10));
            }
        });
        

		$('#del').click(function(e) {
    		e.preventDefault();
    		window.location = "<?php if (isset($detail)) echo base_url().$type.'/Accounting/delete_group/'.$gid; ?>";
    	});
        
        

        $('#submit').click(function(e) {
            e.preventDefault();
            $(this).attr('disabled','disabled');
            
            $.post('<?php if(isset($detail)) { echo base_url().$type."/Accounting/save_group/".$gid; } else { echo base_url().$type."/Accounting/save_group/"; } ?>', {
                'name': $('#vendors').val(),
                'classes' : $('#myclasses').val(),
                'groups' : $('#mygroups').val()
            }, function(d,s,x) {
                window.location = "<?php echo base_url().$type.'/Accounting/groups'; ?>";
            }, "text");
        });
        
        $('#ledger_list').on('click', '.ledger_delete', function(e) {
            e.preventDefault();
            var l = $(this).prop('id');
            selected_ledger.splice(l, 1);
            fill_list();
        })
    });
    
    function fill_list() {
        $('#ledger_list').empty();
        var c="";
        for(var i=0;i<selected_ledger.length; i++) {
            c+='<div class="mdl-list__item"><span class="mdl-list__item-primary-content">' + selected_ledger[i] + '</span><span class="mdl-list__item-secondary-action"><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored mdl-button--icon ledger_delete" id="' + i + '"><i class="material-icons">delete</i></button></span></div>';
        }
        $('#ledger_list').append(c);
    }
</script>
</html>