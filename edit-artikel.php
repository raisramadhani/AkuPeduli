<?php
include 'config.php';
// Ambil data artikel berdasarkan ID
$id = $conn->real_escape_string($_GET['id']);
$sql = "SELECT * FROM artikel WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $artikel = $result->fetch_assoc();
} else {
    die("Artikel tidak ditemukan");
}

// Update data artikel jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $conn->real_escape_string($_POST['judul']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $author = $conn->real_escape_string($_POST['author']);
    $updated_at = date("Y-m-d H:i:s");
    $image_path = $artikel['image'];

    // Cek jika ada gambar baru yang diunggah
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = "uploads/";

        // Buat folder 'uploads' jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $target_file = $upload_dir . basename($image_name);
        if (move_uploaded_file($image_tmp_name, $target_file)) {
            $image_path = $target_file;
        } else {
            echo '<div class="alert alert-danger" role="alert">Gagal mengunggah gambar baru.</div>';
        }
    }

    $sql_update = "UPDATE artikel SET judul='$judul', deskripsi='$deskripsi', author='$author', image='$image_path', updated_at='$updated_at' WHERE id='$id'";

    if ($conn->query($sql_update) === TRUE) {
        // Redirect ke artikel.php dengan parameter sukses
        header("Location: artikel.php?status=success");
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible=" IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
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
        <h1 class="mb-4">Edit Artikel</h1>
        <form action="edit-artikel.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul:</label>
                <input type="text" id="judul" name="judul" class="form-control" value="<?php echo $artikel['judul']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="5" required><?php echo $artikel['deskripsi']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Author:</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php echo $artikel['author']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ganti Gambar:</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <p>Gambar saat ini: <img src="<?php echo $artikel['image']; ?>" alt="Gambar Artikel" style="width: 100px;"></p>
            </div>

            <input type="submit" value="Simpan Perubahan" class="btn btn-primary">
        </form>
    </div>
</body>

</html>