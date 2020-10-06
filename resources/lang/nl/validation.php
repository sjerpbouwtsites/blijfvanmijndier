<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required'             => ':attribute is verplicht.',
    'email'                => 'Ongeldig emailadres.',
    'min'                  => [
        'numeric' => ':attribute is verplicht.',
    ],
    'uploaded'             => ':attribute uploaden mislukt.',
    'image'                => ':attribute moet een foto zijn.',
    'mimes'                => ':attribute moet type: :values zijn.',
    'max'                  => [
        'file'    => ':attribute mag niet groter zijn dan :max kilobytes.',
    ],


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'Naam',
        'animaltype_id' => 'Soort dier',
        'updatetype_id' => 'Soort update',
        'registration_date' => 'Aanmelddatum',
        'breed_id' => 'Ras',
        'gendertype_id' => 'Geslacht',
        'phone_number' => 'Telefoonnummer',
        'email_address' => 'Emailadres',
        'description' => 'Omschrijving',
        'tablegroup_id' => 'Type',
        'animal_image' => 'Afbeelding',
        'start_date' => 'Datum',
        'text' => 'Tekst',
        'employee_id' => 'Medewerker',
        'doctype_id' => 'Documentsoort',
        'document' => 'Document',
        'end_date' => 'Afmelddatum',
        'endtype_id' => 'Afmeldreden',
        'end_description' => 'Toelichting',
    ],

];
