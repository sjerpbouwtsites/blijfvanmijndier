<div class="form-group <?=isset($class) ? $class : ''?>">
    {{ Form::label($field, $label, array('class' => 'control-label col-md-4')) }}
    <div class="col-md-8">
    	{{ Form::date($field, $value, array('class' => 'form-control')) }}
    </div>
</div>
