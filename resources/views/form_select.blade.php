<div class="form-group">
    {{ Form::label($field, $label, array('class' => 'control-label col-md-4')) }}
    <div class="col-md-8">
        {{ Form::select($id, $types, Input::old($id), ['class' => 'form-control']) }}
    </div>
</div>
