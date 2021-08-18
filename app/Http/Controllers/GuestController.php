<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guest;
use App\Animal;
use App\Table;
use App\Address;
use Illuminate\Support\Facades\Input;
use App\Tablegroup;
use App\Checkerboard;

class GuestController extends AbstractController
{

    public $required = [
        'name',
        'phone_number',
        'email_address',
        'city',
        'house_number',
        'street',
        'postal_code'
    ];


    public $uses_generic_index = false;
    public $index_columns = ['Naam', 'Adres', 'Telefoonnummer'];
    

    function __construct()
    {
        parent::__construct('guests');
        $this->tabs = $this->create_tabs();
    }

    public function available(){
        return $this->index(true);
    }

    public function unavailable(){
        return $this->index(false);
    }
    /**
     * @param beschikbaarheid. Mogelijk als null (index), true (beschikbare gg) en false (onbeschikbaren)
     */
    public function index($beschikbaarheid = null)
    {
        $guests = \App\Address::allWithAddress("App\\Guest");

        if ($beschikbaarheid === null) {
            // sorteren op naam
            $guests = $guests->sort(function ($a,$b) {
                $name_a = preg_replace('/\s/', '', strtolower($a->name));
                $name_b = preg_replace('/\s/', '', strtolower($b->name));
                return $name_a <=> $name_b; 
         });
         
        } else if ($beschikbaarheid === true) {
            $guests = $guests->sort(function ($a,$b) {
                return ($a->days_till_disabled() || 0) <=> ($b->days_till_disabled() || 0);
        });
        
    }else if ($beschikbaarheid === false) {
        $guests = $guests->sort(function ($a,$b) {
            return ($a->days_till_available() ||0 ) <=> ($b->days_till_available() || 0);
    });
    // straks nog omdraaien.
}

        $guests_to_grid = [];
        foreach ($guests as $guest) {
            
            $guest->create_prompts($beschikbaarheid);
            $guest->create_icons($beschikbaarheid);

            if ($beschikbaarheid === null) {
                // normale index
                $guests_to_grid[]=$guest;
            } else if ($beschikbaarheid === true){
                if (!$guest->today_disabled()) {
                    $guests_to_grid[]=$guest;
                }
            } else if ($beschikbaarheid === false) {
                if ($guest->today_disabled()) {
                    $guests_to_grid[]=$guest;
                }                
            } else {
                throw new \Exception('something weird with the availability param in index');
            }
        }

        if ($beschikbaarheid === false) {
            $guests_to_grid = \array_reverse($guests_to_grid);
        }
        
        $guests_to_grid = Checkerboard::set_checkerboard($guests_to_grid);

        $guest_grid = $this->get_view('guest.tabbed-grid', [
            'animal_grid_modifier' => 'animal-index',
            'guests' => $guests_to_grid
        ]);

        // function op main controller
        $this->add_app_body_css('tabbed-grid');

        return $this->get_view('guest.tabbed', [
          'guest_grid' => $guest_grid,
          'tabs' => $this->tabs,
        ]);

    }

    public function create_index_rows($models){
        $index_rows = '';
        foreach ($models as $model) {
            $index_rows .= "<tr>";

            $index_rows .= $this->wrap_in_show_link($model->id, $model->name);
            $index_rows .= $this->wrap_in_show_link($model->id, "$model->street $model->house_number $model->city");
            $index_rows .= $this->wrap_in_show_link($model->id, $model->phone_number);

            $index_rows .= "<td><a href='/".$this->plural."/".$model->id."/edit'>🖊</a></td>";
            $index_rows .= $this->focus_in_maya_cell($model->id);  
            $index_rows .= "</tr>";
        }
        return $index_rows;
    }

    /**
     * single show view & endpoint
     */
    public function show($guest_id)
    {
        $guest = $this->get_hydrated($guest_id);
        $guest->disabled_timestamps_as_dates();
        $animals = Animal::setAnimalArrayImages(
            Animal::where('guest_id', $guest->id)->get()
        );

        return $this->get_view("guest.show", [
            'tabs' => $this->tabs,
            'guest' => $guest,
            'animals' => $animals,
            'updates' => UpdateController::getUpdatesByLinkType('guests', $guest->id, 2),
            'behaviourList' => $guest->tables->where('tablegroup_id', Tablegroup::type_to_id('behaviour')),
            'hometypeList' => $guest->tables->where('tablegroup_id', Tablegroup::type_to_id('home_type')),
            'animaltypeList' => $guest->tables->where('tablegroup_id', Tablegroup::type_to_id('animal_type'))
        ]);
    }

