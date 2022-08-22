<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h3>Salut cher·ère bénévole !</h3>
    <p>Nous avons bien reçu ton inscription pour venir travailler à la Course de Garçons de Café le 17 septembre prochain. Un grand merci pour ton soutien ! 🙏🏼 </p>
    <p>Tu trouveras le récapitulatif des tranches horaires choisies ci-dessous. Pour toute modification, merci de nous contacter directement à <a href="mailto:benevoles@makeitmove.ch">benevoles@makeitmove.ch</a>.</p>
    <br>
    @foreach ($mailDatas[0]->workblocks as $workblock)
        <p>De {{ $workblock->readable_start }} à {{ $workblock->readable_stop }} <strong>{{ $workblock->workplaces->name }}</strong> </p>
    @endforeach
    {{-- <p>{{ $mailDatas}}</p> --}}
    <br>
    <p>🕑 Nous te donnons rendez-vous <u>15 minutes avant le début de ton shift au départ de la course</u>, qui se trouve dans la Grand-Rue, devant le shop Knight Rider</p>

    <p>Encore un grand merci pour ton engagement et à tout bientôt !</p>

    <p>Deborah Simonetti<br>Responsable Bénévoles<br>079 174 33 27<br><a href="mailto:benevoles@makeitmove.ch" target="_blank">benevoles@makeitmove.ch</a></p>
</body>
</html>
