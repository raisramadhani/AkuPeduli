<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $conn->real_escape_string($_POST['judul']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $author = $conn->real_escape_string($_POST['author']);
    $created_at = date("Y-m-d H:i:s");

    // Upload image
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . basename($image_name);

    if (move_uploaded_file($image_tmp_name, $target_file)) {
        $sql = "INSERT INTO artikel (judul, deskripsi, image, author, created_at) VALUES ('$judul', '$deskripsi', '$target_file', '$author', '$created_at')";

        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success" role="alert">Artikel berhasil disimpan!</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: ' . $sql . '<br>' . $conn->error . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Gagal mengunggah gambar.</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="artikel.php">AkuPeduli</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="artikel.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="team.php">Team</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Tambah Artikel</h1>
        <form action="add-artikel.php" method="post" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul:</label>
                <input type="text" id="judul" name="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author:</label>
                <input type="text" id="author" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Artikel</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>