    /**
     * single edit view & endpoint
     */
    public function edit($guest_id)
    {
        $guest = $this->get_hydrated($guest_id);
        $guest->disabled_timestamps_as_dates();
        return $this->get_view(
            'guest.edit',
            $this->guest_meta($guest)
        );
    }
    /**
     * new single edit view & endpoint
     */
    public function create()
    {
        return $this->get_view(
            "guest.edit",
            $this->guest_meta(new Guest),
        );
    }

    private function create_tabs(){

        $current_url = \url()->current();

        $all_guests_url = \url('/guests/');
        $available_guests_url = \url("/guests/available");
        $unavailable_guests_url = \url("/guests/unavailable");
        $new_guest_url =  \url("/guests/create");

        $tabs_data = [
            'all'   => [
                'url'=> $all_guests_url,
                'text'  => 'Alle GG',
                'active' => $all_guests_url === $current_url
            ],
            'available'   => [
                'url'=> $available_guests_url,
                'text'=> 'Beschikbare GG',
                'active' => $available_guests_url === $current_url
            ],
            'unavailable'   => [
                'url'=> $unavailable_guests_url,
                'text'=> 'Onbeschikbare GG',
                'active' => $unavailable_guests_url === $current_url
            ],
            'create'   => [
                'url'=> $new_guest_url,
                'text'=> 'Nieuwe GG',
                'active' => $new_guest_url === $current_url
            ],
                     
        ];        

        return $this->get_view("generic.tabs", [
            'tabs_data' => $tabs_data
        ]);

    }


    /**
     * finds guest by id and hydrates the guest.
     * alters the datetime to a date.
     * @return Guest
     * @param string id
     */
    public function get_hydrated(string $id): Guest
    {
        $nude_guest = Guest::find($id);
        return Address::hydrateWithAddress($nude_guest);
    }

    /**
     * one day move to Tablemodel. but requires moving of lots of
     * reaaally confused data / methods. 
     * 
     * generic function for getting behaviourList, vaccinationList, hometypeList and their checked ones.
     * @param Guest guest instance
     * @param Array skip. which of behaviour, vaccination or hometype to skip.
     * @return Array with behaviourList, vaccinationList, hometypeList and their id/description collections; also behaviourListChecked, etc. 
     */
    private function guest_meta(Guest $guest, $skip = array()): array
    {
        $to_return = [];
        foreach (['behaviour', 'animal_type', 'home_type', 'own_animal_type'] as $group) {
            if (in_array($group, $skip)) continue;
            $list_name = $group . "List";

            // all in this group.
            $all_in_group = Table::All()->where(
                'tablegroup_id',
                Tablegroup::type_to_id($group)
            );
            $to_return[$list_name] = $all_in_group;

            // all in this group checked, complete objects
            $all_checked_ids = $guest->tables->where('tablegroup_id', Tablegroup::type_to_id($group))->pluck('id')->toArray();
            $complete_and_checked = [];
            foreach ($all_in_group as $one_of_all) {
                if (in_array($one_of_all['attributes']['id'], $all_checked_ids)) {
                    $complete_and_checked[] = $one_of_all;
                }
            }
            $to_return[$list_name . 'Checked'] = $complete_and_checked;

            // all checked in this group, id list.
            $to_return['checked_' . $group . 's'] = $all_checked_ids;
        }
        $to_return['guest'] = $guest;
        $to_return['tabs'] = $this->tabs;
        return $to_return;
    }



    /**
     * creates new guest if request does not non-null id prop
     * references Model's own attributes to set request values to self
     * @return bool for success
     * @param Request request the incoming post according to laravel
     * @param string address_id the uuid of the related Address
     */
    public function create_or_save(Request $request, string $address_id): bool
    {
        // create new guest or use existing.
        $guest = $this->get_model_instance($request, Guest::class);
        foreach ($guest['own_attributes'] as $key) {
            $guest->$key = $request->$key;
        }
        $guest->address_id = $address_id;
        $guest->disabled = Input::get('disabled') === 'on' ? 1 : 0;

        $guest->disabled_from = empty($guest->disabled_from) ? "2021-01-01" : $guest->disabled_from;
        $guest->disabled_untill = empty($guest->disabled_untill) ? "2021-01-01" : $guest->disabled_untill;

        // extra save to get id
        if ($request->id === null) {
            $guest->save();
        }

        $tables = Input::has('tables')
            ? Input::get('tables')
            : [];

        $guest->tables()->sync($tables);

        //dd($guest);
        $guest->save();
        return true;
    }
}
