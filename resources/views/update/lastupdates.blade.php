
		<ul class="list-group action-menu">
     	    <li class="list-group-item menu-title"><i class="fa fa-pencil fa-fw"></i>&nbsp; Laatste updates</a></li>
      	    @foreach ($updates as $update)
    	    	<li class="list-group-item"><a href="{{ URL::to($update->url) }}">{{$update->start_date}} - {{$update->smallText}}</a></li>
    		@endforeach 	
		</ul>
