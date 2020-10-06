<div class="form-group">
    {{ Form::label($field, $label, array('class' => 'control-label col-md-4')) }}
    <div class="col-md-8">
    	{{ Form::text($field, Input::old($field), array('class' => 'form-control')) }}
    </div>
</div>
