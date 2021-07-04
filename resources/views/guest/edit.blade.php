@extends('layout')

@section('content')
    <div class="col-md-12">
        @if ($guest->id > 0)
            <h3>Wijzigen gastgezin</h3>
        @else
            <h3>Nieuw gastgezin</h3>
        @endif
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        {{ Html::ul($errors->all()) }}

        @if ($guest->id > 0)
            {{ Form::model($guest, ['route' => ['guests.update', $guest->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
            {{ Form::hidden('id') }}
        @else
            {{ Form::open(['url' => 'guests', 'class' => 'form-horizontal']) }}
        @endif
        @include('generic.address-edit', [
        'model' => $guest,
        'model_name' => 'guest'
        ])
        <div class="col-md-6">
            <h4>Details</h4>
            @include('form_text', ['field' => 'name', 'label' => 'Naam'])
            @include('form_text', ['field' => 'phone_number', 'label' => 'Telefoonnummer'])
            @include('form_text', ['field' => 'email_address', 'label' => 'Emailadres'])
            <!-- dirty in de turbo 👹-->
            <div class="form-group">
                <label for="max_hours_alone" class="control-label col-md-4">max uren alleen</label>
                <div class="col-md-8">
                    <input min="0" class="form-control" required name="max_hours_alone" type="number" value="<?= $guest['max_hours_alone'] ?>" id="max_hours_alone">
                </div>
            </div>


            <div class="form-group">
                {{ Form::label('text', 'Opmerking', ['class' => 'control-label col-md-4']) }}
                <div class="col-md-8">
                    {{ Form::textarea('text', Input::old('text'), ['size' => '80x5', 'class' => 'form-control']) }}
                </div>
            </div>
   
        </div><div class="col-md-6">
            <h4>Beschikbaarheid</h4>
            @include('form_checkbox', ['field' => 'disabled', 'label' => 'Onbeschikbaar'])
            
            @include('form_date', [
                'value' => $guest['disabled_from'],
                'field' => 'disabled_from',
                'label' => 'Onbeschikbaar vanaf', 
                'class' => $guest['disabled'] == '1' ? 'toggle-on-disabled' : 'toggle-on-disabled hidden'
                ])
            @include('form_date', [
                'value' => $guest['disabled_untill'], 
                'label' => 'Onbeschikbaar t/m',
                'field' => 'disabled_untill', 
                'class' => $guest['disabled'] == '1' ? 'toggle-on-disabled' : 'toggle-on-disabled hidden'
                ])
            <?php 
                if ($guest['disabled']) {
                    if ($guest->today_disabled()) {
                        echo "<p class='col-md-8 offset-4 toggle-on-disabled'>Dit gastgezin is momenteel onbeschikbaar.</p>";
                    } else  {
                        echo "<p class='col-md-8 col-md-offset-4 toggle-on-disabled'Let op. >Dit gastgezin staat op onbeschikbaar, maar vandaag valt niet binnen de gegeven data.</p>";
                    }
                }
            ?>
        </div>

      
<br><br><br>
                          <div class="col-md-5 py-5 col-md-offset-1 editscherm-checkboxes__buiten">
                            <div class="editscherm-checkboxes__binnen">
                                   
                                <div class="row editscherm-checkboxes__rij">

                                    <div class="editscherm-checkboxes__kolom">
                                        <div class="editscherm-checkboxes__sectie">
                                            @include('form_checkbox_list', 
                                            ['title' => 'Gedrag', 
                                            'list' => $behaviourList, 
                                            'checked' => $checked_behaviours
                                            ])
                                        </div>
                                        <div class="editscherm-checkboxes__sectie">
                                            @include('form_checkbox_list', 
                                                ['title' => 'Eigen dieren', 
                                                'list' => $own_animal_typeList, 
                                                'checked' => $checked_own_animal_types
                                                ])
                                        </div>            
                                    </div>

                                   <div class="editscherm-checkboxes__kolom">
                                        <div class="editscherm-checkboxes__sectie">
                                            @include('form_checkbox_list', ['title' => 'Wonen', 'list' => $home_typeList, 'checked' => $checked_home_types])
                                        </div>
                                        <div class="editscherm-checkboxes__sectie">
                                            @include('form_checkbox_list', ['title' => 'Diervoorkeur', 'list' => $animal_typeList, 'checked' => $checked_animal_types])
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="form-group form_buttons">
                                {{ Form::submit('Opslaan', ['class' => 'btn btn-primary']) }}
                                <a href="{{ URL::to('guests/' . $guest->id) }}" class="btn btn-default">Annuleren</a>
                                </div>
                        </div>



                          {{ Form::close() }}	

                         </div>    	

                         <script>
                             Array.from(document.querySelectorAll('.editscherm-checkboxes__binnen .checkbox')).forEach(checkboxWrap => {
                                 const input = checkboxWrap.querySelector('input')
                                 const label = checkboxWrap.querySelector('label')
                                 input.id = label.getAttribute('for');
                             })
                             document.getElementById('disabled').addEventListener('change', ()=>{
                                Array.from(document.getElementsByClassName('toggle-on-disabled')).forEach(toggleplease => {
                                    toggleplease.classList.toggle('hidden')
                                })
                             })
                         </script>
@stop
