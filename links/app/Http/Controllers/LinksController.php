<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Links;

class LinksController extends Controller
{

    /**
     * Вывод всех ссылок
     * 
     * @return links
     */
    public function show()
    {
        return view('shortLinks', [
            'links' => Links::latest()->get()
        ]);
    }

    /**
     * Генерация сокращенной ссылки
     * 
     * @param Request $request
     * 
     * @return response()->json($link)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $link = Links::create([
            'current_link' => $request->link,
            'short_link' => Str::random(5)
        ]);

        return response()->json($link);
    }

    /**
     * Редирект сокращенной ссылки на исходную
     * 
     * @param $short_link
     * 
     * @return redirect($link->current_link)
     */
    public function shortLink($short_link)
    {
        $link = Links::where('short_link', $short_link)->first();
        $link->save();

        return redirect($link->current_link);
    }
}
