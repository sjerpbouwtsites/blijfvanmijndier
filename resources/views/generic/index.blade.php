@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3 class='titel-letter'>Overzicht {{$index_title}}</h3>
	   	<h5><a href="{{ URL::to($plural_name.'/create') }}" class="btn btn-primary">Toevoegen</a></h5>

			 @include('session_messages')

		<table class="table">
			<thead>
				<tr>
          @foreach ($index_columns as $column_names)
            <th>{{$column_names}}</th>
          @endforeach	
					<th>Pas aan</th>
					<th>Maya</th>
				</tr>
			</thead>
			<tbody>


				<?php echo $index_rows; ?>

			</tbody>
		</table>
	</div>  	
@stop

<style>
	td.bootstrap-ga-weg-ajb {
		padding: 0 !important;
	}
	.bootstrap-ga-weg-ajb a {
		padding: 8px;
		display: block;
	}
</style>