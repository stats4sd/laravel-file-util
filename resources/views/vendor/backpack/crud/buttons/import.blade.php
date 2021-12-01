@if ($crud->hasAccess('update'))
	<a href="{{ url($crud->route.'/import') }}" class="btn btn-success" data-button-type="import"><i class="la la-upload"></i> Import {{ $crud->entity_name_plural }}</a>
@endif
