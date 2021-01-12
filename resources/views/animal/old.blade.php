<div class="col-md-12">
  <div class="col-md-4"><h3>Overzicht dieren uit project</h3></div>
</div>
<div class="col-md-12">

  <div class="card-deck">

    @foreach ($old_animals as $animal)
    <a href="{{ URL::to('animals/' . $animal->id) }}">
    <div class="card panel">
      <img class="card-img-top" src="{{ $animal->animalImage }}" alt="{{ $animal->name }}" width="150" height="150">
      <div class="card-block">
        <h4 class="card-title">{{ $animal->name }}</h4>
        <p class="card-text">{{ $animal->breedDesc }}</p>
      </div>
    </div>
    </a>
    @endforeach	
  </div>	
</div>  	