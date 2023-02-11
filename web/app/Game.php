<?php

namespace App;

Use DB;
use Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Game extends Model
{
    use SoftDeletes;

    protected $table = 'game';

    // below is no need because default
    // protected $primaryKey = 'id';
    // public $incrementing = true;
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    public function getAllGames() {
        return self::all();
    }

    public function getAllGamesPagination() {
        return self::paginate(\Config::get('constants.general.per_page'));
    }

    public function getNewGames($n) {
        if (isMobile()) {
            return self::where('g_not_mobi', 0)
                ->orderBy('id', 'desc')->take($n)->get();
        }
        return self::orderBy('id', 'desc')->take($n)->get();
    }
    public function getHotGames($n) {
        if (isMobile()) {
            return self::whereIn('g_hot', array(1,2))
                    ->where('g_not_mobi', 0)
                    ->orderBy('g_vote', 'DESC')
                    ->orderBy('g_vote_time', 'DESC')
                    ->take($n)->get();
        }
        return self::whereIn('g_hot', array(1,2))
                    ->orderBy('g_vote', 'DESC')
                    ->orderBy('g_vote_time', 'DESC')
                    ->take($n)->get();
    }

    public function getGamesByCatID($cat, $n=60) {
        if (isMobile()) {
            return self::where(function($query) use ($cat)
                    {
                        $query->where('g_cat_1', '=', $cat);
                        $query->orWhere('g_cat_2', '=', $cat);
                        $query->orWhere('g_cat_3', '=', $cat);
                        $query->orWhere('g_cat_4', '=', $cat);
                    })
                    ->where('g_not_mobi', 0)
                    ->orderBy('g_vote', 'DESC')
                    ->orderBy('g_vote_time', 'DESC')
                    ->take($n)->get();
        }
        return self::where('g_cat_1', $cat)
                    ->orWhere('g_cat_2', $cat)
                    ->orWhere('g_cat_3', $cat)
                    ->orWhere('g_cat_4', $cat)
                    ->orderBy('g_vote', 'DESC')
                    ->orderBy('g_vote_time', 'DESC')
                    ->take($n)->get();
    }
    public function getGamesByCatIDs($arr_cat, $n=60) {
        foreach ($arr_cat as $key => $value) {
            $f = explode(",", $value);
        }
        if (isMobile()) {
            return self::where(function($query) use ($f)
                    {
                        $query->whereIn('g_cat_1', $f);
                        $query->orWhereIn('g_cat_2', $f);
                        $query->orWhereIn('g_cat_3', $f);
                        $query->orWhereIn('g_cat_4', $f);
                    })
                    ->where('g_not_mobi', 0)
                    ->orderBy('g_vote', 'DESC')
                    ->orderBy('g_vote_time', 'DESC')
                    ->take($n)->get();
        }
        return self::whereIn('g_cat_1', $f)
                    ->orWhereIn('g_cat_2', $f)
                    ->orWhereIn('g_cat_3', $f)
                    ->orWhereIn('g_cat_4', $f)
                    ->orderBy('g_vote', 'DESC')
                    ->orderBy('g_vote_time', 'DESC')
                    ->take($n)->get();
    }
    public function getGameBySlug($slug) {
        $lang = app()->getLocale();
        if ($lang != 'en') {
            // Note that: slug still by english, so map with game table !!!
            return self::join('game_lang', 'game.id', '=', 'game_lang.g_id')
                ->where('game_lang.lang', '=', $lang)
                ->where('game.g_title_slug', $slug)->first();
        }
        return self::where('g_title_slug', $slug)->first();
    }

    public function getGamesByTags($tag_slug) {
        $lang = app()->getLocale();
        if ($lang != 'en') {
            return self::select('game.*')
                ->join('game_lang', 'game.id', '=', 'game_lang.g_id')
                ->join('game_cat_lang', 'game.g_cat_1', '=', 'game_cat_lang.g_cat_id')
                ->where('game_cat_lang.lang', '=', $lang)
                ->where('g_cat_tags_slug', 'like', '%'.$tag_slug.'%')->get();
        }

        return self::select('game.*')
            ->join('game_cat', 'game.g_cat_1', '=', 'game_cat.id')
            ->where('g_cat_tags_slug', 'like', '%'.$tag_slug.'%')->get();
    }

    public function getGamesBySearch($kw) {
        return self::select('game.*')
                ->join('game_cat', 'game.g_cat_1', '=', 'game_cat.id')
                ->join('game_cat_lang', 'game.g_cat_1', '=', 'game_cat_lang.g_cat_id')
                ->where('game_cat.g_cat_name', 'like', '%'.$kw.'%')
                ->orWhere('game_cat_lang.g_cat_name', 'like', '%'.$kw.'%')
                ->orWhere('g_title', 'like', '%'.$kw.'%')->get();
    }

    public function increasePlayTime($slug) {
        $_slug = htmlspecialchars($slug, ENT_QUOTES);
        return DB::select(DB::raw("UPDATE game SET g_play_time = g_play_time+1 WHERE g_title_slug = '".$_slug."'"));
    }
    public function del($id) {
        return self::where('id', $id)->delete();
    }
    public function getGameByID($id) {
        return self::where('id', $id)->first();
    }
    public function updateGameInfo($vote, $vote_time, $play_time, $hot, $cat1, $cat2, $desc, $guide, $not_mobi, $orentation, $del, $slug) {
        $g = self::getGameBySlug($slug);
        $vote = $vote ?? $g->g_vote;
        $vote_time = $vote_time ?? $g->g_vote_time; 
        $play_time = $play_time ?? $g->g_play_time;
        $hot = $hot ?? $g->g_hot;
        $cat1 = $cat1 ?? $g->g_cat_1;
        $cat2 = $cat2 ?? $g->g_cat_2;
        $desc = $desc ?? $g->g_desc;
        $not_mobi = $not_mobi ?? $g->g_not_mobi;
        $orentation = $orentation ?? $g->g_dimension;
        $guide = $guide ?? $g->g_guide;
        $del = $del == 1 ? date("Y-m-d H:i:s") : null;

        self::where('g_title_slug', $slug)
            ->update([
                'g_vote' => $vote,
                'g_vote_time' => $vote_time,
                'g_play_time' => $play_time,
                'g_hot' => $hot,
                'g_cat_1' => $cat1,
                'g_cat_2' => $cat2,
                'g_desc' => $desc,
                'g_not_mobi' => $not_mobi,
                'g_dimension' => $orentation,
                'g_guide' => $guide,
                'deleted_at' => $del
            ]);
    }

    
    public function updateGameVote($vote_type, $id) {
        $g = self::getGameByID($id);
        $vote = self::getVote($g->g_vote, $g->g_vote_time, $vote_type);
        Log::info($vote);
        $vote_time = $g->g_vote_time + 1; 
        
        self::where('id', $id)
            ->update([
                'g_vote' => $vote,
                'g_vote_time' => $vote_time
            ]);
    }
    /**
     * About vote calculator:
     * Vote new = (Vote.time+5)/(time+1)
     */
    public function getVote($oldVote, $oldTime, $vote_type) {
        if ($vote_type == 1)
            return ($oldVote*$oldTime+5)/($oldTime+1);
        else
            return ($oldVote*$oldTime+1)/($oldTime+1);
    }

}
