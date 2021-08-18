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
			$this->animalImage = \url($image);
		} else {
			$this->animalImage = \url('img/placeholder.jpg');
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
	 * 		owner_update_on_time // more precisely, is not yet too late
	 * 		needs_caregiver_update // eg longer then 2 months in program
	 *      has_caregiver_update
	 *      caregiver_update_on_time // more precisely, is not yet too late
	 * 		needs_jaarevaluatie_update // eg longer then 10 months in program
	 *      has_jaarevaluatie_update
	 *      jaarevaluatie_on_time // more precisely, is not yet too late
	 * ]
     */
    public static function update_check(Animal $animal, $update_type_map)
    {

		$now_time = new \DateTime();

		$animal_id = $animal->id;
		$animal_registered_date = $animal->registration_date;

		// gastgezin
		// hulpverlening
		// pension
		// eigenaar
		// jaarevaluatie

		$uc = [
			
			'in_todo_list' => false,
			'needs_updates'	=> false, // time in system means there should be updates
			'has_updates' => false,
			'needs_owner_update' => false, 
			'has_owner_update' => false,
			'owner_update_on_time' => false,
			'needs_caregiver_update' => false,
			'has_caregiver_update'=> false,
			'caregiver_update_on_time' => false,

			'needs_pension_guest_update' => false,
			'has_pension_guest_update'=> false,
			'pension_guest_update_on_time' => false,			

			'needs_jaarevaluatie_update' => false,
			'has_jaarevaluatie_update' => false,
			'jaarevaluatie_on_time' => false,
			'update_prompts' => '',
			'has_prompts'	=> false,
			'days_behind_to_max' => [0], // push en dan max
			'days_behind'	=> 0,
			
			// debug
			'now'	=> $now_time,
			'areg_date'=> $animal_registered_date,

		];

		// IF NEEDS UPDATES
		if ((new \DateTime($animal_registered_date))->modify('+2 week') < $now_time) {
			$uc['needs_updates'] = true;
			$uc['needs_owner_update'] = true;
		}
		if ((new \DateTime($animal_registered_date))->modify('+2 month') < $now_time) {
			$uc['needs_caregiver_update'] = true;
		}
		if ((new \DateTime($animal_registered_date))->modify('+2 month') < $now_time) {
			$uc['needs_pension_guest_update'] = true;
		}		
		if ((new \DateTime($animal_registered_date))->modify('+10 month') < $now_time) {
			$uc['needs_jaarevaluatie_update'] = true;
		}

		$updates = DB::select("SELECT * FROM updates WHERE link_type = 'animals' AND link_id = $animal_id");

		if (count($updates) > 0) {
			$uc['has_updates'] = true;
		}

		// OWNER UPDATE CHECK
		if ($uc['needs_owner_update']) :
			$owner_update_id = $update_type_map['descriptions']['Update eigenaar'];
			foreach($updates as $update) {
				if ($update->updatetype_id !== $owner_update_id) {
					continue;
				}
				$uc['has_owner_update'] = true;
				$update_time = new \DateTime($update->start_date);
				$update_time_plus_two_weeks = $update_time->modify("+2 week");

				if ($update_time_plus_two_weeks > $now_time) {
					$uc['owner_update_on_time'] = true; 
					break;
				} 
				$uc['days_behind_to_max'][] = $update_time_plus_two_weeks->diff($now_time)->days; 
				
			}
			if (!$uc['has_owner_update']) {
				$uc['days_behind_to_max'][] = (new \DateTime($animal_registered_date))->diff($now_time)->days; 
			}
		endif; // needs owner update
		
		
		// CAREGIVER UPDATE CHECK
		if ($uc['needs_caregiver_update']) :
			$caregiver_update_id = $update_type_map['descriptions']['Contact hulpverlening'];
			
			foreach($updates as $update) {

				if ($update->updatetype_id !== $caregiver_update_id) {
					continue;
				}

				$uc['has_caregiver_update'] = true;
				$update_time = new \DateTime($update->start_date);
				$update_time_plus_two_months = $update_time->modify("+2 month");

				if ($update_time_plus_two_months > $now_time) {
					$uc['caregiver_update_on_time'] = true;
					break; 
				}
				$uc['days_behind_to_max'][] = $update_time_plus_two_months->diff($now_time)->days; 
			}
			if (!$uc['has_caregiver_update']) {
				$uc['days_behind_to_max'][] = (new \DateTime($animal_registered_date))->diff($now_time)->days; 
			}
		endif; // needs caregiver update

		// PENSION GUEST UPDATE CHECK
		if ($uc['needs_pension_guest_update']) :
			$pension_update_id = $update_type_map['descriptions']['Contact pension'];
			$guest_update_id = $update_type_map['descriptions']['Contact gastgezin'];
			
			foreach($updates as $update) {

				if ($update->updatetype_id !== $pension_update_id && $update->updatetype_id !== $guest_update_id) {
					continue;
				}

				$uc['has_pension_guest_update'] = true;
				$update_time = new \DateTime($update->start_date);
				$update_time_plus_two_months = $update_time->modify("+2 month");

				if ($update_time_plus_two_months > $now_time) {
					$uc['pension_guest_update_on_time'] = true;
					break; 
				}
				$uc['days_behind_to_max'][] = $update_time_plus_two_months->diff($now_time)->days; 
			}
			if (!$uc['has_pension_guest_update']) {
				$uc['days_behind_to_max'][] = (new \DateTime($animal_registered_date))->diff($now_time)->days; 
			}
		endif; // needs pension guest update		
		
		// jaarevaluatie UPDATE CHECK
		if ($uc['needs_jaarevaluatie_update']) :
			$jaarevaluatie_update_id = $update_type_map['descriptions']['jaarevaluatie'];
			
			foreach($updates as $update) {

				if ($update->updatetype_id !== $jaarevaluatie_update_id) {
					continue;
				}

				$uc['has_jaarevaluatie_update'] = true;
				$update_time = new \DateTime($update->start_date);
				$update_time_plus_two_months = $update_time->modify("+2 month");

				if ($update_time_plus_two_months > $now_time) {
					$uc['jaarevaluatie_update_on_time'] = true;
					break; 
				}
				$uc['days_behind_to_max'][] = $update_time_plus_two_months->diff($now_time)->days;
			}
			if (!$uc['has_jaarevaluatie_update']) {
				$uc['days_behind_to_max'][] = (new \DateTime($animal_registered_date))->diff($now_time)->days; 
			}
		endif; // needs jaarevalutie update

		$prompts = [];
		$icons = [];

		// if ($uc['needs_updates'] && !$uc['has_updates']) {
		// 	$icons[] = Animal::make_icon_row(["heart"], 'GEEN UPDATES. Dit dier is langer dan twee weken in het project, maar er zijn nog geen updates') ;
		// 	$prompts[] = "Updates missen &uuml;berhaupt.";
		// 	$uc['in_todo_list'] = true;
		// } 

		if ($uc['needs_owner_update']) :

			if (!$uc['has_owner_update'] || !$uc['owner_update_on_time']) {
				$uc['in_todo_list'] = true;
			}
			
			if (!$uc['has_owner_update']) {
				 $icons[] = Animal::make_icon_row(["female"], 'EIGENAAR UPDATE. Dit dier is langer dan twee weken in het project, maar er is geen eigenaar update.');
				$prompts[] =  "Eigenaar eerste contact.";
			} else if(!$uc['owner_update_on_time']) {
				$icons[] = Animal::make_icon_row(['repeat',"female"], 'EIGENAAR UPDATE VERVOLG. Er was eerder contact met de eigenaar, maar dat is langer dan twee weken geleden.');
				$prompts[] = "Eigenaar vervolg contact.";
			}
		endif; // needs owner update

		if ($uc['needs_caregiver_update']) :
			if (!$uc['has_caregiver_update'] || !$uc['caregiver_update_on_time']){
				$uc['in_todo_list'] = true;
			}
			if (!$uc['has_caregiver_update']) {
				$icons[] = Animal::make_icon_row(["users"], 'HULPVERLENER UPDATE. Dit dier is langer dan twee maanden in het project, maar de hulpverlening is nog niet gesproken.');
				$prompts[] = "Hulpverlening eerste contact.";
			} else if(!$uc['caregiver_update_on_time']) {
				$icons[] = Animal::make_icon_row(['repeat',"users"], 'HULPVERLENER UPDATE. Er was eerder contact met de hulpverlening, maar dat is langer dan twee maanden geleden.');
				$prompts[] = "Hulpverlening vervolg contact.";		
			}
		endif; // needs caregiver update
		
		if ($uc['needs_pension_guest_update']) :
			if (!$uc['has_pension_guest_update'] || !$uc['pension_guest_update_on_time']){
				$uc['in_todo_list'] = true;
			}
			if (!$uc['has_pension_guest_update']) {
				$icons[] = Animal::make_icon_row(["home"], 'GASTGEZIN/PENSION UPDATE. Dit dier is langer dan twee maanden in het project, maar het verblijf is nog niet gesproken.');
				$prompts[] = "Verblijf eerste contact.";
			} else if(!$uc['pension_guest_update_on_time']) {
				$icons[] = Animal::make_icon_row(['repeat',"home"], 'GASTGEZIN/PENSION UPDATE. Er was eerder contact met het verblijf, maar dat is langer dan twee maanden geleden.');
				$prompts[] = "verblijf gastgezin/pension vervolg contact.";		
			}
		endif; // needs pension guest update

		if ($uc['needs_jaarevaluatie_update']) :
			if (!$uc['has_jaarevaluatie_update']){
				$uc['in_todo_list'] = true;
			}
			if (!$uc['has_jaarevaluatie_update']) {
				$icons[] = Animal::make_icon_row(["sign-out"], 'JAAREVALUATIE UPDATE. Dit dier is langer dan tien maanden in het project, het wordt tijd voor een evaluatiegesprek.');
				$prompts[] = "Jaarevaluatie nodig.";
			} 
		endif; // needs jaarevaluatie update

		$uc['update_prompts'] = $prompts;
		$uc['has_prompts'] = count($prompts) > 0;

		$uc['icons'] = $icons;
		$uc['has_icons'] = count($icons) > 0;		

		$uc['days_behind'] = \max($uc['days_behind_to_max']);

		return $uc;
    }

	/**
	 * just an helper for update checker to cut code jungle
	 * @param string text_in_title_attr goes in to the title attribute of that row of icons.
	 * @param array font_awesome_47_classes stringarray with fa 4.7 classes
	 */
	private static function make_icon_row(array $font_awesome_47_classes, $text_in_title_attr){
		return [
			'fa_classes' => $font_awesome_47_classes,
			'title_attr' => $text_in_title_attr
		];
	}

}
