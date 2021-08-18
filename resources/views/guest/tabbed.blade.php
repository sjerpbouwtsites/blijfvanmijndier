@extends('layout')

@section('content')

<?=$tabs?>

<div class="col-md-12" >

    @include('session_messages')

    <?=$guest_grid?>

</div>


@stop

