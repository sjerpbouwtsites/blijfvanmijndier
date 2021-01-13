<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tablegroup;
use App\Table;
use App\MenuItem;

class TablegroupController extends Controller
{
    public function index()
    {
        $tablegroups = Tablegroup::all();
        $menuItems = $this->GetMenuItems(''); // @TODO hier was geen param meegegevn? bestaat deze indexc?

        return view("tablegroup.index", ['tablegroups' => $tablegroups, 'menuItems' => $menuItems]);
    }

    public function show($id)
    {
        $tablegroup = Tablegroup::find($id);
        $tables = Table::where('tablegroup_id', $id)->get();
        $menuItems = $this->GetMenuItems();

        return view("table.index", ['tables' => $tables, 'groupname' => $tablegroup->first()->name, 'menuItems' => $menuItems]);
    }
}
