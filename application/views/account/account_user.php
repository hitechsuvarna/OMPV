<style type="text/css">
	.fileUpload {
			position: relative;
			overflow: hidden;
			/*margin: 10px;*/
		}
		.fileUpload input.upload {
			position: absolute;
			top: 0;
			right: 0;
			margin: 0;
			padding: 0;
			
			cursor: pointer;
			opacity: 0;
			filter: alpha(opacity=0);
		}
</style>
<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--2-col"></div>
		<div class="mdl-cell mdl-cell--8-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Your Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php echo $basic[0]->ic_name; ?>">
						<label class="mdl-textfield__label" for="c_name">Name</label>
					</div>
					<!-- <b>Choose Display Photo</b>
					<input type="file" name="attach_file" class="upload"> -->					
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col"></div>
		
		<div class="mdl-cell mdl-cell--2-col"></div>
		<div class="mdl-cell mdl-cell--8-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Basic Information</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div id="info_repeat" class="mdl-grid">
						<?php 
							for ($i=0; $i < count($property) ; $i++) { 
								$prop = $property[$i]->ip_property;
								$pid = $property[$i]->ip_id;
								$val = "";

								for ($ij=0; $ij < count($details) ; $ij++) { 
									$cpid = $details[$ij]->icbd_property;
									
									if ($cpid==$pid) {
										$val = $details[$ij]->icbd_value;
									}
								}

								echo '<div class="mdl-cell mdl-cell--4-col"><div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><input type="text" id="c_val'.$pid.'" name="c_val[]" class="mdl-textfield__input" value="'.$val.'"><label class="mdl-textfield__label" for="c_val'.$pid.'">'.$prop.'</label></div></div>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col"></div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
</div>
</div>
</body>

<script>
	$(document).ready(function() {
		$('#c_name').focus();	

		$('#submit').click(function(e) {
			e.preventDefault();
			
			var c_new_prp = [];
			var c_new_val = [];
			$("input[name^='c_n_val'").each(function(){
				console.log($(this).val());
				c_new_val.push($(this).val());
			});

			$("input[name^='c_n_prp'").each(function(){
				console.log($(this).val());
				c_new_prp.push($(this).val());
			});

			var c_new_data = [];
			c_new_data.push({'n_p' : c_new_prp, 'n_v' : c_new_val});

			var c_value = [];
			$("input[name^='c_val'").each(function(){
				var pp = $(this).prop('id');
				var l = pp.length;
				pp = pp.substr(5,l);	
				c_value.push({'p': $(this).val(), 'v' : pp });
			});

			console.log(c_value);
			
			var customer_name = $('#c_name').val();
			var customer_info = [];

			var stid = "";

			<?php 
					echo "$.post('".base_url()."Account/update_account/".$cid."', {'name' : customer_name, 'new_property' : c_new_data, 'value' : c_value }, function(data, status, xhr) { window.location = '".base_url()."Account' }, 'text');";
			?>

			
		});
	});

// function uploadfiledata(stid) {
// 	var datat = new FormData();
// 	if($('.upload')[0].files[0]) {
// 		datat.append("use", $('.upload')[0].files[0]);
		
// 		flnm = "";
// 		$.ajax({
// 			url: "<?php echo base_url().'education/uploadfile/'; ?>" + stid, // Url to which the request is send
// 			type: "POST",             // Type of request to be send, called as method
// 			data: datat, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
// 			contentType: false,       // The content type used when sending data to the server.
// 			cache: false,             // To unable request pages to be cached
// 			processData:false,        // To send DOMDocument or non processed data file it is set to false
// 			success: function(data)   // A function to be called if request succeeds
// 			{
// 				console.log("Recd: " + data);
// 				flnm = data.toString();
// 				$('.upload').val('');
// 				window.location = '<?php echo base_url()."education/students"; ?>';
// 			}
// 		});
// 	} else {
// 		window.location = '<?php echo base_url()."education/students"; ?>';
// 	}
// }
</script>
</html>