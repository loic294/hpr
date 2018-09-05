<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Competence;
use App\CompetenceChoisie;
use App\Profile;

class AdminController extends Controller
{

  function index() {

    $competences = Competence::all();
    $profiles = Profile::all();

    $nbrEquipes = floor(count($profiles) / 6);

    // return $nbrEquipes;

    $modes = [
      "expression",
      "promotion",
      "explication",
      "structuration",
      "vigilance",
      "soutien"
    ];

    $notes = [];
    $excellence = [];

    $competences_index = [];
    foreach ($competences as $key => $competence) {
      $competences_index[] = 0;
    }

    $modes_index = [];
    foreach ($modes as $key => $m) {
      $modes_index[] = 0;
    }

    $equipes = [];
    for ($i=0; $i < $nbrEquipes; $i++) {
      $equipe[] = [];
      foreach ($modes as $key => $m) {
        $equipes[$i][$m] = 0;
      }
    }

    function findCompetence($comps, $id) {
      foreach ($comps as $key => $comp) {
        if($comp->competence_id == $id) return true;
      }
      return false;
    }

    foreach ($profiles as $key1 => $p) {
      foreach ($competences as $key3 => $competence) {
        $m = [];
        foreach ($modes as $key2 => $mode) {
          $m[] = $p[$modes[$key2]];
        }

        $hasCompetence = findCompetence($p->competences, $competence->id);
        $notes[] = [
          "profile" => $p->id,
          "modes" => $m,
          "competence" => $competence->id,
          "hasCompetence" => $hasCompetence,
          "selected" => false
        ];

        if($hasCompetence) {
          $competences_index[$key3] = $competences_index[$key3] + 1;
        }

      }
    }

    global $last_small;
    global $comp_petit;
    global $comp_petit_id;
    global $comp_petit_index;
    $comp_petit = $competences_index[0];
    $comp_petit_id = $competences[0]->id;
    $comp_petit_index = 0;
    $last_small = 0;


    function findPetiteCompetence($competences_index, $competences) {
      global $last_small;
      global $comp_petit;
      global $comp_petit_id;
      global $comp_petit_index;
      global $competences_index;

      $comp_petit = $competences_index[$competences_index];
      $comp_petit_id = $competences[$comp_petit_index]->id;
      $comp_petit_index++;
    }

    function findNotes($compID, $notes) {
      $values = [];
      foreach ($notes as $key => $note) {
        if($note['competence'] == $compID && $note['hasCompetence'] && !$note['selected']) {
          $values[] = $key;
        }

      }
      return $values;
    }

    function getMaxMode($modes) {
      $modesCopy = $modes;
      rsort($modesCopy);
      $indexes = [];
      foreach ($modesCopy as $key => $mode) {
        $indexes[] = array_search($mode, $modes);
      }
      return $indexes;
    }

    $selectedProfiles = [];

    for ($k=0; $k < 6; $k++) {

      findPetiteCompetence($competences_index, $competences);


      for ($l=0; $l < 6; $l++) {
        $dansEquipe = [];
        // return findNotes($comp_petit_id, $notes)[$l];
        if(isset(findNotes($comp_petit_id, $notes)[$l])) {
          $t = findNotes($comp_petit_id, $notes)[$l];
          $n = $notes[$t];
          $indexes = getMaxMode($n['modes']);
          $index = null;

          if($modes_index[$indexes[0]] < count($equipes)) $index = 0;
          else if($modes_index[$indexes[1]] < count($equipes)) $index = 1;
          else if($modes_index[$indexes[2]] < count($equipes)) $index = 2;
          else if($modes_index[$indexes[3]] < count($equipes)) $index = 3;
          else if($modes_index[$indexes[4]] < count($equipes)) $index = 4;
          else if($modes_index[$indexes[5]] < count($equipes)) $index = 5;

          if($index !== null) {
            for ($i=0; $i < count($equipes); $i++) {
              if($equipes[$i][$modes[$indexes[$index]]] == 0 && !in_array($i, $dansEquipe) && !in_array($notes[$t]["profile"], $selectedProfiles)) {
                $modes_index[$indexes[$index]] += 1;
                $notes[$t]["selected"] = true;
                $notes[$t]["mode"] = $modes[$indexes[$index]];
                $dansEquipe[] = $i;
                $selectedProfiles[] = $notes[$t]["profile"];
                $equipes[$i][$modes[$indexes[$index]]] = $notes[$t];
                break;
              }
            }
          }
        }

      }

      $last_small = $comp_petit;

    }

    function findNotesFinal($notes, $selectedProfiles, $eq, $i2) {
      foreach ($notes as $key => $note) {
        if(
          $note['hasCompetence'] &&
          !$note['selected'] &&
          !in_array($note["profile"], $selectedProfiles) &&
          !in_array($note["profile"], $eq) &&
          $note["modes"][$i2] > 10
        )
          return  $key;
      }
    }

    foreach ($equipes as $key1 => $equipe) {
      $eq = [];
      $cp = [];
      foreach ($modes as $key2 => $mode) {
        $eq[] = $notes[$index]["profile"];

        if($equipe[$mode] == 0) {
          $index = findNotesFinal($notes, $selectedProfiles, $eq, $key2);
          $modes_index[$key2] += 1;
          $selectedProfiles[] = $notes[$index]["profile"];
          $notes[$index]["selected"] = true;
          $equipes[$key1][$mode] = $notes[$index];
          $notes[$index]["mode"] = $mode;
        }

        $cp[] = $equipes[$key1][$mode]['competence'];
        $profile = Profile::find($equipes[$key1][$mode]['profile']);
        $compF = $equipes[$key1][$mode]['competence'];

        $count = array_count_values($cp);

        if(in_array($compF, $cp) && $count[$compF] > 1) {
          foreach ($profile->competences as $key => $comp) {
            if($comp->competence_id !== $compF && !in_array($comp->competence_id, $cp))
              $compF = $comp->competence_id;
              // return $comp;
          }
        }

//         return $profile->competences;
        
        $equipes[$key1][$mode]['details'] = Profile::find($equipes[$key1][$mode]['profile']);
        $equipes[$key1][$mode]['competences'] = $profile->competences;

      }
    }




    // return [
    //   "competence" => $comp_petit,
    //   "last_small" => $last_small,
    //   "test" => findNotes($comp_petit, $notes),
    //   "equipes" => $equipes,
    //   "competences_index" => $competences_index,
    //   "modes_index" => $modes_index,
    //   "notes" => $notes
    // ];


    $data = [
      "equipes" => $equipes,
      "modes" => $modes,
      "competences" => $competences,
      "notes" => $notes,
    ];

    // return $data;

    return view('admin', $data);
  }

  function store(Request $request) {

    //return redirect()->back()->with("success", "Les équipes ont été mises à jour.");

  }

}
