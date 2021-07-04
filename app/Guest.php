<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Guest extends Model
{

    private $disabled_dates_mangled = false;
    private $disabled_from_original = null;
    private $disabled_untill_original = null;
    public $has_prompts = false;
    public $prompts = [];
    public $icons = [];
    public $has_icons = false;
    private static $_today_datetime = null; // 'caching'
    private $_days_till_disabled = null;
    private $_days_till_available = null;

    private static function get_today_datetime(){
        if (Guest::$_today_datetime === null) {
            Guest::$_today_datetime = new \DateTime();
        }
        return Guest::$_today_datetime;
    }

    /**
     * Those properties not coming from the address model.
     */
    protected $own_attributes = [
        'name',
        'phone_number',
        'email_address',
        'max_hours_alone',
        'disabled',
        'disabled_from',
        'disabled_untill',
        'text'
    ];

    public function get_first_name(){
        return explode(' ', $this->name)[0];
    }

    public function tables()
    {
        $today_datetime = new \DateTime();
        return $this->belongsToMany(Table::class);
    }

    /**
     * Get the address record associated with the guest.
     */
    public function address()
    {
        return $this->hasOne('App\Address', 'uuid', 'address_id')->get();
    }

    /**
     * Checks if today is after the disabled from and before the disabled untill. 
     * @return Bool 
     */
    public function today_disabled(){
        $today = new \DateTime();
        $disabled_from = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_from_original)
            : new \DateTime($this->disabled_from);
        $disabled_untill = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_untill_original)
            : new \DateTime($this->disabled_untill);

        return $disabled_from->modify('-1 day') < $today && $disabled_untill->modify('+1 day') > $today;
    }
    
    public function disabled_from_friendly(){
        setlocale(LC_ALL, 'nl_NL');
        $dt = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_from_original)
            : new \DateTime($this->disabled_from);        
            
        return $dt->format('l d F Y');
    }
    public function disabled_untill_friendly(){
        setlocale(LC_ALL, 'nl_NL');
        $dt = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_untill_original)
            : new \DateTime($this->disabled_untill);        
            
        return $dt->format('l d F Y');
    }

    /**
     * return null if not disabled;
     * return 0 if now disabled
     * otherwise diff
     * @return int|null number of days untill disabled
     */
    public function days_till_disabled(){

        if ($this->_days_till_disabled !== null) {
            return $this->_days_till_disabled;
        }

        if (!$this->disabled) {
           $this->_days_till_disabled = null;
           return $this->_days_till_disabled;
        }
        
        if ($this->today_disabled()) {
            $this->_days_till_disabled = 0;
            return $this->_days_till_disabled;
        } 

        $disabled_from = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_from_original)
            : new \DateTime($this->disabled_from);
        
        $interval = $disabled_from->diff(Guest::get_today_datetime());
        $this->_days_till_disabled = $interval->days;
        return $this->_days_till_disabled;
        
    }

    /**
     * return 10000 if not disabled;
     * return 0 if now disabled
     * otherwise diff
     * @return int number of days untill disabled
     */
    public function days_till_available(){
        if ($this->_days_till_available) {
            return $this->_days_till_available;
        }

        if (!$this->disabled) {
           return $this->_days_till_available = null;
        }
        if (!$this->today_disabled()) {
            return $this->_days_till_available = 0;
        } 

        $disabled_untill = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_untill_original)
            : new \DateTime($this->disabled_untill);
        
        $interval = $disabled_untill->diff(Guest::get_today_datetime());
        return $this->_days_till_available = $interval->days;
        
    }

    public function get_animal_preference_string(){
        $animal_preference = $this->tables->where('tablegroup_id', Tablegroup::type_to_id('animal_type'));
        if (count($animal_preference) == 0) return '';

            $animal_preference_names = [];
            foreach($animal_preference as $ap) {
                $animal_preference_names[] = $ap->description;
            }
            return " - ".strtolower(implode(', ', $animal_preference_names)).".";
        
    }

    public function create_prompts($availability = null){
        if ($availability === null || $availability === true) {
            if ($this->disabled) {
                if ($this->today_disabled()) {
                    $this->prompts[] = "<strong>On</strong>beschikbaar";
                } else {

                    $dtd = $this->days_till_disabled();
    
                    if ($dtd < 15) {
                        $this->prompts[] = "<strong>On</strong>beschikbaar over $dtd dagen, vanaf ".$this->disabled_from_friendly();
                    } elseif ($dtd < 35) {
                        $this->prompts[] = "<strong>On</strong>beschikbaar over ".\round($dtd / 7)." weken, tot ".$this->disabled_from_friendly();
                    } elseif ($dtd < 180) {
                        $this->prompts[] = "Nog ".\round($dtd / 30.5)." maanden beschikbaar, tot ". $this->disabled_from_friendly();
                    } elseif ($dtd > 180) {
                        $this->prompts[] = "Meer dan een half jaar beschikbaar, tot ".$this->disabled_from_friendly();
                    }  
                 }
                }
        }
 
        if ($availability === false || $availability === null) {
            if ($this->today_disabled()) {
                $dta = $this->days_till_available();
                if ($dta < 15) {
                    $this->prompts[] = "Snel beschikbaar: over $dta dagen, vanaf ".$this->disabled_untill_friendly();
                } elseif ($dta < 35) {
                    $this->prompts[] = "Beschikbaar over ".\round($dta / 7)." weken, vanaf ".$this->disabled_untill_friendly();
                } elseif ($dta < 180) {
                    $this->prompts[] = "Pas over ".\round($dta / 30.5)." maanden beschikbaar, vanaf ". $this->disabled_untill_friendly();
                } elseif ($dta > 180) {
                    $this->prompts[] = "Lange termijn onbeschikbaar, tot ".$this->disabled_untill_friendly();
                }  
            }
        }        

        $this->has_prompts = count($this->prompts) > 0;
        return $this->prompts;
        
    }   

    public function create_icons($availability = null){
        if ($availability === null) {
            if ($this->today_disabled()) :
                $dta = $this->days_till_available();
                if ($dta < 30) {
                    $this->icons[] = Guest::make_icon_row(['hourglass', 'heart-o' ], "Binnen afzienbare tijd beschikbaar: $dta dagen.");
                } else {
                    $this->icons[] = Guest::make_icon_row(['chain-broken'], "Onbeschikbaar.");
                }
            elseif ($this->disabled): // als niet vandaag disabled
                $dtd = $this->days_till_disabled();
                if ($dtd < 90) {
                    $this->icons[] = Guest::make_icon_row(['clock-o', 'heart-o' ], "Slechts kort beschikbaar: $dtd dagen.");
                } else {
                    $this->icons[] = Guest::make_icon_row(['hourglass', 'sign-out'], "Nog enkele maanden beschikbaar.");
                }
            endif;
        } elseif ($availability === true) {
            if ($this->disabled && !$this->today_disabled()) {
                $dtd = $this->days_till_disabled();
                if ($dtd < 90) {
                    $this->icons[] = Guest::make_icon_row(['clock-o', 'heart-o' ], "Slechts kort beschikbaar: $dtd dagen.");
                } else {
                    $this->icons[] = Guest::make_icon_row(['hourglass', 'sign-out'], "Nog enkele maanden beschikbaar.");
                }                
            }
        } elseif ($availability === false) {
            if ($this->disabled && $this->today_disabled()) {
                $dta = $this->days_till_available();
                if ($dta < 30) {
                    $this->icons[] = Guest::make_icon_row(['hourglass', 'heart-o' ], "Binnen afzienbare tijd beschikbaar: $dta dagen.");
                } else {
                    $this->icons[] = Guest::make_icon_row(['chain-broken'], "Onbeschikbaar.");
                }              
            }
        }
        if (count($this->icons) > 0) {
            $this->has_icons = true;
        }
    }


    /**
     * removes the time signature from these timestamps if guest is disabled.
     * @return Guest
     */
    public function disabled_timestamps_as_dates(){

        if (!$this->disabled) {
            return;
        }

        $this->disabled_from_original = $this->disabled_from;
        $this->disabled_untill_original = $this->disabled_untill;
        $this->disabled_from = explode(' ', $this->disabled_from)[0];
        $this->disabled_untill = explode(' ', $this->disabled_untill)[0];
        $this->disabled_dates_mangled = true;
        return $this;
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
