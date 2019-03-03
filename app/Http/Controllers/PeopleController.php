<?php

namespace App\Http\Controllers;

use App\Person;

use Illuminate\Http\Request;
use Validator;

class PeopleController extends Controller
{
    public function store(Request $request)
    {
        // Validation is not required by Coding Challenge directions,
        // but always a good idea to validate (for security).
        Validator::make($request->all(), [
            'people' => 'required|json'
        ])->validate();

        // Parse POST data as per Coding Challenge directions
        $json = json_decode( $request->input('people') );
        $people = collect($json->data);

        $this->sortAndAppendData($people);

        $email_addresses = json_encode( $people->pluck('email')->toArray() );
        $original_data   = json_encode( array_values( $people->toArray() ) );

        // Persist data to database as per Coding Challenge directions
        $person = new Person(compact('email_addresses', 'original_data'));
        $person->ip_address = $request->ip(); // Prop not fillable (for security)
        $person->save();

        // Respond
        return response()->json([
            'message' => 'People saved!',
            'data'    => $person
        ]);
    }

    /**
     * Sort and append the given people data as per CauseLab's coding
     * challenge requirement.
     *
     * @return void
     */
    private function sortAndAppendData(&$people)
    {
        // sort
        $people = $people->sortByDesc('age');

        // append
        $people = $people->each(function($person) {
            $person->name = $person->first_name . ' ' . $person->last_name;
        });
    }
}
