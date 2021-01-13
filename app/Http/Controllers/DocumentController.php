<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Document;
use App\Animal;

class DocumentController extends Controller
{

    function __construct()
    {
        parent::__construct('documents', 'animals');
    }

    public function index($link_id)
    {
        $animal = Animal::find($link_id);
        //$documents = Document::where('link_id', $link_id)->orderBy('date', 'desc')->get();
        $documents = Document::where('link_id', $link_id)->get();

        foreach ($documents as $document) {
            $document->date = $this->FormatDate($document->date);
            $document->doctypeName = $this->getDescription($document->doctype_id);
        }

        $documents = $documents->sortBy('doctypeName');

        $menuItems = $this->GetMenuItems('animals');

        $data = array(
            'documents' => $documents,
            'menuItems' => $menuItems,
            'animal' => $animal
        );

        return view("document.index")->with($data);
    }

    public function show($link_id, $document_id)
    {
        $document = Document::find($document_id);
        $document->date = $this->FormatDate($document->date);
        $document->documentLink = 'documents/' . 'document_' . $document_id . '.pdf';
        $animal = Animal::find($document->link_id);
        $doctypeName = $this->getDescription($document->doctype_id);

        $menuItems = $this->GetMenuItems('animals');

        $data = array(
            'document' => $document,
            'menuItems' => $menuItems,
            'animal' => $animal,
            'doctypeName' => $doctypeName
        );

        return view("document.show")->with($data);
    }

    public function edit($link_id, $document_id)
    {
        $document = Document::find($document_id);
        $data = $this->GetDocumentData($document);

        return view("document.edit")->with($data);
    }

    public function create($link_id)
    {
        $document = new Document;
        $document->link_id = $link_id;
        $document->date = date('Y-m-d');
        $data = $this->GetDocumentData($document);

        return view("document.edit")->with($data);
    }

    public function store(Request $request, $link_id)
    {
        $validator = $this->validateDocument(true);

        if ($validator->fails()) {
            return redirect()->action('DocumentController@create', $link_id)
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveDocument($request, $link_id, null);
            Session::flash('message', 'Document succesvol toegevoegd!');
            return redirect()->action('DocumentController@index', $link_id);
        }
    }

    public function update(Request $request, $link_id, $document_id)
    {
        $validator = $this->validateDocument(false);

        if ($validator->fails()) {
            return redirect()->action('DocumentController@edit', [$link_id, $document_id])
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->saveDocument($request, $link_id, $document_id);
            Session::flash('message', 'Document succesvol gewijzigd!');
            return redirect()->action('DocumentController@show', [$link_id, $document_id]);
        }
    }

    private function GetDocumentData($document)
    {
        $menuItems = $this->GetMenuItems('animals');
        $animal = Animal::find($document->link_id);
        $doctypes = $this->GetTableList($this->doctypeId);

        $doctypes->prepend('Selecteer documentsoort', '0');

        $data = array(
            'document' => $document,
            'menuItems' => $menuItems,
            'animal' => $animal,
            'doctypes' => $doctypes
        );

        return $data;
    }

    private function validateDocument($documentRequired)
    {
        if ($documentRequired) {
            $rules = array(
                'doctype_id' => 'required|numeric|min:1',
                'date'       => 'required',
                'document'   => 'required|mimes:pdf|max:1024',
            );
        } else {
            $rules = array(
                'doctype_id' => 'required|numeric|min:1',
                'date'       => 'required',
            );
        }

        return Validator::make(Input::all(), $rules);
    }

    private function saveDocument(Request $request, $link_id, $document_id)
    {
        if ($document_id !== null) {
            $document = Document::find($document_id);
        } else {
            $document = new Document;
        }

        $document->link_id = $link_id;
        $document->link_type = 'animal';
        $document->date = $request->date;
        $document->doctype_id = $request->doctype_id;
        $document->text = $request->text;

        // extra save to get id
        if ($request->id === null) {
            $document->save();
        }

        if ($request->hasFile('document')) {
            $documentName = 'document_' . $document->id . '.' . $request->file('document')->getClientOriginalExtension();
            $request->file('document')->move(base_path() . '/public/documents/', $documentName);
        }

        $document->save();
    }
}
