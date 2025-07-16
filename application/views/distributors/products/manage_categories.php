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
	
	.tblhght {
	    height: 80vh;
	    overflow: auto;
	}
</style>

<main class="mdl-layout__content">

	<div class="mdl-grid" >
		<div class="mdl-cell mdl-cell--6-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Category Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_name" name="p_name" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="p_name">Cateory Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select class="mdl-textfield__input" id="p_parent">
							<?php echo '<option value="0">None</option>'; for ($i=0; $i < count($category); $i++) { 
								echo '<option value="'.$category[$i]->ica_id.'">'.$category[$i]->ica_category_name.'</option>';
							} ?>
						</select>
						<label class="mdl-textfield__label" for="p_name">Select Parent Category</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<p style="margin: 10px;text-align: left;">Upload category photo</p><br>
						<input type="file" name="attach_file" class="upload" multiple>
						<div class="category_photos"></div>
					</div>
					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="submit">
						UPDATE
					</button>
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored" id="delete">
						DELETE
					</button>
					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="reset">
						reset
					</button>

				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--6-col tblhght">
			<table class="purchase_table">
			    <thead>
			        <tr>
			            <th>Category</th>
			        </tr>
			    </thead>
				<tbody>
					<?php 
						for ($i=0; $i < count($category) ; $i++) { 
							echo '<tr id="'.$category[$i]->ica_id.'"><td>'.$category[$i]->ica_category_name.'</td></tr>';
						}
					?>
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

</body>
<script>
	var catid="";
	var snackbarContainer = document.querySelector('#demo-snackbar-example');
  	$(document).ready(function() {
		$('#fixed-header-drawer-exp').keyup(function(e) {
			e.preventDefault();
			$.post('<?php echo base_url().$type."/Products/search_categories"; ?>', { 'keyword' : $(this).val() }, function(d,s,x) { $('.purchase_table > tbody').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { b+='<tr id="' + a[i].ica_id + '"> <td>' + a[i].ica_category_name + '</td> </tr>'; } $('.purchase_table > tbody').append(b); });
		});
		
		$('#delete').click(function(e) {
		    if(catid!=""){
		        window.location = '<?php echo base_url().$type."/Products/delete_category/"; ?>' + catid;
		    }
		})
		
		$('#reset').click(function(e) {
			e.preventDefault();
			$('#fixed-header-drawer-exp').val();
			$('#p_name').val();
			$('#p_parent').val();
			$('.upload').val('');
			catid="";
		})

		$('#submit').click(function(e) {
		    $(this).attr('disabled','disabled');
		    e.preventDefault();
		    save_record();
		});
		
		$('.purchase_table').on('click','tr', function(e) {
			e.preventDefault();
			catid=$(this).prop('id');
			var imgurl = "<?php echo base_url().'assets/uploads/'.$oid.'/c/'; ?>" + catid + '/';
			$.post('<?php echo base_url().$type."/Products/load_categories/"; ?>' + $(this).prop('id'), { }, 
			    function(d,s,x) { 
			        var a=JSON.parse(d); 
			        $('#p_name').val(a[0].ica_category_name); $('#p_parent').val(a[0].ica_parent_category); 
			        $('.category_photos').empty();
			        if(a[0].ica_img!="" || a[0].ica_img != NULL) { 
			              $('.category_photos').append('<img src="' + imgurl + a[0].ica_img + '" style="width:100%; margin:10px;">'); 
			        }
			        if(a[0].ica_img2!="" || a[0].ica_img2 != NULL) { 
			              $('.category_photos').append('<img src="' + imgurl + a[0].ica_img2 + '" style="width:100%;margin:10px;">'); 
			        }
			        if(a[0].ica_img3!="" || a[0].ica_img3 != NULL) { 
			              $('.category_photos').append('<img src="' + imgurl + a[0].ica_img3 + '" style="width:100%;margin:10px;">'); 
			        }
			        if(a[0].ica_img4!="" || a[0].ica_img4 != NULL) { 
			              $('.category_photos').append('<img src="' + imgurl + a[0].ica_img4 + '" style="width:100%;margin:10px;">'); 
			        }
			        if(a[0].ica_img5!="" || a[0].ica_img5 != NULL) { 
			              $('.category_photos').append('<img src="' + imgurl + a[0].ica_img5 + '" style="width:100%;margin:10px;">'); 
			        }
			        if(a[0].ica_img6!="" || a[0].ica_img6 != NULL) { 
			              $('.category_photos').append('<img src="' + imgurl + a[0].ica_img6 + '" style="width:100%;margin:10px;">'); 
			        }
			        
			 }, "text" );
		});

		function save_record() {
			$.post('<?php echo base_url().$type."/Products/update_categories/"; ?>' + catid, {
				'c' : $('#p_name').val(),
				'p' : $('#p_parent').val(),
			}, function(data, status, xhr) {
			    var ert = {message: "Please Wait.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
				image_upload(data);
			}, 'text').fail(function(r) {
			    var ert = {message: "Please Try Again.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                $('#submit').removeAttr('disabled');
			})
		}

		function delete_record(pid) {$.post('<?php echo (isset($edit_product))?base_url().$type."/Products/delete_product/".$pid : "#"; ?>', {}, function(data, status, xhr) {redirect(); }, 'text'); }

    	var datat = new FormData();
		
		function image_upload(id) {
			var rf = false;
			for(var i=0; i < $('.upload').length; i++) {
			    console.log($('.upload')[i].files);
			    for(var j=0;j<$('.upload')[i].files.length; j++) {
			        if($('.upload')[i].files[j]) {
    			 	    datat.append(j, $('.upload')[i].files[j]);
			        }
			    }
			}
			console.log(datat);
			var url = "<?php echo base_url().$type.'/Products/category_image_upload/'; ?>" + id + "/" + 1;
			
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
					var ert = {message: "Category Updated.",timeout: 1000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                    setTimeout(function() {
                        redirect();
                    }, 1000);
				}
			});
			
			if(rf == true) {
				redirect();
			}
		}

		function redirect() {
			window.location = "<?php echo base_url().$type.'/Products/manage_categories'; ?>";
		}
	});
</script>
</html>