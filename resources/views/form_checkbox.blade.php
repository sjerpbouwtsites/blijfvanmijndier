<div class="form-group">
    {{ Form::label($field, $label, array('class' => 'control-label col-md-4')) }}
    <div class="col-md-8">
    	{{ Form::checkbox($field, Input::old($field), null) }}
    </div>
</div>
