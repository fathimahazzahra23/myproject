<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Film Terbaik</title>
    <link rel="stylesheet" href="style.css">
    <!-- Menambahkan font dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>🎥 Rekomendasi Film Terbaik 🎬</h1>
        <p>Bagikan rekomendasi film favoritmu dan lihat ulasan dari pengguna lain!</p>
    </header>

    <main>
        <section class="container">
            <section class="form-section">
                <h2>Tambah Rekomendasi Film</h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judul">Judul Film:</label>
                        <input type="text" name="judul" placeholder="Masukkan judul film" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <textarea name="deskripsi" placeholder="Masukkan deskripsi film" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar Film:</label>
                        <input type="file" name="gambar" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <input type="number" name="rating" placeholder="Rating (1-5)" min="1" max="5" step="0.1" required>
                    </div>
                    <input type="submit" name="submit_film" value="Kirim Rekomendasi">
                </form>

                <?php
                if (isset($_POST['submit_film'])) {
                    // Menangani karakter khusus dengan mysqli_real_escape_string()
                    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
                    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                    $rating = $_POST['rating'];
                    $gambar = $_FILES['gambar']['name'];
                    $target_dir = "gambar/";  // Folder tempat gambar disimpan
                    $target_file = $target_dir . basename($gambar);
                    
                    // Cek apakah file berhasil diupload
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                        // Menyimpan data film ke database
                        $query = "INSERT INTO film (judul, deskripsi, gambar, rating) VALUES ('$judul', '$deskripsi', '$gambar', '$rating')";
                        if (mysqli_query($conn, $query)) {
                            echo "<p class='success'>Film berhasil direkomendasikan!</p>";
                        } else {
                            echo "<p class='error'>Terjadi kesalahan: " . mysqli_error($conn) . "</p>";
                        }
                    } else {
                        echo "<p class='error'>Terjadi kesalahan saat mengupload gambar!</p>";
                    }
                }
                
                ?>
            </section>

            <section class="film-section">
                <h2>Daftar Rekomendasi Film</h2>
                <div class="film-container">
                    <?php
                    $query = "SELECT * FROM film ORDER BY rating DESC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='film-card'>";
                        echo "<img src='gambar/" . $row['gambar'] . "' alt='" . $row['judul'] . "'>";
                        echo "<div class='film-info'>";
                        echo "<h3>" . $row['judul'] . "</h3>";
                        echo "<p>Rating: <strong>" . number_format($row['rating'], 1) . " / 5</strong></p>";
                        echo "<p>" . substr($row['deskripsi'], 0, 100) . "...</p>";
                        echo "<a href='detail.php?id=" . $row['id'] . "' class='detail-btn'>Lihat Detail</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </section>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Rekomendasi Film | Dibuat dengan ❤️ oleh Penggemar Film</p>
    </footer>
</body>
</html>
