<main class="mdl-layout__content">
	<div class="mdl-grid">
		<!-- <div class="mdl-cell mdl-cell--6-col"></div> -->
		<div class="mdl-cell mdl-cell--6-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Tax Group Name</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="p_name" name="p_name" class="mdl-textfield__input" value="<?php if(isset($edit_tax_group)) { echo $edit_tax_group[0]->ittxg_group_name; } ?>">
						<label class="mdl-textfield__label" for="p_name">Enter Tax Group Name</label>
					</div>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--6-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Add Taxes to Group</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<label>Add taxes that you want to group together.</label>
					<ul id="myTags" class="mdl-textfield__input">
						<?php if (isset($edit_tax_group_item)) {
								for ($j=0; $j < count($edit_tax_group_item) ; $j++) { 
									$x = $edit_tax_group_item[$j]->itxgc_tx_id;
								
									$y = 0;
									for ($ij=0; $ij < count($tax) ; $ij++) { 
										$m = $tax[$ij]->itx_id;
										if($x==$m) {
											$y=$ij;
										}
									}
									echo "<li>".$tax[$y]->itx_name."</li>";
								}
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>


	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--4-col">
		<?php if(isset($edit_tax_group)) {
			echo "<a href='".base_url().$type.'/Products/delete_tax_group/'.$tid."'";
			echo '<button class="mdl-button mdl-button-upside mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Delete Product</button>';
			echo "</a>";
		}?>
		</div>
		<div class="mdl-cell mdl-cell--4-col"></div>
	</div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
</main>
</div>
</body>
<script type="text/javascript">
    $(document).ready( function() {
    	var tag_data = [];
    	
    	<?php
    		for ($i=0; $i < count($tax) ; $i++) { 
    			echo "tag_data.push('".$tax[$i]->itx_name."');";
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
			
			var group_name = $('#p_name').val();
			var group_taxes = [];

			$('#myTags > li').each(function(index) {
				var tmpstr = $(this).text();
				var len = tmpstr.length - 1;
				if(len > 0) {
					tmpstr = tmpstr.substring(0, len);
					group_taxes.push(tmpstr);
				}
			});

			$.post('<?php if (isset($edit_tax_group)) { echo base_url().$type."/Products/update_tax_group/".$tid; } else { echo base_url().$type."/Products/save_tax_group"; } ?>', {
				'name' : group_name,
				'taxes' : group_taxes
			}, function(data, status, xhr) {
				window.location = '<?php echo base_url().$type."/Products/tax_group"; ?>';
			}, 'text');

		});
	});
</script>
</html>