<html>
    <head>
        <meta charset="utf-8">
        <title>SportTrack | Mon compte</title>
        <link rel="icon" type="image/png" href="img/Run_icon.png">
        <link rel="stylesheet" href="../views/list_activities.css" media="screen" type="text/css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    </head>
    <body>
    <div class=menu>
        <a href="/"><img src="img/sporttrack.png"></img></a>
        <a href="upload">AJOUTER UN FICHIER</a>
        <a class="myaccount" href="my_account">MON COMPTE</a>
    </div>
    <div class="activities">
        <h1>Mes activités</h1>

        <table class="table">
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Temps</th>
                <th>Distance</th>
                <th>Vitesse moyenne</th>
                <th>Vitesse max</th>
                <th>Dénivelé</th>
                <th>Fréquence cardiaque moyenne</th>
                <th>Fréquence cardiaque max</th>
                <th>Fréquence cardiaque min</th>
            </tr>

            <?php foreach ($activities as $activity) { ?>
                <tr>
                    <td><?php echo $activity->getDate(); ?></td>
                    <td><?php echo $activity->getDescription(); ?></td>
                    <td><?php echo $activity->getTime(); ?></td>
                    <td><?php echo $activity->getDistance(); ?></td>
                    <td><?php echo $activity->getAverageSpeed(); ?></td>
                    <td><?php echo $activity->getMaxSpeed(); ?></td>
                    <td><?php echo $activity->getTotalAltitude(); ?></td>
                    <td><?php echo $activity->getAverageHeartRate(); ?></td>
                    <td><?php echo $activity->getMaxHeartRate(); ?></td>
                    <td><?php echo $activity->getMinHeartRate(); ?></td>
                </tr>
            <?php } ?>
        </table>

        <a href="/">Retourner à l'accueil</a>
    </div>
</body>
</html>