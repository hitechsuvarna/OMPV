<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Show Hide Pricing</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-grid">
                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch-1">
                            <input type="checkbox" id="switch-1" class="mdl-switch__input" <?php $sess_data = $this->session->userdata(); if($sess_data["price_display"]== "block") echo "checked"; ?>>
                            <span class="mdl-switch__label">Display product pricing</span>
                        </label>
                        <div id="pass-disp">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input type="password" id="switch_pass" name="switch_pass" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="switch_pass">Enter your Password</label>
    					</div>
    					<button class="mdl-button mdl-js-button mdl-button--primary" id="switch-pass-btn">Update Settings</button>
    					</div>
                    </div>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Password Reset</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="password" id="u_o_pass" name="u_o_pass" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="u_o_pass">Old Password</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="password" id="u_pass" name="u_pass" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="u_pass">Password</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="password" id="u_confirm_pass" name="u_confirm_pass" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="u_confirm_pass">Confirm Password</label>
					</div>
					<button class="mdl-button mdl-js-button mdl-button-done mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" id="submit">Update</button>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" id="user-create-div">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Create Users</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="cu_name" name="cu_name" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="cu_name">Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="cu_email" name="cu_email" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="cu_email">Email</label>
					</div>
					<button class="mdl-button mdl-js-button mdl-button-done mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" id="cu_submit">Add User</button>
					<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%;display:block;overflow:auto;" id="cu_table">
                        <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Name</th>
                                <th class="mdl-data-table__cell--non-numeric">Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i=0;$i<count($usrs); $i++) {
                               echo '<tr><td class="mdl-data-table__cell--non-numeric">'.$usrs[$i]->idu_name.'</td><td class="mdl-data-table__cell--non-numeric">'.$usrs[$i]->idu_username.'</td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="'.$usrs[$i]->idu_id.'"><i class="material-icons">delete</i></button></td></tr>'; 
                            }?>
                        </tbody>
                    </table>
                </div>
                
            </div>
		</div>
	</div>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
	    
	    <?php 
	        $sess_data = $this->session->userdata();
	        if(isset($sess_data['sub'])) {
	            echo '$("#user-create-div").css("display","none");';
	        }
	    ?>
	    
	    $('#pass-disp').css('display','none');
	    
	    $('#switch-1').change(function(e) {
	        e.preventDefault();
	        $('#pass-disp').css('display','block');
	    });
	    
	    $('#switch-pass-btn').click(function(e) {
	        e.preventDefault();
	        
	        var ps = $('#switch_pass').val();
	        $.post('<?php $sess_data = $this->session->userdata(); if(isset($sess_data['sub'])) { echo base_url().$type."/Account/display_price/sub"; } else { echo base_url().$type."/Account/display_price"; } ?>', {'ps' : ps }, function(d, s, x) { 
	            if(d=="true") {
	                display_message("Pricing Displayed.");
	            } else if(d=="false") { 
	                display_message("Pricing Hidden."); 
	            } else if(d=="password") { 
	                if($('#switch-1')[0].checked == true){
	                   switchoff();
	                   display_message("Incorrect Password.");
	                } else {
	                    switchon();
	                    display_message("Incorrect Password.");
	                }
	            } 
	            $('#pass-disp').css('display','none'); 
	            $('#switch_pass').val('');
	        }, 'text');
	    });
	    
	    $('#submit').click(function(e) {
	        e.preventDefault();
	        
	        $.post('<?php $sess_data = $this->session->userdata(); if(isset($sess_data['sub'])) { echo base_url().$type."/Account/reset_account_pass/sub"; } else { echo base_url().$type."/Account/reset_account_pass"; } ?>', { 'upass' : $('#u_pass').val(), 'uopass' : $('#u_o_pass').val() }, function(d, s, x) {  if(d=="true") {display_message("Password Updated. Please Login Again"); window.location = "<?php echo base_url().'dealers/Account/logout/1'; ?>" } else if(d=="false") { display_message("Error."); } else if(d=="password") { display_message("Incorrect Password."); } }, 'text');
	    });
	    
	    $('#cu_submit').click(function(e) {
	        e.preventDefault();
	        
	        $.post('<?php echo base_url().$type."/Account/save_user"; ?>', { 'name' : $('#cu_name').val(), 'email' : $('#cu_email').val() }, function(d, s, x) { $('#cu_table > tbody').empty(); var a=JSON.parse(d), o=""; for(var i=0;i<a.length; i++) { o+='<tr><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_name + '</td><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_username + '</td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + a[i].idu_id + '"><i class="material-icons">delete</i></button></td></tr>';  } $('#cu_table > tbody').append(o); reset_users()  }, 'text');
	    });
	    
	    $('#cu_table').on('click','.delete', function(e) {
	        e.preventDefault();
	        
	        $.post('<?php echo base_url().$type."/Account/delete_user"; ?>', { 'did' : $(this).prop('id') }, function(d, s, x) { $('#cu_table > tbody').empty(); var a=JSON.parse(d), o=""; for(var i=0;i<a.length; i++) { o+='<tr><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_name + '</td><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_username + '</td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + a[i].idu_id + '"><i class="material-icons">delete</i></button></td></tr>';  } $('#cu_table > tbody').append(o); reset_users(); }, 'text');
	    });
	    
	    
	});
	
	function reset_users() {
	
	    $('#cu_name').val('');
	    $('#cu_email').val('');
	}
	function switchon(){
	    document.getElementById('switch-1').checked = true;
	   // $("#switch-1").prop( "checked", true );
	    console.log("Ahoy");
	}
	
	function switchoff(){
	    $("#switch-1").prop( "checked", false );
	}
	
	
	function display_message(message) {
	    var snackbarContainer = document.querySelector('#demo-snackbar-example');
        var ert = {message: message,timeout: 2000, }; 
        
        snackbarContainer.MaterialSnackbar.showSnackbar(ert);

	}
	function redirect() {
		window.location = '<?php echo base_url().$this->config->item('dealer_login_red'); ?>';
	}
</script>
</html>