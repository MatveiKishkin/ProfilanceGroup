<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Links;

class LinksController extends Controller
{
    public function show()
    {
        return view('shortLinks', [
            'links' => Links::latest()->get()
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $link = Links::create([
            'current_link' => $request->link,
            'short_link' => Str::random(5)
        ]);
        // $link->save();

        // return redirect()->route('generate.link')->with('success', 'Создана сокращенная ссылка');
        return response()->json($link);
    }

    public function shortLink($short_link)
    {
        $link = Links::where('short_link', $short_link)->first();
        $link->save();

        return redirect($link->current_link);
    }
}
