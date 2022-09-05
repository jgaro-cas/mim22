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
    <p>Tu trouveras le récapitulatif des tranches horaires choisies ci-dessous. Pour toute modification, merci de nous contacter directement à <a href="mailto:benevole@makeitmove.ch">benevole@makeitmove.ch</a>.</p>
    <br>
    @foreach ($mailDatas[0]->workblocks as $workblock)
        <p>De {{ $workblock->readable_start }} à {{ $workblock->readable_stop }} <strong>{{ $workblock->workplaces->name }}</strong> </p>
    @endforeach
    {{-- <p>{{ $mailDatas}}</p> --}}
    <br>
    <p>🕑 Nous te donnons rendez-vous <u>15 minutes avant le début de ton shift au départ de la course</u>, qui se trouve dans la Grand-Rue, devant le shop Knight Rider</p>
    <p>Pour te remercier de ton précieux coup de main, tu recevras un bon pour manger 🍔, et un bon boisson 🍺 (les boissons sont offertes pendant que tu travailles) ! </p>
    <p>Encore un grand merci pour ton engagement et à tout bientôt !</p>

    <p>Sébastien Hofmann<br>Responsable Bénévoles<br><a href="mailto:benevole@makeitmove.ch" target="_blank">benevole@makeitmove.ch</a></p>
</body>
</html>
