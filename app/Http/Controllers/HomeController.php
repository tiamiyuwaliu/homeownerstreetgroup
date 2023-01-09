<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Requests\CsvReader;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('welcome', ['people' => People::paginate(5)]);
    }

    public function store(Request $request, CsvReader $csvReader) {
        $file = $request->file('file');
        if (!$csvReader->isValid($file)) return redirect(route('home'))->with('error', __('Only csv file is accepted'));

        $people = $csvReader->read($file)->getPeople();
        if ($request->input('dst') == 1) {
            return view('output', ['people' => $people]);
        } else{
            foreach($people as $data) {
                People::create([
                    'title' => $data['title'],
                    'first_name' => $data['first_name'],
                    'initial' => $data['initial'],
                    'last_name' => $data['last_name'],
                ]);
            }

            return redirect(route('home'))->with('success', __('New home owners inserted successfully'));
        }
    }

    public function delete(Request $request, $id) {
        People::find($id)->delete();
        return redirect(route('home'))->with('success', __('Home owner deleted successfully'));

    }
}
