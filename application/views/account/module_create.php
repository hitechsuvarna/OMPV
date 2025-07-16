<main class="mdl-layout__content">
	<div class="mdl-grid">

		<div class="mdl-cell mdl-cell--2-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Upload a Excel file</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<input type="file" name="attach_file" class="upload">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($edit_customer)) { echo $edit_customer[0]->ic_name; } ?>">
						<label class="mdl-textfield__label" for="c_name">Module Name</label>
					</div>
					
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Preferences</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-cell mdl-cell--12-col" style="text-align: center;margin: 0px;">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<ul id="myTags" class="mdl-textfield__input">
							<?php if (isset($edit_preferences)) {
									for ($j=0; $j < count($edit_preferences) ; $j++) { 
										$x = $edit_preferences[$j]->imp_tag_id;
									
										$y = 0;
										for ($ij=0; $ij < count($tags) ; $ij++) { 
											$m = $tags[$ij]->iat_id;
											if($x==$m) {
												$y=$ij;
											}
										}
										echo "<li>".$tags[$y]->iat_value."</li>";
									}
								}
							?>
						</ul>
						</div>
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
<script type="text/javascript">
    $(document).ready( function() {
    	var tag_data = [];
    	
    	<?php
    		for ($i=0; $i < count($tags) ; $i++) { 
    			echo "tag_data.push('".$tags[$i]->it_value."');";
    		}
    	?>
    	
    	$('#myTags').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : tag_data
    	});
    });
</script>

<script>
	$(document).ready(function() {
		$('#submit').click(function(e) {
			e.preventDefault();
			
			var name = $('#c_name').val();
			var address = $('#c_address').val();
			
			var customer_info = [];
			$('#myTags > li').each(function(index) {
				var tmpstr = $(this).text();
				var len = tmpstr.length - 1;
				if(len > 0) {
					tmpstr = tmpstr.substring(0, len);
					customer_info.push(tmpstr);
				}
			});
			

			<?php 
				echo "$.post('".base_url()."Account/save_module', {'name' : name, 'tags' : customer_info }, function(data, status, xhr) { uploadfiledata(data); }, 'text');";
			?>
		});

	});

	function uploadfiledata(stid) {
		var datat = new FormData();
		if($('.upload')[0].files[0]) {
			datat.append("use", $('.upload')[0].files[0]);
			
			flnm = "";
			$.ajax({
				url: "<?php echo base_url().'Account/uploadfile/'; ?>" + stid, // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: datat, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(data)   // A function to be called if request succeeds
				{
					console.log("Recd: " + data);
					flnm = data.toString();
					$('.upload').val('');
					window.location = '<?php echo base_url()."Account/module_details/"; ?>' + data;
				}
			});
		} else {
			// window.location = '<?php echo base_url()."Account"; ?>';
		}
	}
</script>
</html>