<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Club;
use App\Models\Result;
use App\Models\EloRanking;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use Illuminate\Support\Facades\Storage;

class BundesligaController extends Controller
{
    public function calculateEloRanking()
    {
        // initialize all clubs with start ranking points of 1500
        $clubs = Club::all();

        foreach($clubs as $club) {
            $exists = EloRanking::where('club_id', $club->verein_id)->get()->first();
            if(!$exists) {
                $eloRanking = new EloRanking();
                $eloRanking->club_id = $club->verein_id;
                $eloRanking->club = $club->vereinsname;
                $eloRanking->elo = 1500;
                $eloRanking->elo_history = '';
                $eloRanking->save();
            }
        }

        // get games in chronological order and calculate new elo points

        // get days since the beginning of bundesliga
        $lastGame = Result::orderBy('anstoss', 'desc')->take(1)->get()->first();
        $period = CarbonPeriod::create('1963-08-23', $lastGame->anstoss);
        $dates = new Collection();

        // Iterate over the period
        foreach ($period as $date) {
            $dates->push($date);
        }

        foreach ($dates as $date) {

            // get games for this day
            $spiele = Result::where('anstoss', '<', $date->addDays(1))->where('anstoss', '<', $date->subDays(1))->get();

            foreach ($spiele as $spiel) {
                $hv = Club::where('verein_id', $spiel->heimverein)->get()->first();
                $gv = Club::where('verein_id', $spiel->gastverein)->get()->first();
                $heimverein = EloRanking::where('club_id', $hv->verein_id)->get()->first();
                $gastverein = EloRanking::where('club_id', $gv->verein_id)->get()->first();

                if ($spiel->heimtore > $spiel->gasttore) {
                    $resultheimverein = 1;
                    $resultgastverein = 0;
                    $nodraw = true;
                } elseif ($spiel->heimtore < $spiel->gasttore) {
                    $resultheimverein = 0;
                    $resultgastverein = 1;
                    $nodraw = true;
                }

                if ($nodraw) {
                    // calculate elo
                    $result = $this->calculateElo($heimverein, $gastverein, $resultheimverein, $resultgastverein);
                    // persist new elo points
                    $heimverein->elo = $result[2];
                    $gastverein->elo = $result[3];
                    $heimverein->save();
                    $gastverein->save();
                }

                // loop throug all clubs and persist elo points for this day
                $clubs = EloRanking::all();
                foreach ($clubs as $club) {
                    $unserializedEloArray = array();

                    $eloArray = $club->elo_history;

                    // save new elo points
                    if ($eloArray != null) {
                        $unserializedEloArray = unserialize($eloArray);
                        $unserializedEloArray[$date->format('Y-m-d')] = $club->elo;
                        $club->elo_history = serialize($unserializedEloArray);
                        $club->save();
                    } else {
                        $unserializedEloArray[$date->format('Y-m-d')] = $club->elo;
                        $club->elo_history = serialize($unserializedEloArray);
                        $club->save();
                    }
                }

                $nodraw = false;
            }
        }
    }

    /**
     * Calculate elo
     * Berechnet die elo Werte
     *
     * @param object $verein1
     * @param object $verein2
     * @param integer $resultverein1
     * @param integer $resultverein2
     *
     * @return array array with elo changes
     */
    public function calculateElo($verein1, $verein2, $resultverein1, $resultverein2)
    {
        // Elowerte auslesen
        $eloverein1 = $verein1->elo;
        $eloverein2 = $verein2->elo;

        // Neue Elowerte berechnen

        $expectancyValueverein1 = 1 / (1 + pow(10, (($eloverein2 - $eloverein1) / 200)));
        $eloNewverein1 = $eloverein1 + 15 * ($resultverein1 - $expectancyValueverein1);

        $expectancyValueverein2 = 1 / (1 + pow(10, (($eloverein1 - $eloverein2) / 200)));
        $eloNewverein2 = $eloverein2 + 15 * ($resultverein2 - $expectancyValueverein2);

        $eloDiffverein1 = $eloNewverein1 - $eloverein1;
        $eloDiffverein2 = $eloNewverein2 - $eloverein2;

        $eloArray[] = $eloDiffverein1;
        $eloArray[] = $eloDiffverein2;
        $eloArray[] = $eloNewverein1;
        $eloArray[] = $eloNewverein2;

        // Elowerte zurückgeben

        return $eloArray;
    }

    public function generateCSV(){
        // get days since the beginning of bundesliga
        $start = config('bundesliga.csv_start_date');
        $end = config('bundesliga.csv_end_date');
        $period = CarbonPeriod::create($start, $end);
        $dates = new Collection();

        // Iterate over the period
        foreach ($period as $date) {
            $dates->push($date);
        }

        $clubs = EloRanking::all();

        Storage::put('/public/elo_bundesliga.csv', 'date,club,elo' . "\r\n");

        foreach ($dates as $date) {
            foreach($clubs as $club) {
                $unserializedArray = unserialize($club->elo_history);
                if(array_key_exists($date->format('Y-m-d'), $unserializedArray)){
                    $elo = $unserializedArray[$date->format('Y-m-d')];
                    $current = Storage::get('/public/elo_bundesliga.csv');
                    $current = $current . $date->format('Y-m-d') . ',' . $club->club . ',' . $elo . "\r\n";
                    Storage::put('/public/elo_bundesliga.csv', $current);
                }
            }
        }
    }
}
