	<div class="box-content">
				<form id="form_add_marcas" name ="form_add_marcas" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Datos de Registro</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nombre Marca</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtmarca" id="txtmarca" data-toggle="tooltip" data-placement="bottom" title="Nombre de la Marca"> />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Identificador Marca</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtidmarca"   maxlength="3" id="txtidmarca" data-toggle="tooltip" data-placement="bottom" title="Identificador"> />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Logo</label>
							<div class="col-sm-5">
								<input type="file" class="form-control" name="logo"  />
							</div>	
						</div>
						<div class="form-group">
							<div class="col-sm-9 col-sm-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox"  name="acceptTerms" /> Accept the terms and policies
										<i class="fa fa-square-o small"></i>
									</label>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Información Marca</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Website</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="website" placeholder="http://" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Phone number</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="phoneNumber" />
							</div>
						</div>
					</fieldset>
					
					
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
			</div>
<script type="text/javascript">
// Run Select2 plugin on elements


$(document).ready(function() {
	// Create Wysiwig editor for textare
	TinyMCEStart('#wysiwig_simple', null);
	TinyMCEStart('#wysiwig_full', 'extreme');
	// Add slider for change test input length
	FormLayoutExampleInputLength($( ".slider-style" ));
	// Initialize datepicker
	$('#input_date').datepicker({setDate: new Date()});
	// Load Timepicker plugin
	LoadTimePickerScript(DemoTimePicker);
	// Add tooltip to form-controls
	$('.form-control').tooltip();
	LoadSelect2Script(DemoSelect2);
	// Load example of form validation
	LoadBootstrapValidatorScript(DemoFormValidator);
	// Add drag-n-drop feature to boxes
	WinMove();
});
</script>