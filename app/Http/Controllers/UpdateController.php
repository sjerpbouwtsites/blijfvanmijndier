<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Update;
use App\Animal;
use App\Guest;
use App\Shelter;
use DateTime;

class UpdateController extends Controller
{

    function __construct()
    {
        parent::__construct('updates', 'animals'); // this controller is more bespoke with menu items.
    }

    public function showall($type)
    {
        $updates = $this->getLatestUpdates(false, 70, $type);
        // KEEP THIS BUT MODERNIZE
        $menuItems = $this->GetMenuItems('animals');

        $selection = $type == "selection" ? "active" : "";
        $showall = $type == "showall" ? "active" : "";

        $data = array(
            'updates' => $updates,
            'menuItems' => $menuItems,
            'selection' => $selection,
            'showall' => $showall
        );

        return view("update.showall")->with($data);
    }

    public function index(Request $request, $link_id)
    {

        $link_type = $this->GetLinkType($request, $link_id);
        $object = $this->GetObjectData($link_type, $link_id);

        $updates  = Update::where([['link_type', $link_type], ['link_id', $link_id]])->orderBy('start_date', 'desc')->orderBy('id', 'desc')->get();

        foreach ($updates as $update) {
            $update->start_date = $this->FormatDate($update->start_date);
            $update->employeeName = $this->getDescription($update->employee_id);
            $update->updatetypeDesc = $this->getDescription($update->updatetype_id);
        }

        // KEEP THIS BUT MODERNIZE
        $menuItems = $this->GetMenuItems($link_type);

        $data = array(
            'updates' => $updates,
            'menuItems' => $menuItems,
            'name' => $object['name'],
            'link_type' => $link_type,
            'link_id' => $link_id
        );

        return view("update.index")->with($data);
    }

    public function show(Request $request, $link_id, $id)
    {
        $update = Update::find($id);
        $update->start_date = $this->FormatDate($update->start_date);
        $employeeName = $this->getDescription($update->employee_id);
        $update->updatetypeDesc = $this->getDescription($update->updatetype_id);

        $link_type = $this->GetLinkType($request, $link_id);
        $object = $this->GetObjectData($link_type, $link_id);
        // KEEP THIS BUT MODERNIZE
        $menuItems = $this->GetMenuItems($link_type);

        $data = array(
            'update' => $update,
            'menuItems' => $menuItems,
            'name' => $object['name'],
            'name_label' => $object['name_label'],
            'type' => $link_type,
            'link_id' => $link_id,
            'employeeName' => $employeeName
        );

        return view("update.show")->with($data);
    }

    public function edit(Request $request, $link_id, $id)
    {
        $update = Update::find($id);
        $data = $this->GetUpdateData($update);

        return view("update.edit")->with($data);
    }

    public function create(Request $request, $link_id)
    {

        $link_type = $this->GetLinkType($request);

        $update = new Update;
        $update->link_id = $link_id;
        $update->link_type = $link_type;
        $update->start_date = date('Y-m-d');
        $data = $this->GetUpdateData($update);

        return view("update.edit")->with($data);
    }

