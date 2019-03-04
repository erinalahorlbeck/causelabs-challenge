<?php

namespace App\Http\Controllers;

use App\Person;

use Illuminate\Http\Request;
use Validator;

class PeopleController extends Controller
{
    /**
     * List the index of People in the database.
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        // Normally I would paginate, and not expose all properties
        // carelessly, by using an Api Resource class. This is just a
        // simple example.
        $people = Person::all();

        return response()->json($people);
    }

    /**
     * Store a new Person in the database.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        //
        // I realize validation is not required by Coding Challenge
        // directions, but it's a good habit for security reasons.
        // (And normally I would create a Form class to validate
        // rather than stuffing that code into the controller.)
        Validator::make($request->all(), [
            'people' => 'required|json'
        ])->validate();

        // Parse
        $peopleData = $this->parsePeopleData($request);

        // Persist
        $person = new Person($peopleData);
        $person->ip_address = $request->ip(); // IP prop not fillable (for security)
        $person->save();

        // Respond
        return response()->json([
            'message' => 'People saved!',
            'data'    => $person
        ]);
    }

    /**
     * Parse the data of a validated POST request in accordance with
     * specifications of the CauseLabs Coding Challenge.
     * 
     * @param \Illuminate\Http\Request
     * @return Array
     */
    private function parsePeopleData(Request $request)
    {
        $json = json_decode( $request->input('people') );
        $people = collect($json->data);

        // Sort
        $people = $people->sortByDesc('age');

        // Append
        $people = $people->each(function($person) {
            $person->name = $person->first_name . ' ' . $person->last_name;
        });

        // Final vars
        $email_addresses = '"' . implode(',', $people->pluck('email')->toArray() ) . '"';
        $original_data   = json_encode( array_values( $people->toArray() ) );

        return compact('email_addresses', 'original_data');
    }
}
