<main class="mdl-layout__content">
	<div class="mdl-grid">
		<!-- GENERAL DETAILS -->
		<div class="mdl-cell mdl-cell--12-col">
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--4dp" style="width: 100%;">
				<thead>
					<tr>
						<th class="mdl-data-table__cell--non-numeric">Name</th>
						<th class="mdl-data-table__cell--non-numeric">Email</th>
						<th class="mdl-data-table__cell--non-numeric">Group</th>
					</tr>
				</thead>
				<tbody>
					<?php
						for ($i=0; $i < count($users) ; $i++) { 
							echo '<tr id="'.$users[$i]->i_uid.'" class="click_customer">';
							echo '<td class="mdl-data-table__cell--non-numeric">'.$users[$i]->ic_name;
							echo '<td class="mdl-data-table__cell--non-numeric">'.$users[$i]->i_uname;
							echo '<td class="mdl-data-table__cell--non-numeric">'.$users[$i]->ic_section;
							echo "</tr>";
						}
					?>
				</tbody>
			</table>

		</div>
		<a href="<?php echo base_url().'Account/add_user'; ?>">
			<button class="lower-button mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent">
				<i class="material-icons">add</i>
			</button>
		</a>
	</div>
</main>
</div>
</body>
<script>
	$(document).ready(function() {
		$('.click_customer').click(function(e) {
			e.preventDefault();

			var cid = $(this).prop('id');

			window.location = "<?php echo base_url().'Portal/edit_customer/'; ?>"+ cid;
		});
	});
</script>

</html>