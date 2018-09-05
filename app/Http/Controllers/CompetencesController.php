<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Competence;
use App\CompetenceChoisie;
use App\Profile;

class CompetencesController extends Controller
{

  function index() {

    $data = [
      "competences" => Competence::all()
    ];

    return view('index', $data);
  }

  function store(Request $request) {

    // return $request->all();
    $i = $request->all();

    $profile = new Profile;
    $profile->prenom = $i['prenom'];
    $profile->nom = $i['nom'];
    $profile->matricule = $i['matricule'];
    $profile->expression = $i['expression'];
    $profile->promotion = $i['promotion'];
    $profile->explication = $i['explication'];
    $profile->structuration = $i['structuration'];
    $profile->vigilance = $i['vigilance'];
    $profile->soutien = $i['soutien'];
    $profile->save();



    foreach ($i['competences'] as $key => $competence) {
      $comp1 = Competence::find($competence);
      $comp = new CompetenceChoisie;
      $comp->competence_id = $competence;
      $comp->competence = $comp1->nom;
      $comp->profile_id = $profile->id;
      $comp->save();
    }

    // return $profile;

    return redirect()->back()->with("success", "Génial! Le profil a été ajouté.");

  }

}
