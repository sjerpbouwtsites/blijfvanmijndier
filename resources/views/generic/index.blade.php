@extends('layout')

@section('content')
	<div class="col-md-12">
		<h3>Overzicht {{$index_title}}</h3>
	   	<h5><a href="{{ URL::to($plural_name.'/create') }}" class="btn btn-primary">Toevoegen</a></h5>

			 @include('session_messages')

		<table class="table table-hover">
			<thead>
				<tr>
          @foreach ($index_columns as $column_names)
            <th>{{$column_names}}</th>
          @endforeach	
					<th>Pas aan</th>
				</tr>
			</thead>
			<tbody>


				<?php echo $index_rows; ?>

			</tbody>
		</table>
	</div>  	
@stop
