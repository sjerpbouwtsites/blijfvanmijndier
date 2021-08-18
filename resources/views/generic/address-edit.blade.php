<div class="col-md-6">
    <h4 class='titel-letter'>Adres</h4>

    <input type='hidden' name='address_id' value="<?=$model['address_id']?>" >
    <div class="form-group">
        {{ Form::label('faulty_address', 'Adres niet gebruiken want fout of incompleet', array('class' => 'control-label col-md-8')) }}
        <div class="col-md-4">
            {{ Form::checkbox('faulty_address', Input::old('faulty_address'), null) }}
        </div>
    </div>
    
    <div id='faulty-address-switch' class="{{$model['faulty_address'] === 1 ? 'hidden' : ''}}">

                
                @include('form_group_address' ,	
                    ['lattitude' => $model['lattitude'],
                    'longitude' => $model['longitude']
                    ])


    </div>
    
</div>

<script>
    document.getElementById('faulty_address').addEventListener('change', (e)=>{
        document.getElementById('faulty-address-switch').classList.toggle('hidden');
    })
</script>