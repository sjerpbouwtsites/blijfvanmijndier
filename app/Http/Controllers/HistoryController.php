<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\History;
use App\Animal;
use App\Shelter;
use App\Guest;
use App\Owner;
use DateTime;

class HistoryController extends Controller
{

    function __construct()
    {
        parent::__construct('histories', 'animals');
    }

    public function indexanimals(Request $request, $source_id)
    {
        $source_type = $this->GetLinkType($request);
        $histories = History::where([['source_type', $source_type], ['source_id', $source_id]])->orderBy('history_date', 'desc')->get();

        $source_object = $this->GetObjectData($source_type, $source_id);

        foreach ($histories as $history) {
            $link_object = $this->GetObjectData($history->link_type, $history->link_id);

            $history->history_date = $this->FormatDate($history->history_date);
            $history->link_label = $link_object['name_label'];
            $history->link_name = $link_object['name'];
            $history->urlType = $link_object['link_type'];
            $history->urlId = $link_object['link_id'];
            $history->actionDesc = $this->GetActionDescription($history->action);
        }

        $menuItems = $this->GetMenuItems('animals');

        $data = array(
            'histories' => $histories,
            'source_label' => $source_object['name_label'],
            'source_name' => $source_object['name'],
            'source_id' => $source_id,
            'source_type' => $source_type,
            'menuItems' => $menuItems
        );

        return view("history.index")->with($data);
    }

    public function index(Request $request, $source_id)
    {
        $source_type = $this->GetLinkType($request);
        $histories = History::where([['link_type', $source_type], ['link_id', $source_id]])->orderBy('history_date', 'desc')->get();

        $source_object = $this->GetObjectData($source_type, $source_id);

        foreach ($histories as $history) {
            $link_object = $this->GetObjectData($history->source_type, $history->source_id);

            $history->history_date = $this->FormatDate($history->history_date);
            $history->link_label = $link_object['name_label'];
            $history->link_name = $link_object['name'];
            $history->urlType = $link_object['link_type'];
            $history->urlId = $link_object['link_id'];
            $history->actionDesc = $this->GetActionDescription($history->action);
        }

        $menuItems = $this->GetMenuItems($source_type);

        $data = array(
            'histories' => $histories,
            'source_label' => $source_object['name_label'],
            'source_name' => $source_object['name'],
            'source_id' => $source_id,
            'source_type' => $source_type,
            'menuItems' => $menuItems
        );

        return view("history.index")->with($data);
    }

    private function GetLinkType(Request $request)
    {

        list($link_type) = explode("/", $request->path());
        return $link_type;
    }

    private function GetActionDescription($action)
    {
        switch ($action) {
            case 'connect':
                $actionDesc = 'Gekoppeld';
                break;
            case 'unconnect':
                $actionDesc = 'Ontkoppeld';
                break;
        }

        return $actionDesc;
    }

    private function GetObjectData($link_type, $link_id)
    {

        switch ($link_type) {
            case 'animals':
                $animal = Animal::find($link_id);
                $name_label = 'Dier';
                $name = $animal->name;
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
            case 'guests':
                $guest = Guest::find($link_id);
                $name_label = 'Gastgezin';
                $name = $guest->name;
                $link_type = $link_type;
                $link_id = $link_id;
                break;
            case 'owners':
                $owner = Owner::find($link_id);
                $name_label = 'Eigenaar';
                $name = $owner->surname;
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

    public static function saveHistory($source_type, $source_id, $link_type, $link_id, $action)
    {
        $history = new History;

        $history->source_id = $source_id;
        $history->source_type = $source_type;
        $history->link_id = $link_id;
        $history->link_type = $link_type;
        $history->history_date = new DateTime();
        $history->action = $action;

        $history->save();
    }
}
