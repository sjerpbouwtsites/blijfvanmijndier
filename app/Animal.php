<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Table;
use Illuminate\Support\Facades\DB;

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
	private $needUpdate; // TODO WEG
	private $updates_checked;

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


    /**
	 * Get all updates of an animal,
	 * checks if they are in date range
	 * waarom schrijf ik eigenlijk in het engels
	 * @return array with bools like:
	 * [
	 * 		needs_updates // eg is long enough in program
	 * 		has_updates 
	 * 		needs_owner_update 
	 * 		has_owner_update
	 * 		owner_update_in_range
	 * 		needs_caregiver_update // eg longer then 2 months in program
	 *      has_caregiver_update
	 *      caregiver_update_in_range
	 * 		needs_jaarevaluatie_update // eg longer then 10 months in program
	 *      has_jaarevaluatie_update
	 *      jaarevaluatie_in_range
	 * ]
     */
    public static function update_check(Animal $animal, $update_type_map)
    {

		$now_time = new \DateTime();

		$animal_id = $animal->id;
		$animal_registered_date = $animal->registration_date;

		$updates_checked = [
			'needs_updates'	=> false,
			'has_updates' => false,
			'needs_owner_update' => false,
			'has_owner_update' => false,
			'owner_update_in_range' => false,
			'needs_caregiver_update' => false,
			'has_caregiver_update'=> false,
			'caregiver_update_in_range' => false,
			'needs_jaarevaluatie_update' => false,
			'has_jaarevaluatie_update' => false,
			'jaarevaluatie_in_range' => false,
			'update_prompts' => '',
			'has_prompts'	=> false,
		];

		// IF NEEDS UPDATES
		if ((new \DateTime($animal_registered_date))->modify('+2 week') < $now_time) {
			$updates_checked['needs_updates'] = true;
			$updates_checked['needs_owner_update'] = true;
		}
		if ((new \DateTime($animal_registered_date))->modify('+2 month') < $now_time) {
			$updates_checked['needs_caregiver_update'] = true;
		}
		if ((new \DateTime($animal_registered_date))->modify('+10 month') < $now_time) {
			$updates_checked['needs_jaarevaluatie_update'] = true;
		}

		$updates = DB::select("SELECT * FROM updates WHERE link_type = 'animals' AND link_id = $animal_id");

		if (count($updates) > 0) {
			$updates_checked['has_updates'] = true;
		}

		// OWNER UPDATE CHECK
		if ($updates_checked['needs_owner_update']) {
			$owner_update_id = $update_type_map['descriptions']['Update eigenaar'];
			foreach($updates as $update) {
				if ($update->updatetype_id !== $owner_update_id) {
					continue;
				}
				$updates_checked['has_owner_update'] = true;
				$update_time = new \DateTime($update->updated_at);
				$update_time_plus_two_weeks = $update_time->modify("+2 week");

				if ($update_time_plus_two_weeks > $now_time) {
					$updates_checked['owner_update_in_range'] = true; 
				}
			}
		}
		
		// CAREGIVER UPDATE CHECK
		if ($updates_checked['needs_caregiver_update']) {
			$caregiver_update_id = $update_type_map['descriptions']['Contact hulpverlening'];
			
			foreach($updates as $update) {

				if ($update->updatetype_id !== $caregiver_update_id) {
					continue;
				}

				$updates_checked['has_caregiver_update'] = true;
				$update_time = new \DateTime($update->updated_at);
				$update_time_plus_two_months = $update_time->modify("+2 month");

				if ($update_time_plus_two_months > $now_time) {
					$updates_checked['caregiver_update_in_range'] = true; 
				}
			}
		}

		
		// jaarevaluatie UPDATE CHECK
		if ($updates_checked['needs_jaarevaluatie_update']) {
			$jaarevaluatie_update_id = $update_type_map['descriptions']['jaarevaluatie'];
			
			foreach($updates as $update) {

				if ($update->updatetype_id !== $jaarevaluatie_update_id) {
					continue;
				}

				$updates_checked['has_jaarevaluatie_update'] = true;
				$update_time = new \DateTime($update->updated_at);
				$update_time_plus_two_months = $update_time->modify("+2 month");

				if ($update_time_plus_two_months > $now_time) {
					$updates_checked['jaarevaluatie_update_in_range'] = true; 
				}
			}
		}

		$prompts = [];
		$icons = [];
		if ($updates_checked['needs_updates'] && !$updates_checked['has_updates']) {
			$icons[] = "heart";
			$prompts[] = "Eerste update nodig.";
		} 

		if ($updates_checked['needs_owner_update']) {
			if (!$updates_checked['has_owner_update']) {
				$prompts[] = "Eigenaar eerste contact.";
				$icons[] = "female";
			} else if(!$updates_checked['owner_update_in_range']) {
				$prompts[] = "Eigenaar vervolg contact.";
				$icons[] = "female";				
			}
		}

		if ($updates_checked['needs_caregiver_update']) {
			if (!$updates_checked['has_caregiver_update']) {
				$prompts[] = "Hulpverlening eerste contact.";
				$icons[] = "users";
			} else if(!$updates_checked['caregiver_update_in_range']) {
				$prompts[] = "Hulpverlening vervolg contact.";		
				$icons[] = "users";		
			}
		}
		
		if ($updates_checked['needs_jaarevaluatie_update']) {
			if (!$updates_checked['has_jaarevaluatie_update']) {
				$prompts[] = "Jaarevaluatie nodig.";
				$icons[] = "signout";
			} else if(!$updates_checked['caregiver_update_in_range']) {
				$icons[] = "signout";
				$prompts[] = "Jaarevaluatie te laat.";				
			}
		}
		$updates_checked['update_prompts'] = $prompts;
		$updates_checked['has_prompts'] = count($prompts) > 0;

		$updates_checked['icons'] = $icons;
		$updates_checked['has_icons'] = count($icons) > 0;		

		return $updates_checked;
    }

}
