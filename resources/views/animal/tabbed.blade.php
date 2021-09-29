@extends('layout')

@section('content')

<?=$tabs?>

<div class="col-md-12" >

    @include('session_messages')

    <?=$animal_grid?>

</div>


@stop

