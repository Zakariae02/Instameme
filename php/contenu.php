<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instameme - Contenu</title>
  <link rel="stylesheet" href="../css/contenu.css" />
</head>

<body>
  <div class="container">
    <div class="header">
      <div class="logo">
        <a href="./index.php">
          <img src="../assets/Instameme - Logo.png" alt="Logo" /></a>
      </div>
      <div class="search-bar">
        <input type="text" placeholder="Recherche" />
        <button>🔍</button>
      </div>
      <div>
        <a href="./ajout.php">Créer</a>
        <a href="./utilisateur.php">Profil</a>
      </div>
    </div>

    <div class="content">
      <?php
      // Database credentials
      $servername = "localhost"; // Change this to your MySQL server hostname
      $username = "root"; // Change this to your MySQL username
      $password = "password"; // Change this to your MySQL password
      $database = "instameme"; // Change this to your MySQL database name

      // Create connection
      $conn = new mysqli($servername, $username, $password, $database);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Assume content_id is passed via GET parameter
      $content_id = $_GET['content_id'];

      // Query to fetch post details including user information
      $sql = "SELECT u.pseudo, c.chemin_image, COUNT(l.id_contenu) AS likes, c.description, c.date_publication 
                    FROM contenus c
                    JOIN utilisateurs u ON c.id_utilisateur = u.id
                    LEFT JOIN likes l ON c.id = l.id_contenu
                    WHERE c.id = $content_id"; // Assuming you pass the content ID through the URL
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<div class="post-image">';
        echo '<img class="imagee" src="../assets/images/' . $row["chemin_image"] . '" alt="" width="70%" />';
        echo '</div>';
        echo '<div class="sidebar">';
        echo '<h3>' . $row["pseudo"] . '</h3>';
        echo '<p>' . $row["description"] . '</p>';
        echo '<div class="actions">';
        echo '<button>Aimer (' . $row["likes"] . ')</button>';
        // Link to the "Partage" page with content ID in the URL
        echo '<a href="./partage.php?content_id=' . $content_id . '"><button>Partager</button></a>';
        echo '</div>';
        echo '<p>Aimé par ...</p>'; // You can fetch and display users who liked this post here
        echo '<p>Date de publication: ' . $row["date_publication"] . '</p>';
        echo '<p>Commentaires</p>'; // You can fetch and display comments for this post here
        echo '<textarea name="" id="" cols="30" rows="10"></textarea>';
        echo '</div>';
      } else {
        echo "No post found";
      }

      // Close the database connection
      $conn->close();
      ?>
    </div>
  </div>
</body>

</html>