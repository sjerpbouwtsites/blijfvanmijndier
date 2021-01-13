<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
	private $animalStatusDesc;
	private $breedDesc;
	private $animaltypeDesc;
	private $gendertypeDesc;
	public $animalImage;
	private $endtypeDesc;
	private $lastUpdate;
	private $lastUpdateSort;
	private $needUpdate;

	public function __construct()
	{
		$this->setAnimalImage();
	}

	public function tables()
	{
		return $this->belongsToMany(Table::class);
	}

	/**
	 * to call setAnimalImages on arrays of animals
	 * @return array of animals with images;
	 */
	public static function setAnimalArrayImages($animals)
	{
		foreach ($animals as $animal) {
			$animal->setAnimalImage();
		}
		return $animals;
	}

	/**
	 * When this has an id, find image on disk, else give placeholder; set as prop animalImage
	 */
	public function setAnimalImage()
	{
		if (!$this->id) {
			return; // new model.
		}
		$image = 'img/' . 'animal_' . $this->id . '.jpg';

		if (file_exists($image)) {
			$this->animalImage = $image;
		} else {
			$this->animalImage = 'img/placeholder.jpg';
		}
	}
}
