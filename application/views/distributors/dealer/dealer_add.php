<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Dealer Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_name; } ?>">
						<label class="mdl-textfield__label" for="c_name">Company Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_company" name="c_company" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_company; } ?>">
						<label class="mdl-textfield__label" for="c_company">Customer Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_email" name="c_email" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_email; } ?>">
						<label class="mdl-textfield__label" for="c_email">Email</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="number" id="c_phone" name="c_phone" type="number" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_phone; } ?>">
						<label class="mdl-textfield__label" for="c_phone">Phone</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_address" name="c_address" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_address; } ?>">
						<label class="mdl-textfield__label" for="c_address">Address</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_gst" name="c_gst" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_gst_number; } ?>">
						<label class="mdl-textfield__label" for="c_gst">GST Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_credit" name="c_credit" class="mdl-textfield__input" value="<?php if(isset($edit_dealer)) { echo $edit_dealer[0]->ic_credit; } ?>">
						<label class="mdl-textfield__label" for="c_credit">Credit in days</label>
					</div>
				
					<?php if (isset($edit_dealer)) {
					    if($edit_dealer[0]->ic_new_flag == "1") {
					        echo '<div><button class="mdl-button mdl-js-button mdl-button--colored" id="activate"> Activate </button></div>';
					        echo '<div><button class="mdl-button mdl-js-button mdl-button--colored" id="block"> Block </button></div>';
					    } else if($edit_dealer[0]->ic_new_flag == "0") {
					        echo '<div><button class="mdl-button mdl-js-button mdl-button--colored" id="block"> Block </button></div>';
					    } else if($edit_dealer[0]->ic_new_flag == "2") {
					        echo '<div><button class="mdl-button mdl-js-button mdl-button--colored" id="activate"> Activate </button></div>';
					    } else {
					        echo '<div><button class="mdl-button mdl-js-button mdl-button--colored" id="block"> Block </button></div>';
					    } 
						
						echo '<div><button class="mdl-button mdl-js-button mdl-button--primary" id="delete"> DELETE </button></div>'; } ?>
					
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
		    <div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Sub Dealers</h2>
				</div>
				<div class="mdl-card__supporting-text">
			        <table class="mdl-data-table mdl-js-data-table" style="width:100%;display:block; overflow:auto;">
                        <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Name</th>
                                <th class="mdl-data-table__cell--non-numeric">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($edit_sub_dealer)) {
                                    for($i=0;$i<count($edit_sub_dealer); $i++) {
                                        echo '<tr>';
                                        echo '<td class="mdl-data-table__cell--non-numeric">'.$edit_sub_dealer[$i]->idu_name.'</td>';
                                        echo '<td class="mdl-data-table__cell--non-numeric">'.$edit_sub_dealer[$i]->idu_username.'</td>';
                                        echo '</tr>';
                                    }
                                }
                            
                            ?>
                                        
                        <tr>
                        </tbody>
                    </table>
			    </div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col"></div>
		
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('#submit').click(function(e) {e.preventDefault(); save_record(); });

		$('#delete').click(function(e) { e.preventDefault(); delete_record(); });
		
		$('#activate').click(function(e) { e.preventDefault(); update_flag(0); });
		$('#block').click(function(e) { e.preventDefault(); update_flag(2); });
	});

    function update_flag(state) {
        $.post('<?php if(isset($edit_dealer)) echo base_url().$type."/Dealers/update_flag/".$did; ?>', {
            'f' : state
        }, function(d,s,x) {
            window.location = "<?php echo base_url().$type.'/Dealers'; ?>";
        })
    }
	function delete_record() {
		$.post('<?php echo (isset($edit_dealer))? base_url().$type.'/Dealers/delete_dealer/'.$did : '#'; ?>', {}, function(d,s,x) { window.location = "<?php echo base_url().$type.'/Dealers'; ?>"}, "text");
	}

	function save_record() {
		$.post('<?php if(isset($edit_dealer)) { echo base_url().$type."/Dealers/update_dealer/".$did; } else { echo base_url().$type."/Dealers/save_dealer"; } ?>', {
			'name' : $('#c_name').val(),
			'company' : $('#c_company').val(),
			'email' : $('#c_email').val(),
			'phone' : $('#c_phone').val(),
			'address' : $('#c_address').val(),
			'gst' : $('#c_gst').val(),
			'credit' : $('#c_credit').val()
		}, function(data, status, xhr) {
			redirect();
		}, 'text');
	}


	
	function redirect() {
		window.location = "<?php echo base_url().$type.'/Dealers'; ?>";
	}
</script>
</html>