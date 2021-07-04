<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Guest extends Model
{

    private $disabled_dates_mangled = false;
    private $disabled_from_original = null;
    private $disabled_untill_original = null;

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

    public function tables()
    {
        
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
            
        return $dt->format('l F Y');
    }
    public function disabled_untill_friendly(){
        setlocale(LC_ALL, 'nl_NL');
        $dt = $this->disabled_dates_mangled 
            ? new \DateTime($this->disabled_untill_original)
            : new \DateTime($this->disabled_untill);        
            
        return $dt->format('l F Y');
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

}