    public function store(Request $request, $link_id)
    {
        $validator = $this->validateUpdate();

        if ($validator->fails()) {
            return redirect($request->link_type . '/' . $link_id . '/updates/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveUpdate($request, $link_id, null);
            Session::flash('message', 'Update succesvol toegevoegd!');
            return redirect($request->link_type . '/' . $link_id . '/updates/');
        }
    }

    public function update(Request $request, $link_id, $update_id)
    {
        $validator = $this->validateUpdate();

        if ($validator->fails()) {
            return redirect($request->link_type . '/' . $link_id . '/updates/' . $update_id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveUpdate($request, $link_id, $update_id);
            Session::flash('message', 'Update succesvol gewijzigd!');
            return redirect($request->link_type . '/' . $link_id . '/updates/' . $update_id);
        }
    }

    public static function getUpdatesByLinkType($link_type, $link_id, $limit)
    {

        $updates = Update::where([['link_type', $link_type], ['link_id', $link_id]])->orderBy('start_date', 'desc')->limit($limit)->get();
        $length = 70;

        foreach ($updates as $update) {
            if (strlen($update->text) <= $length) {
                $update->smallText = $update->text;
            } else {
                $update->smallText = substr($update->text, 0, $length) . ' ...';
            }

            $update->start_date = HelperController::FormatDate($update->start_date);
            $update->url = $link_type . '/' . $link_id . '/updates/' . $update->id;
        }

        return $updates;
    }

    public static function getLatestUpdates($limit, $length, $type)
    {
        if ($limit) {
            $updates = Update::orderBy('start_date', 'desc')->orderBy('id', 'desc')->limit(5)->get();
        } elseif ($type == "selection") {
            $date = new DateTime();
            $date->modify('-14 day');
            $formatted_date = $date->format('Y-m-d H:i:s');

            $updates = Update::where('start_date', '>', $formatted_date)->orderBy('start_date', 'desc')->orderBy('id', 'desc')->get();
        } else {
            $updates = Update::orderBy('start_date', 'desc')->orderBy('id', 'desc')->get();
        }

        foreach ($updates as $update) {
            $update->start_date = HelperController::FormatDate($update->start_date);
            $update->employeeName = HelperController::getDescription($update->employee_id);
            $update->name_label = UpdateController::GetObjectData($update->link_type, $update->link_id)['name_label'];
            $update->name = UpdateController::GetObjectData($update->link_type, $update->link_id)['name'];
            if (strlen($update->text) <= $length) {
                $update->smallText = $update->text;
            } else {
                $update->smallText = substr($update->text, 0, $length) . ' ...';
            }
        }

        return $updates;
    }

    private function GetUpdateData($update)
    {

        $object = $this->GetObjectData($update->link_type, $update->link_id);
        // KEEP THIS BUT MODERNIZE
        $menuItems = $this->GetMenuItems($object['link_type']);

        $employees = $this->GetTableList($this->employeeId);
        $employees->prepend('Selecteer medewerker', '0');

        $updatetypes = $this->GetTableList($this->updatetypeId);
        $updatetypes->prepend('Selecteer soort update', '0');

        $data = array(
            'update' => $update,
            'menuItems' => $menuItems,
            'updatetypes' => $updatetypes,
            'name' => $object['name'],
            'link_type' => $object['link_type'],
            'link_id' => $object['link_id'],
            'employees' => $employees
        );

        return $data;
    }

    private function validateUpdate()
    {
        $rules = array(
            'updatetype_id' => 'required|numeric|min:1',
            'employee_id' => 'required|numeric|min:1',
            'start_date'  => 'required',
            'text'        => 'required',
        );

        return Validator::make(Input::all(), $rules);
    }

    private static function GetObjectData($link_type, $link_id)
    {

        switch ($link_type) {
            case 'animals':
                $animal = Animal::find($link_id);
                $name_label = 'Dier';
                $name = $animal->name;
                $link_type = $link_type;
                $link_id = $link_id;
                break;
            case 'guests':
                $guest = Guest::find($link_id);
                $name_label = 'Gastgezin';
                $name = $guest->name;
                $link_type = $link_type;
                $link_id = $link_id;
                break;
            case 'shelters':
                $shelter = Shelter::find($link_id);
                $name_label = 'Pension';
                $name = $shelter->name;
                $link_type = $link_type;
                $link_id = $link_id;
                break;
        }

        $data = array(
            'name_label' => $name_label,
            'name' => $name,
            'link_type' => $link_type,
            'link_id' => $link_id
        );

        return $data;
    }

    private function GetLinkType(Request $request)
    {

        list($link_type) = explode("/", $request->path());
        return $link_type;
    }

    private function saveUpdate(Request $request, $link_id, $update_id)
    {
        if ($update_id !== null) {
            $update = Update::find($update_id);
        } else {
            $update = new Update;
        }

        $update->link_id = $link_id;
        $update->link_type = $request->link_type;
        $update->start_date = $request->start_date;
        $update->employee_id = $request->employee_id;
        $update->updatetype_id = $request->updatetype_id;
        $update->text = $request->text;

        $update->save();
    }
}
