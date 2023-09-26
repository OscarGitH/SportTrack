<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SportTrack | À Propos</title>
    <link rel="icon" type="image/png" href="img/Run_icon.png">
    <link rel="stylesheet" href="../views/apropos.css">
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

    <div class="about-me-container">
        <h2>À propos de nous :</h2>
        <p>NOMS : <?php echo $lastname; ?></p>
        <p>PRENOMS : <?php echo $firstname; ?></p>
    </div>
</body>
</html>
