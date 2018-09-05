<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>HPR - Projet</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600|Bitter:400,700" rel="stylesheet" type="text/css">
        <link href="css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body>

      <div class="half-background"></div>

      <div class="content small">
        <div class="content-box">

          @if(Session::has("success"))
            <div class="success">{{Session::get("success")}}</div>
          @endif

          <form action="{{ URL::to('/') }}/" method="post">
            {{ csrf_field() }}

            <h3>Informations de base</h3>
            <div class="inline">
              <div class="input">
                <input type="text" name="prenom" required />
                <label>Prénom</label>
              </div>
              <div class="input">
                <input type="text" name="nom" required />
                <label>Nom</label>
              </div>
              <div class="input">
                <input type="number" name="matricule" required />
                <label>Matricule</label>
              </div>
            </div>

            <h3>Modes d'interaction</h3>
            <div class="inline">
              <div class="input">
                <input type="number" min="0" max="100" name="expression" required />
                <label>Expression</label>
              </div>
              <div class="input">
                <input type="number" min="0" max="100" name="promotion" required />
                <label>Promotion</label>
              </div>
              <div class="input">
                <input type="number" min="0" max="100" name="explication" required />
                <label>Explication</label>
              </div>
            </div>
            <div class="inline">
              <div class="input">
                <input type="number" min="0" max="100" name="structuration" required />
                <label>Structuration</label>
              </div>
              <div class="input">
                <input type="number" min="0" max="100" name="vigilance" required />
                <label>Vigilance</label>
              </div>
              <div class="input">
                <input type="number" min="0" max="100" name="soutien" required />
                <label>Soutien</label>
              </div>
            </div>

            <h3>Competences</h3>
            @foreach($competences as $key => $comp)
              <input type="checkbox" name="competences[]" value="{{$comp->id}}" id="{{camel_case($comp->nom)}}" />
              <label for="{{camel_case($comp->nom)}}"><span></span>{{$comp->nom}}</label>
            @endforeach

            <div class="center">
              <button type="submit">Ajouter mon profil</button>
            </div>

          </form>

        </div>
      </div>

    </body>
</html>
