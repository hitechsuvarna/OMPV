<main class="mdl-layout__content">
	<section class="mdl-layout__tab-panel is-active" id="scroll-tab-1">
		<div class="page-content">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--4-col"></div>
				<div class="mdl-cell mdl-cell--4-col">
					<div class="mdl-card mdl-shadow--4dp">
						<div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Student Details</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($edit_customer)) { echo $edit_customer[0]->ic_name; } ?>">
								<label class="mdl-textfield__label" for="c_name">Enter Student Name</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="text" id="c_name" name="c_name" class="mdl-textfield__input" value="<?php if(isset($edit_customer)) { echo $edit_customer[0]->ic_name; } ?>">
								<label class="mdl-textfield__label" for="c_name">Enter Student Name</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Name</label>
						<input class="form-control text-theme" type="text" id="su_name" name="su_name" placeholder="Your Full Name" value="<?php echo $user[0]->ud_name; ?>">
					</div>
					<div class="form-group">
						<label>Address</label>
						<textarea class="form-control text-theme" type="text" id="su_address" name="su_address" placeholder="Your Address"><?php echo $user[0]->ud_address; ?></textarea>
					</div>
					<div class="form-group">
						<label>Phone</label>
						<input class="form-control text-theme" type="text" id="su_phone" name="su_phone" placeholder="Your phone number"  value="<?php echo $user[0]->ud_phone; ?>">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input class="form-control text-theme" type="text" id="su_email" name="su_email" placeholder="Your Email Id"  value="<?php echo $user[0]->ud_email; ?>">
					</div>
					<div>
						<input id="submit" type="submit" class="btn btn-block btn-info" value="UPDATE">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>