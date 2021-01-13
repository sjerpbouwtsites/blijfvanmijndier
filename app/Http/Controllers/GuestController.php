<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Guest;
use App\Animal;
use App\Table;
use App\Address;
use Illuminate\Support\Facades\Input;

class GuestController extends AbstractController
{

    protected $required = [
        'name',
        'phone_number',
        'email_address',
        'city',
        'house_number',
        'street',
        'postal_code'
    ];

    function __construct()
    {
        parent::__construct('guests');
    }

    /**
     * single show view & endpoint
     */
    public function show($guest_id)
    {
        $guest = $this->get_hydrated($guest_id);
        $animals = Animal::setAnimalArrayImages(
            Animal::where('guest_id', $guest->id)->get()
        );

        return $this->get_view("guest.show", [
            'guest' => $guest,
            'animals' => $animals,
            'updates' => UpdateController::getUpdatesByLinkType('guests', $guest->id, 2),
            'behaviourList' => $guest->tables->where('tablegroup_id', $this->behaviourId),
            'hometypeList' => $guest->tables->where('tablegroup_id', $this->hometypeId),
            'animaltypeList' => $guest->tables->where('tablegroup_id', $this->animaltypeId)
        ]);
    }

    /**
     * single edit view & endpoint
     */
    public function edit($guest_id)
    {
        $guest = $this->get_hydrated($guest_id);
        //$view_data = $this->GetGuestData($guest);
        // $meta_data = $this->guest_meta($guest);
        // $behaviourList = Table::All()->where('tablegroup_id', $this->behaviourId);
        // dump($meta_data);
        // dd($behaviourList);
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
            $this->GetGuestData(new Guest),
        );
    }

    /**
     * finds guest by id and hydrates the guest.
     * @return Guest
     * @param string id
     */
    public function get_hydrated(string $id): Guest
    {
        $nude_guest = Guest::find($id);
        return Address::hydrateWithAddress($nude_guest);
    }

    /**
     * generic function for getting behaviourList, vaccinationList, hometypeList and their checked ones.
     * @param Guest guest instance
     * @param Array skip. which of behaviour, vaccination or hometype to skip.
     * @return Array with behaviourList, vaccinationList, hometypeList and their id/description collections; also behaviourListChecked, etc. 
     */
    private function guest_meta(Guest $guest, $skip = array()): array
    {
        $to_return = [];
        foreach (['behaviour', 'animaltype', 'hometype'] as $group) {
            if (in_array($group, $skip)) continue;
            $list_name = $group . "List";
            $id_name = $group . "Id";

            // all in this group.
            $all_in_group = Table::All()->where(
                'tablegroup_id',
                $this->$id_name
            );
            $to_return[$list_name] = $all_in_group;

            // all in this group checked, complete objects
            $all_checked_ids = $guest->tables->where('tablegroup_id', $this->$id_name)->pluck('id')->toArray();
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
        return $to_return;
    }

    /**
     * Helper van edit en create
     */
    private function GetGuestData($guest)
    {
        $behaviourList = Table::All()->where('tablegroup_id', $this->behaviourId);

        $hometypeList = Table::All()->where('tablegroup_id', $this->hometypeId);
        $animaltypeList = Table::All()->where('tablegroup_id', $this->animaltypeId);

        $checked_behaviours = $guest->tables()->where('tablegroup_id', $this->behaviourId)->pluck('tables.id')->toArray();
        $checked_hometypes = $guest->tables()->where('tablegroup_id', $this->hometypeId)->pluck('tables.id')->toArray();
        $checked_animaltypes = $guest->tables()->where('tablegroup_id', $this->animaltypeId)->pluck('tables.id')->toArray();

        $menuItems = $this->GetMenuItems('guests');

        $data = array(
            'guest' => $guest,
            'menuItems' => $menuItems,
            'behaviourList' => $behaviourList,
            'checked_behaviours' => $checked_behaviours,
            'hometypeList' => $hometypeList,
            'checked_hometypes' => $checked_hometypes,
            'animaltypeList' => $animaltypeList,
            'checked_animaltypes' => $checked_animaltypes
        );

        return $data;
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

        // extra save to get id
        if ($request->id === null) {
            $guest->save();
        }

        $tables = Input::has('tables')
            ? Input::get('tables')
            : [];
        $guest->tables()->sync($tables);
        $guest->save();
        return true;
    }
}
