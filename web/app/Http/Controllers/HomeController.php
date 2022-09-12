<?php

namespace App\Http\Controllers;

use App\Game;
use App\GameCats;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request) {

        $g = new Game();
        $gc = new GameCats();
        $g_new = $g->getNewGames(24);
        $g_hot = $g->getHotGames(48); // 8 Special

        $gc_all = $gc->getArrayCatRichInfo();
        $gc_by_id = $gc_all[0];
        $arr_tags = $gc_all[1];

        $gbc_1 = $g->getGamesByCatID(11, 18); // Driving
        $gbc_2 = $g->getGamesByCatID(14, 18); // Action

        // print_r($gc_by_id);
        return view('home.home', [
            'g_new' => $g_new,
            'g_hot' => $g_hot,
            'gbc_1' => $gbc_1,
            'gbc_2' => $gbc_2,
            'gc_by_id' => $gc_by_id,
            'arr_tags' => array_unique($arr_tags)
        ]);
    }

    public function changeLanguage($language)
    {
        \Session::put('website_language', $language);

        return redirect()->back();
    }
}
