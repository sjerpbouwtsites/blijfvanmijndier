<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Vet;
use App\Owner;
use App\Guest;
use App\Location;
use App\Shelter;
use App\Address;

/**
 * has the basic controller functions
 * index, show, edit, create, store, update, get_hydrated, create_or_save
 * to be overwritten by the not abstract class when required.
 */
abstract class AbstractController extends Controller
{

  public string $view_prefix;
  public string $singular;
  public string $plural;
  public string $model_name;
  public $validator;

  /**
   * please override me.
   */
  public array $validator_rules = [];

  function __construct($name)
  {
    /**
     * used in parent for menu items
     */
    parent::__construct($name);

    // assumes plural used.
    $this->plural = $name;
    $this->view_prefix = mb_substr($name, 0, -1);
    $this->singular = $this->view_prefix;
    $this->model_name = ucfirst($this->singular);
  }

  /**
   * plenary view & root endpoint
   */
  public function index()
  {
    return $this->get_view($this->view_prefix . ".index", [
      $this->plural => Address::allWithAddress("App\\" . $this->model_name)->sortBy('name'),
    ]);
  }

  /**
   * single view & endpoint
   */
  public function show($id)
  {
    return $this->get_view($this->view_prefix . ".show", [
      $this->singular => $this->get_hydrated($id),
    ]);
  }

  /**
   * single edit view & endpoint
   */
  public function edit($id)
  {
    return $this->get_view($this->view_prefix . ".edit", [
      $this->singular => $this->get_hydrated($id),
    ]);
  }

  /**
   * single create view & endpoint
   */
  public function create()
  {
    return $this->get_view($this->view_prefix . ".edit", [
      $this->singular => new $this->model_name,
    ]);
  }

  public function setValidator(): void
  {
    $this->validator = Validator::make(Input::all(), $this->validator_rules);
  }

  /**
   * where is posted to on create.
   */
  public function store(Request $request)
  {
    $this->setValidator();
    if ($this->validator->fails()) {
      return Redirect::to($this->plural . '/create')
        ->withErrors($this->validator)
        ->withInput();
    }
    $ai = Address::save_or_create_address(true);
    $this->create_or_save($request, $ai);
    Session::flash('message', 'Succesvol toegevoegd!');
    return redirect()->action($this->model_name . 'Controller@index');
  }

  /**
   * where is posted to on update
   */
  public function update(Request $request)
  {
    $this->setValidator();
    if ($this->validator->fails()) {
      return redirect()->action($this->model_name . 'Controller@edit', $request->id)
        ->withErrors($this->validator)
        ->withInput();
    }
    $ai = Address::save_or_create_address(false);
    $this->create_or_save($request, $ai);
    Session::flash('message', 'Succesvol gewijzigd!');
    return redirect()->action($this->model_name . 'Controller@show', $request->id);
  }

  /**
   * PLACEHOLDER
   */
  public function get_hydrated(string $id)
  {
    throw new \Exception('abstracts get_hydrated func is just a placeholder!');
  }

  /**
   * PLACEHOLDER!
   */
  public function create_or_save(Request $request, string $address_id)
  {
    throw new \Exception('abstracts create or save func is just a placeholder!');
  }
}
