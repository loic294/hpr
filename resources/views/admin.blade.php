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

          <h1>Admin</h1>

          <div class="center">
            <button onclick="window.location.reload()">Regénérer les équipes</button>
          </div>

          @foreach($equipes as $key1 => $equipe)
            <h3>Équipe {{$key1+1}}</h3>

            <ul class="table">
            @foreach($modes as $key2 => $mode)
              <?php $t = $equipe[$mode]; ?>

              <li class="row">
                <div>#{{$t['details']["id"]}} - {{$t['details']["prenom"]}} {{$t['details']["nom"]}} ({{$t['details']["matricule"]}})</div>
                <div><span class="cap">{{$mode}}</span> ({{$t['modes'][$key2]}}%) - 
                  @foreach($t['competences'] as $comp)
                  {{$comp->competence}} 
                 @endforeach
                </div>
              </li>
            @endforeach
            </ul>

          @endforeach


        </div>
      </div>

    </body>
</html>
