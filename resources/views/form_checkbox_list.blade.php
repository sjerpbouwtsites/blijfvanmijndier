<h4 class="list_h4">{{ $title }}</h4>
<div class="form-group">
	@foreach ($list as $listItem)
	<div class="checkbox">
		{{ Form::checkbox('tables[]', $listItem->id, in_array($listItem->id, $checked) ? true : false) }} <label for='input-{{$listItem->id}}'>{{ $listItem->description }}</label>
		</div>
	@endforeach
</div>
