<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

/**
 * A layer between the controller and the actual instances.
 * 
 * has the basic controller functions
 * index, show, edit, create, store, update, get_hydrated, create_or_save
 * to be overwritten by the not abstract class when required.
 */
abstract class AbstractController extends Controller
{

  public $view_prefix;
  public $singular;
  public $plural;
  public $model_name;
  public $validator;
  // set in setValidator
  public $validator_config = [];
  public $geolocation_failure = false;
  public $uses_generic_index = false;

  // please override.
  public $required = [];

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
 * used to create table rows in index
 */
  public function wrap_in_show_link($id, $to_wrap){
    $wrapped = '';
    $wrapped .= "<td class='bootstrap-ga-weg-ajb'>";
    $wrapped .= "<a href='/".$this->plural."/".$id." '>";
    $wrapped .= $to_wrap;
    $wrapped .= "</a>";
    $wrapped .= "</td>";
    return $wrapped;
}

  /**
   * opens map and opens relevant marker
   */
  public function focus_in_maya_cell($id){
    $wrapped = '';
    $wrapped .= "<td>";
    $wrapped .= "<a href='/map?focus=true&focus-type=".$this->singular."&focus-id=".$id."'>";
    $wrapped .= "🗺";
    $wrapped .= "</a>";
    $wrapped .= "</td>";
    return $wrapped;
  }


  /**
   * plenary view & root endpoint
   */
  public function index()
  {

    $models = \App\Address::allWithAddress("App\\" . $this->model_name)->sortBy('name');

    if ($this->uses_generic_index) {
      return $this->get_view("generic.index", [
        $this->plural => $models,
        'index_columns' => $this->index_columns,
        'index_rows' => $this->create_index_rows($models),
        'index_title'=> $this->singular,
        'plural_name'=> $this->plural,
        
      ]);
    }
    return $this->get_view($this->view_prefix . ".index", [
      $this->plural => $models,
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

    foreach ($this->required as $key) {

      $this->validator_config[$key] = '';

      // switch ($key) {
      //   case 'email_address':
      //     //$this->validator_config[$key] = "required|email:rfc";
      //     $this->validator_config[$key] = "required"; //TODO LELIJKE FIX OMDAT LIVE DATA raar is
      //     break;
      //   case 'phone_number':
      //     $this->validator_config[$key] = "required|min:10";
      //     break;
      //   case 'postal_code':
      //     $this->validator_config[$key] = "required|max:7|regex:/^([0-9]{4}[ ]{0,1}[a-zA-Z]{2})$/";
      //     break;
      //   default:
      //     $this->validator_config[$key] = 'required';
      // }
    }
    $this->validator = Validator::make(Input::all(), $this->validator_config);
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
    $add_res = \App\Address::save_or_create_address();

    

    $this->create_or_save($request, $add_res['address_id']);
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
    $add_res = \App\Address::save_or_create_address();
   
    
    $this->create_or_save($request, $add_res['address_id']);
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
