<h4>{{ $title }}</h4>

@if(!$list->isEmpty()) 
	<ul>
	    @foreach ($list as $listItem)
	        <div class="checkbox">
	            <li>{{ $listItem->description }}</li>
	        </div>
	    @endforeach 
	</ul>
@else
	Geen selectie
@endif	
