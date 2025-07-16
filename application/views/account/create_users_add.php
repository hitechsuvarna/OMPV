<main class="mdl-layout__content">
	<div class="mdl-grid" style="margin-bottom: 60px;">
		<!-- GENERAL DETAILS -->
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Select Group</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="group_name" name="group_name" class="mdl-textfield__input">
							<option value="all">Select</option>
							<?php
								for ($i=0; $i < count($groups) ; $i++) { 
									echo "<option value='".$groups[$i]->ic_section."'>".$groups[$i]->ic_section."</option>";
								}
							?>
						</select>
						<label class="mdl-textfield__label" for="group_name">Group</label>
					</div>
				</div>
			</div>
			</div>
			<div class="mdl-cell mdl-cell--12-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Select Property for Username</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="property_name" name="property_name" class="mdl-textfield__input">
							<option value="all">Select</option>
							
						</select>
						<label class="mdl-textfield__label" for="property_name">Property</label>
					</div>
				</div>
			</div>
			</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Select Unalloted Users</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp" style="width: 100%;" id="customer_name">
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
			</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--12-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Select Modules to Assign</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp" style="width: 100%;" id="module_name">
						<tbody>
							<?php
								for ($i=0; $i < count($mod) ; $i++) { 
									echo '<tr>';
									echo '<td class="mdl-data-table__cell--non-numeric">';
									// echo '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">';
									echo '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="'.$mod[$i]->ium_m_id.'">';
									echo '<input type="checkbox" id="'.$mod[$i]->ium_m_id.'" name="module_name[]" class="mdl-switch__input"';
									
									echo '>';
									echo '<span class="mdl-switch__label"></span>';
									echo '</label>';
									// echo '</div>';
									echo "</td>";
									echo '<td class="mdl-data-table__cell--non-numeric">'.$mod[$i]->im_name.'</td>';
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			</div>
			</div>
		</div>
	</div>
	<button class="lower-button mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">done</i>
	</button>
</main>
</div>
</body>
<script>
	$(document).ready(function(){
		$('#group_name').change(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url()."Account/load_customers"; ?>', {
					'group' : $(this).val()
				}, function(data, status, xhr) {
					$('#customer_name > tbody').empty();
					$('#property_name').empty();

					$('#property_name').append('<option value="all">Select</option>');

					var abc = JSON.parse(data);

					for (var i = 0; i < abc.customer.length; i++) {
						$("#customer_name > tbody").append('<tr><td class="mdl-data-table__cell--non-numeric"><label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="c' + abc.customer[i].ic_id + '"><input type="checkbox" id="c' + abc.customer[i].ic_id + '" name="customer_name[]" class="mdl-switch__input"><span class="mdl-switch__label"></span></label></td><td class="mdl-data-table__cell--non-numeric">' + abc.customer[i].ic_name + '</td></tr>');
					}

					for (var i = 0; i < abc.property.length; i++) {
						$("#property_name").append('<option value="' + abc.property[i].ip_id + '">' + abc.property[i].ip_property + '</option>');
					}
				}, "text");
		});

		$('#submit').click(function(e) {
			e.preventDefault();

			var group = $('#group_name').val();
			var property = $('#property_name').val();

			console.log(group);
			var customer = [];
			$("input[name^='customer_name'").each(function(){
				if($(this)[0].checked == true){
					var tmp = $(this).prop('id');
					tmp = tmp.substring(1, tmp.length);
					customer.push(tmp); 
				}
			});
			console.log(customer);
			var module = [];
			$("input[name^='module_name'").each(function(){
				if($(this)[0].checked == true){
					module.push($(this).prop('id')); 	
				}
			});
			console.log(module);

			<?php 
				echo '$.post("'.base_url().'Account/save_user/", { "group" : group, "customer" : customer, "module" : module, "property" : property }, function(data, status, xhr) { window.location = "'.base_url().'Account/add_user"; }, "text");';
			?>
		});

		
	});
</script>
</html>