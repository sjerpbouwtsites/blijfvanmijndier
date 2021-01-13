<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Table;
use App\Tablegroup;
use App\MenuItem;

class TableController extends Controller
{

    function __construct()
    {
        parent::__construct('tables');
    }

    public function index()
    {
        $tables = Table::all()->sort();
        $tablegroup_id = 0;

        if (Input::has('tablegroup_id') && Input::get('tablegroup_id') > 0) {
            $tables = Table::all()->where('tablegroup_id', Input::get('tablegroup_id'))->sort();
            $tablegroup_id = Input::get('tablegroup_id');
        } else {
            $tables = Table::all()->sort();
        }

        foreach ($tables as $table) {
            $table->tableGroupDesc = $this->getTableGroupDescription($table->tablegroup_id);
        }

        $tables = $tables->sortBy('tableGroupDesc');

        $types = Tablegroup::pluck('name', 'id')->reverse()->sort();
        $types->prepend('Alle tabellen', '0');

        $menuItems = $this->GetMenuItems('tables');

        $data = array(
            'tables' => $tables,
            'types' => $types,
            'tablegroup_id' => $tablegroup_id,
            'menuItems' => $menuItems
        );

        return view("table.index")->with($data);
    }

    public function create()
    {
        $table = new Table;
        $types = Tablegroup::pluck('name', 'id')->reverse();
        $types->prepend('Selecteer type', '0');

        $menuItems = $this->GetMenuItems('tables');

        $data = array(
            'table' => $table,
            'types' => $types,
            'menuItems' => $menuItems
        );

        return view("table.create")->with($data);
    }

    public function edit($id)
    {
        $table = Table::find($id);
        $types = Tablegroup::pluck('name', 'id')->reverse();
        $types->prepend('Selecteer type', '0');

        $menuItems = $this->GetMenuItems('tables');

        $data = array(
            'table' => $table,
            'types' => $types,
            'menuItems' => $menuItems
        );

        return view("table.edit")->with($data);
    }

    public function store(Request $request)
    {
        $validator = $this->validateTable();

        if ($validator->fails()) {
            return Redirect::to('tables/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveTable($request);
            Session::flash('message', 'Tabelregel succesvol toegevoegd!');
            return redirect()->action('TableController@index');
        }
    }

    public function update(Request $request)
    {
        $validator = $this->validateTable();

        if ($validator->fails()) {
            return redirect()->action('TableController@edit', $request->id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveTable($request);
            Session::flash('message', 'Tabelregel succesvol gewijzigd!');
            return redirect()->action('TableController@index');
        }
    }

    private function validateTable()
    {
        $rules = array(
            'description'   => 'required',
            'tablegroup_id' => 'required|numeric|min:1'
        );

        return Validator::make(Input::all(), $rules);
    }

    private function saveTable(Request $request)
    {
        if ($request->id !== null) {
            $table = Table::find($request->id);
        } else {
            $table = new Table;
        }

        $table->tablegroup_id = $request->tablegroup_id;
        $table->description = $request->description;
        $table->description2 = $request->description2;
        $table->save();
    }

    private function getTableGroupDescription($id)
    {
        $tableGroup = TableGroup::find($id);
        return $tableGroup->name;
    }
}
