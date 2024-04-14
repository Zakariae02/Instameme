<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créer un post - Instameme</title>
  <link rel="stylesheet" href="../css/ajout.css" />
</head>

<body>
  <div class="header">
    <div class="logo">
      <a href="./indexphp">
        <img src="../assets/Instameme - Logo.png" alt="Logo" /></a>
    </div>
    <a href="./index.php" class="link">Accueil</a>
    <div class="nav">
      <input type="text" placeholder="Recherche" />
      <button>🔍</button>
    </div>
    <div class="profile">
      <a href="./ajout.php">Créer</a>
      <a href="./utilisateur.php">Profil</a>
    </div>
  </div>
  <div class="container">
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="file-upload">Champ de Fichier</label>
        <input type="file" id="file-upload" name="file" />
      </div>

      <div class="form-group">
        <label for="description">Champ de Description</label>
        <textarea id="description" rows="4" name="description"></textarea>
      </div>

      <button type="submit" name="submit">Publier</button>
    </form>
  </div>
</body>

</html>