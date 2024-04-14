<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Instameme - Accueil</title>
  <link rel="stylesheet" href="../css/user.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f2f2f2;
    }

    .header {
      background-color: #333;
      color: #fff;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo img {
      height: 50px;
      width: auto;
    }

    .gallery {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .photo {
      margin: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    .photo img {
      width: 100%;
      height: auto;
    }

    .actions {
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .actions button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .actions button:hover {
      background-color: #45a049;
    }

    .comments {
      padding: 20px;
      margin-top: 10px;
      background-color: #f9f9f9;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .comments h3 {
      margin-top: 0;
    }

    .comments p {
      margin-bottom: 5px;
    }

    .comment-form {
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      margin-top: 10px;
    }

    .comment-form textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
      resize: none;
    }

    .comment-form button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .comment-form button:hover {
      background-color: #45a049;
    }

    .success-message {
      background-color: #dff0d8;
      color: #3c763d;
      border: 1px solid #d6e9c6;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="header">
    <div class="logo">
      <a href="./index.php">
        <img src="../assets/Instameme - Logo.png" alt="Logo" />
      </a>
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

    // Query to fetch images from the database
    $sql = "SELECT id, chemin_image FROM contenus";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Display each image dynamically
        echo '<div class="photo">';
        echo '<a href="./contenu.php?content_id=' . $row["id"] . '"><img src="../assets/images/' . $row["chemin_image"] . '" alt="Image description" /></a>';
        echo '<div class="actions">';
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="content_id" value="' . $row["id"] . '">';
        echo '<button type="submit" name="like">Aimer</button>';
        echo '</form>';
        echo '<a href="./partage.php"><button>Partager</button></a>';
        echo '</div>';
        echo '</div>';

        // Fetch comments for each post
        $content_id = $row['id'];
        $sql_comments = "SELECT * FROM commentaires WHERE id_contenu = $content_id";
        $result_comments = $conn->query($sql_comments);
    ?>

        <div class="comments">
          <h3>Commentaires :</h3>
          <?php
          if ($result_comments->num_rows > 0) {
            while ($comment_row = $result_comments->fetch_assoc()) {
              echo '<p>' . $comment_row['message'] . '</p>';
            }
          } else {
            echo "<p>Aucun commentaire pour le moment.</p>";
          }
          ?>
        </div>

        <div class="comment-form">
          <form method="POST" action="">
            <input type="hidden" name="content_id" value="<?php echo $content_id; ?>">
            <textarea name="comment" placeholder="Ajouter un commentaire"></textarea>
            <button type="submit" name="submit_comment">Envoyer</button>
          </form>
        </div>

    <?php
      }
    } else {
      echo "No images found";
    }

    // Handle Like Button Click
    if (isset($_POST['like'])) {
      // Get content ID from the form
      $content_id = $_POST['content_id'];

      // Get the user ID (you may need to implement user authentication to get the actual user ID)
      $user_id = 1; // Assuming user ID 1 for demonstration purposes, replace with actual user ID

      // Add like to the database for the corresponding content and user
      $sql = "INSERT INTO likes (id_contenu, id_utilisateur) VALUES ('$content_id', '$user_id')";
      if ($conn->query($sql) === TRUE) {
        // Redirect back to the previous page
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
      echo '<div class="success-message">Like ajouté avec succès!</div>';
    }

    // Handle Comment Submission
    if (isset($_POST['submit_comment'])) {
      // Get content ID and comment from the form
      $content_id = $_POST['content_id'];
      $comment = $_POST['comment'];

      // Get the user ID (you may need to implement user authentication to get the actual user ID)
      $user_id = 1; // Assuming user ID 1 for demonstration purposes, replace with actual user ID

      // Add comment to the database for the corresponding content and user
      $sql = "INSERT INTO commentaires (id_contenu, id_utilisateur, message, date_publication) VALUES ('$content_id', '$user_id', '$comment', NOW())";
      if ($conn->query($sql) === TRUE) {
        // Redirect back to the previous page
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
      echo '<div class="success-message">Commentaire ajouté avec succès!</div>';
    }

    // Close the database connection
    $conn->close();
    ?>
  </div>

</body>

</html>