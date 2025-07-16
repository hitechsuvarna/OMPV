<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Accounting Year</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="date-input-start" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="date-input-start">Year Start Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="date-input-end" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="date-input-end">Year End Date</label>
					</div>

					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="year-code" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="year-code">Year Code</label>
					</div>

					<!-- <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="c_start_date" name="c_start_date" class="mdl-textfield__input">
							<option value="NA">Select</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
						</select>
						<label class="mdl-textfield__label" for="c_start_date">Accounting Year Start Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="c_start_month" name="c_start_month" class="mdl-textfield__input">
							<option value="NA">Select</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						<label class="mdl-textfield__label" for="c_start_month">Accounting Year Start Month</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="c_end_date" name="c_end_date" class="mdl-textfield__input">
							<option value="NA">Select</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
						</select>
						<label class="mdl-textfield__label" for="c_end_date">Accounting Year End Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="c_end_month" name="c_end_month" class="mdl-textfield__input">
							<option value="NA">Select</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						<label class="mdl-textfield__label" for="c_end_month">Accounting Year End Month</label>
					</div>
					 -->
				</div>
			</div>
		</div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('#date-input-start').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
		$('#date-input-end').bootstrapMaterialDatePicker({ weekStart : 0, time: false });

		var start_yr = "";
		var end_yr = "";
		$('#date-input-start').change(function(e){
			e.preventDefault();
			
			var dt = new Date($(this).val());
			start_yr = dt.getFullYear();

			$('#year-code').val(start_yr + "-" + end_yr);
		});
		
		$('#date-input-end').change(function(e){
			e.preventDefault();
			
			var dt = new Date($(this).val());
			end_yr = dt.getFullYear();
			$('#year-code').val(start_yr + "-" + end_yr);
		});


		$('#submit').click(function(e) {
			e.preventDefault();
			
			$.post('<?php echo base_url()."Account/update_accounting_setting"; ?>', {
				"year_start" : $('#date-input-start').val(),
				"year_end" : $('#date-input-end').val(),
				"year_code" : $('#year-code').val(),
			}, function(data, status, xhr) {

			}, "text");

			
		});

	});
</script>
</html>