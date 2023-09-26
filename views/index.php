<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>SportTrack | Accueil</title>
    <link rel="icon" type="image/png" href="img/Run_icon.png">
    <link rel="stylesheet" href="../views/index.css" media="screen" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="menu">
            <a href="/"><img src="img/sporttrack.png" alt="SportTrack Logo"></a>
            <?php if (isset($_SESSION['userId'])): ?>
                <a href="disconnect">SE DÉCONNECTER</a>
                <a class="myaccount" href="my_account">MON COMPTE</a>
            <?php else: ?>
                <a href="connect">SE CONNECTER</a>
                <a href="user_add">S'INSCRIRE</a>
            <?php endif; ?>
        </div>
    </header>
    
    <main>
        <div class="conteneur">
            <?php if (!isset($_SESSION['userId'])): ?>
                <p class="explication">SportTrack permet à des sportifs disposant d’une montre "cardio/gps" de pouvoir sauvegarder et gérer des données de position et de fréquence cardiaque</p>
                <a class="signuptext" href="user_add">Créer un compte</a>
            <?php else: ?>
                <p class="explication">Bienvenue, <?php echo $_SESSION['firstName']; ?> !</p>
                <p class="explication">Accédez à votre compte pour consulter vos données ou ajouter un nouveau fichier</p>
                <a class="myaccount" href="my_account">MON COMPTE</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <section>
            <a class="apropos" href="apropos">À propos</a>
        </section>
    </footer>
</body>
</html>