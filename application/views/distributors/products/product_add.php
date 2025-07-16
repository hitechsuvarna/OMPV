<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Product Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<b>Category</b>
						<ul id="p_category" class="mdl-textfield__input">
							<?php if (isset($edit_product)) {
									for ($j=0; $j < count($category) ; $j++) { 
										if ($category[$j]->ica_id == $edit_product[0]->ip_category) {
											echo "<li>".$category[$j]->ica_category_name."</li>";
											break;
										}
									}
								} else if(isset($curr_cat)) {
								    for ($j=0; $j < count($category) ; $j++) { 
										if ($category[$j]->ica_id == $curr_cat) {
											echo "<li>".$category[$j]->ica_category_name."</li>";
											break;
										}
									}
								}
							?>
						</ul>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_name" name="p_name" class="mdl-textfield__input" value="<?php if(isset($edit_product)) { echo $edit_product[0]->ip_name; } ?>">
						<label class="mdl-textfield__label" for="p_name">Product Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_code" name="p_code" class="mdl-textfield__input" value="<?php if(isset($edit_product)) { echo $edit_product[0]->ip_description; } ?>">
						<label class="mdl-textfield__label" for="p_code">Product Code</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_alias" name="p_alias" class="mdl-textfield__input" value="<?php if(isset($edit_product)) { echo $edit_product[0]->ip_alias; } ?>">
						<label class="mdl-textfield__label" for="p_alias">Alias</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_hsn" name="p_hsn" class="mdl-textfield__input" value="<?php if(isset($edit_product)) { echo $edit_product[0]->ip_hsn_code; } ?>">
						<label class="mdl-textfield__label" for="p_hsn_code">HSN Code</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<b>Unit</b>
						<ul id="p_unit" class="mdl-textfield__input">
							<?php if (isset($edit_product)) {
									for ($j=0; $j < count($units) ; $j++) { 
										if ($units[$j]->ip_unit == $edit_product[0]->ip_unit) {
											echo "<li>".$units[$j]->ip_unit."</li>";
											break;
										}
									}
								}
							?>
						</ul>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="p_tax" class="mdl-textfield__input">
							<option value="0">Select</option>
							<?php for ($i=0; $i < count($taxes) ; $i++) { 
								echo '<option value="'.$taxes[$i]->ittxg_id.'" ';
								if(isset($edit_product_taxes)) if(count($edit_product_taxes) > 0) if($taxes[$i]->ittxg_id == $edit_product_taxes[0]->ipt_t_id) echo "selected ";
								echo '>'.$taxes[$i]->ittxg_group_name.'</option>';
							}?>
						</select>
						<label class="mdl-textfield__label" for="p_hsn_code">Select Tax</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_limit" name="p_limit" class="mdl-textfield__input" value="<?php if(isset($edit_product)) { echo $edit_product[0]->ip_lower_limit; } ?>">
						<label class="mdl-textfield__label" for="p_limit">Inventory Lower Limit</label>
					</div>
					
					
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload product photo 1</p><br>
						<input type="file" name="attach_file" class="upload">
						<img src="<?php if(isset($edit_product_image)) { if(isset($edit_product_image[0])) { echo base_url().'assets/uploads/'.$oid.'/'.$pid.'/'.$edit_product_image[0]->ipi_img; } }?>" id="p_image" style="width: 100%;">
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload product photo 2</p><br>
						<input type="file" name="attach_file" class="upload">
						<img src="<?php if(isset($edit_product_image)) { if(isset($edit_product_image[1])) { echo base_url().'assets/uploads/'.$oid.'/'.$pid.'/'.$edit_product_image[1]->ipi_img; } }?>" id="p_image" style="width: 100%;">
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload product photo 3</p><br>
						<input type="file" name="attach_file" class="upload">
						<img src="<?php if(isset($edit_product_image)) { if(isset($edit_product_image[2])) { echo base_url().'assets/uploads/'.$oid.'/'.$pid.'/'.$edit_product_image[2]->ipi_img; } }?>" id="p_image" style="width: 100%;">
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload product photo 4</p><br>
						<input type="file" name="attach_file" class="upload">
						<img src="<?php if(isset($edit_product_image)) { if(isset($edit_product_image[3])) { echo base_url().'assets/uploads/'.$oid.'/'.$pid.'/'.$edit_product_image[3]->ipi_img; } }?>" id="p_image" style="width: 100%;">
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload product photo 5</p><br>
						<input type="file" name="attach_file" class="upload">
						<img src="<?php if(isset($edit_product_image)) { if(isset($edit_product_image[4])) { echo base_url().'assets/uploads/'.$oid.'/'.$pid.'/'.$edit_product_image[4]->ipi_img; } }?>" id="p_image" style="width: 100%;">
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload product photo 6</p><br>
						<input type="file" name="attach_file" class="upload">
						<img src="<?php if(isset($edit_product_image)) { if(isset($edit_product_image[5])) { echo base_url().'assets/uploads/'.$oid.'/'.$pid.'/'.$edit_product_image[5]->ipi_img; } }?>" id="p_image" style="width: 100%;">
					</div>
					<?php if (isset($edit_product)) {
						echo '<div> <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="delete"> Delete </button> </div> </div>';	
					} ?>
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
	    
	    $( "#p_name" ).autocomplete({
            source: function(request, response) {
                $.post('<?php echo base_url().$type."/Products/search_product_name"; ?>', {
                    'keywords' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response( $.map(a, function( item ) {
                        return{
                                label: item.ip_name,
                                value: item.ip_name
                        }
                    }));
                })
            }
        }); 
    	
		var unit_data = [];
    	<?php for ($i=0; $i < count($units) ; $i++) {echo "unit_data.push('".$units[$i]->ip_unit."');"; } ?>
    	$('#p_unit').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : unit_data,
    		tagLimit : 1,
    		singleField : true
    	});

		var category_data = [];
    	<?php for ($i=0; $i < count($category) ; $i++) {echo "category_data.push('".$category[$i]->ica_category_name."');"; } ?>
    	$('#p_category').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : category_data,
    		tagLimit : 1,
    		singleField : true
    	});

		$('#submit').click(function(e) {e.preventDefault(); save_record(); });

		$('#delete').click(function(e) {e.preventDefault(); delete_record(<?php if (isset($edit_product)) echo $edit_product[0]->ip_id; ?>)});
	});

	function save_record() {
		$.post('<?php if(isset($edit_product)) { echo base_url().$type."/Products/update_product/".$pid; } else { echo base_url().$type."/Products/save_product"; } ?>', {
			'category' : $('#p_category')[0].innerText,
			'product' : $('#p_name').val(),
			'description' : $('#p_code').val(),
			'alias' : $('#p_alias').val(),
			'hsn' : $('#p_hsn').val(),
			'unit' : $('#p_unit')[0].innerText,
			'tax' : $('#p_tax').val(),
			'limit' : $('#p_limit').val()
		}, function(data, status, xhr) {
			image_upload(data);
		}, 'text');
	}

	function delete_record(pid) {$.post('<?php echo (isset($edit_product))?base_url().$type."/Products/delete_product/".$pid : "#"; ?>', {}, function(data, status, xhr) {redirect(); }, 'text'); }

    var datat = new FormData();
	function image_upload(id) {
		
		var rf = false;
		
		    
		for(var i=0; i < $('.upload').length; i++) {
		    if($('.upload')[i].files[0]) {
		        console.log($('.upload')[i].files[0]);
    		    datat.append(i, $('.upload')[i].files[0]);
		    }
		}
		console.log(datat);
		var url = "<?php echo base_url().$type.'/Products/image_upload/'; ?>" + id + "/" + 1;
		
		flnm = "";
		$.ajax({
			url: url, // Url to which the request is send
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
				redirect();
			}
		});
		
		if(rf == true) {
		  //  redirect();
		}
	}

	function redirect() {
		window.location = "<?php echo base_url().$type.'/Products/load_category/'.$curr_cat; ?>";
	}
</script>
</html>