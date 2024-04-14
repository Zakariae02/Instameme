<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partage - Instameme</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Instameme</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="ajout.php">Créer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="utilisateur.php">Profil</a>
                </li>

            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4">Partage</h2>
                <?php
                // Assuming content_id is passed via GET parameter
                if (isset($_GET['content_id'])) {
                    // Get the content ID from the URL
                    $content_id = $_GET['content_id'];

                    // Query to fetch the image path from the database based on content ID
                    // Replace this with your actual database connection and query
                    $servername = "localhost";
                    $username = "root";
                    $password = "password";
                    $database = "instameme";

                    $conn = new mysqli($servername, $username, $password, $database);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT chemin_image FROM contenus WHERE id = $content_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $image_path = '../assets/images/' . $row["chemin_image"];
                        // Display the image
                        echo '<div class="card">';
                        echo '<img src="' . $image_path . '" class="card-img-top" alt="Image">';
                        echo '<div class="card-body">';
                        echo '<p class="card-text">Image partagée</p>';
                        echo '</div>';
                        echo '</div>';
                        // Display the form for entering description
                        echo '<form class="mt-4" action="process_share.php" method="POST">';
                        echo '<input type="hidden" name="content_id" value="' . $content_id . '">';
                        echo '<div class="form-group">';
                        echo '<label for="description">Description</label>';
                        echo '<textarea class="form-control" id="description" name="description" rows="3" required></textarea>';
                        echo '</div>';
                        echo '<button type="submit" class="btn btn-primary">Partager</button>';
                        echo '</form>';
                    } else {
                        echo "Image not found.";
                    }
                    $conn->close();
                } else {
                    echo "Content ID not provided.";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>