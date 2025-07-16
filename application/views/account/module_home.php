<main class="mdl-layout__content">
	<div class="mdl-grid">
		<!-- GENERAL DETAILS -->
		<?php 
			for ($i=0; $i < count($module) ; $i++) { 
				echo '<div class="mdl-cell mdl-cell--4-col">';
				echo '<a href="'.base_url()."ModX/application_generate/".$module[$i]->icem_id.'/view/">';
				echo '<div class="mdl-card mdl-shadow--4dp">';
				echo '<div class="mdl-card__title mdl-card--expand">';
				echo '<h2 class="mdl-card__title-text">'.$module[$i]->icem_name.'</h2>';
				echo '</div>';
				echo '</div>';
				echo '</a>';
				echo '</div>';
			}
		?>
	</div>
</main>
</div>
</body>
</html>