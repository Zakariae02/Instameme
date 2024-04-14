<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Instameme - Accueil</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .header {
      background-color: #f2f2f2;
      padding: 20px;
      text-align: center;
    }

    .logo img {
      width: 100px;
      height: auto;
    }

    .link {
      margin: 0 10px;
      text-decoration: none;
      color: #333;
    }

    .nav input[type="text"] {
      padding: 8px;
      border: none;
      border-radius: 5px;
    }

    .nav button {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      background-color: #333;
      color: #fff;
      cursor: pointer;
    }

    .profile a {
      margin: 0 10px;
      text-decoration: none;
      color: #333;
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .post-container {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .user-name {
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }

    .commentaire {
      margin-bottom: 10px;
      color: red;
    }

    .likes {
      color: green;
    }

    .pagination {
      margin-top: 20px;
      text-align: center;
    }

    .pagination a {
      color: #000;
      padding: 8px 16px;
      text-decoration: none;
      transition: background-color .3s;
      border: 1px solid #ddd;
    }

    .post-container img {
      width: 100%;
      height: auto;
    }

    .pagination a.active {
      background-color: #4CAF50;
      color: white;
      border: 1px solid #4CAF50;
    }

    .pagination a:hover:not(.active) {
      background-color: #ddd;
    }
  </style>
</head>

<body>
  <div class="header">
    <div class="logo">
      <a href="./index.php">
        <img src="../assets/Instameme - Logo.png" alt="Logo" /></a>
    </div>
    <a href="./index.php" class="link">Accueil</a>
    <div class="nav">
      <form method="GET" action="">
        <input type="text" name="search" placeholder="Recherche" />
        <button type="submit">🔍</button>
      </form>
    </div>

    <div class="profile">
      <?php
      session_start();
      // Check if the user is logged in
      if (isset($_SESSION['user_id'])) {
        echo '<a href="./inscription.php">Inscription</a>';
        echo '<a href="./connexion.php">Connexion</a>';
      } else {
        echo '<a href="./ajout.php">Créer</a>';
        echo '<a href="./utilisateur.php">Profil</a>';
        echo '<a href="./inscription.php">Déconnexion</a>';
      }
      ?>
    </div>
  </div>

  <div class="gallery">
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

    // Pagination
    $items_per_page = 10; // Number of posts per page
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Check if a search query is submitted
    if (isset($_GET['search'])) {
      $search = $_GET['search'];
      // Modify the SQL query to include the search condition
      $sql = "SELECT u.pseudo, u.date_inscription, c.message, COUNT(l.id_contenu) AS likes, i.chemin_image 
              FROM utilisateurs u
              LEFT JOIN commentaires c ON u.id = c.id_utilisateur
              LEFT JOIN likes l ON u.id = l.id_utilisateur
              LEFT JOIN contenus i ON u.id = i.id_utilisateur
              WHERE u.pseudo LIKE '%$search%'
              GROUP BY u.id
              LIMIT $offset, $items_per_page";
    } else {
      // Default SQL query without search condition
      $sql = "SELECT u.pseudo, u.date_inscription, c.message, COUNT(l.id_contenu) AS likes, i.chemin_image 
              FROM utilisateurs u
              LEFT JOIN commentaires c ON u.id = c.id_utilisateur
              LEFT JOIN likes l ON u.id = l.id_utilisateur
              LEFT JOIN contenus i ON u.id = i.id_utilisateur
              GROUP BY u.id
              LIMIT $offset, $items_per_page";
    }

    // Query to fetch posts with user details and images
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Display each post dynamically within a post container
        echo '<div class="post-container">';
        echo '<div class="user-name">' . $row["pseudo"] . '</div>';
        if ($row["chemin_image"]) {
          // Display the image if available
          echo '<img src="../assets/images/' . $row["chemin_image"] . '" alt="Post Image" />';
        } else {
          // Display a placeholder if no image available
          echo '<div class="no-image">No image available</div>';
        }
        if ($row["message"]) {
          echo '<div class="commentaire">' . $row["message"] . '</div>';
        } else {
          echo '<div class="commentaire">No comment</div>';
        }
        echo '<div class="likes">' . $row["likes"] . ' Likes</div>';
        echo '</div>';
      }
    } else {
      echo "No posts found";
    }

    // Close the database connection
    $conn->close();
    ?>

    <!-- Pagination Links -->
    <div class="pagination">
      <?php
      // Display pagination links
      $conn = new mysqli($servername, $username, $password, $database); // Reconnect to get total pages
      $total_pages_sql = "SELECT COUNT(*) FROM utilisateurs";
      $result = $conn->query($total_pages_sql);
      $total_rows = $result->fetch_array()[0];
      $total_pages = ceil($total_rows / $items_per_page);

      for ($i = 1; $i <= $total_pages; $i++) {
        $active_class = ($i == $current_page) ? "active" : "";
        echo '<a href="?page=' . $i . '" class="' . $active_class . '">' . $i . '</a>';
      }

      // Close the database connection
      $conn->close();
      ?>
    </div>
  </div>
</body>

</html>