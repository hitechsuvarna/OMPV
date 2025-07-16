<main class="mdl-layout__content">
	<div class="mdl-grid">
		<!-- GENERAL DETAILS -->
		<div class="mdl-cell mdl-cell--4-col">
			<a href="<?php echo base_url().'Account/details/'.$uid; ?>">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title mdl-card--expand">
					<h2 class="mdl-card__title-text">Your Details</h2>
				</div>
			</div>
			</a>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<a href="<?php echo base_url().'Account/create_module'; ?>">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title mdl-card--expand">
					<h2 class="mdl-card__title-text">Create your modules using Excel</h2>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<a href="<?php echo base_url().'Account/user_list'; ?>">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title mdl-card--expand">
					<h2 class="mdl-card__title-text">Create users</h2>
				</div>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<a href="<?php echo base_url().'Account/user_accounting_setting'; ?>">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title mdl-card--expand">
					<h2 class="mdl-card__title-text">Accounting Setting</h2>
				</div>
			</div>
		</div>

	</div>
</main>
</div>
</body>
</html>