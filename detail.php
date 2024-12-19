<?php 
include 'koneksi.php'; 

// Ambil ID film dari URL
$id = $_GET['id'];

// Query untuk mendapatkan detail film berdasarkan ID
$query = "SELECT * FROM film WHERE id = $id";
$result = mysqli_query($conn, $query);
$film = mysqli_fetch_assoc($result);

// Ambil ulasan dari tabel ulasan jika ada
$query_ulasan = "SELECT * FROM ulasan WHERE film_id = $id ORDER BY tanggal DESC";
$result_ulasan = mysqli_query($conn, $query_ulasan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Film - <?php echo $film['judul']; ?></title>
    <link rel="stylesheet" href="style.css"> <!-- Link ke file CSS yang sudah ada -->
</head>
<body>
    <header>
        <h1>🎬 Detail Film: <?php echo $film['judul']; ?></h1>
        <p>Temukan lebih banyak tentang film ini dan ulasan dari penggemar lainnya!</p>
    </header>

    <main>
        <section class="detail-container">
            <div class="detail-film">
                <div class="detail-img-container">
                    <img src="gambar/<?php echo $film['gambar']; ?>" alt="<?php echo $film['judul']; ?>" class="detail-img">
                </div>
                <div class="detail-info">
                    <h2><?php echo $film['judul']; ?></h2>
                    <p><strong>Rating:</strong> <?php echo number_format($film['rating'], 1); ?> / 5</p>
                    <p><strong>Deskripsi:</strong></p>
                    <p class="sinopsis"><?php echo nl2br($film['deskripsi']); ?></p>
                </div>
            </div>
        </section>

        <section class="ulasan-form-container">
            <h3>Ulasan Pengguna</h3>
            <?php if (mysqli_num_rows($result_ulasan) > 0): ?>
                <div class="ulasan-list">
                    <?php while ($ulasan = mysqli_fetch_assoc($result_ulasan)): ?>
                        <div class="ulasan-item">
                            <p><strong><?php echo $ulasan['nama_pengguna']; ?> (Rating: <?php echo $ulasan['rating']; ?>/5):</strong></p>
                            <p><?php echo nl2br($ulasan['komentar']); ?></p>
                            <small><?php echo $ulasan['tanggal']; ?></small>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Tidak ada ulasan untuk film ini. Jadilah yang pertama memberikan ulasan!</p>
            <?php endif; ?>

            <!-- Form Tambah Ulasan -->
            <h3>Tambahkan Ulasan Anda</h3>
            <form method="post" action="">
                <input type="text" name="nama_pengguna" placeholder="Nama Anda" required><br>
                <textarea name="komentar" placeholder="Tulis ulasan..." required></textarea><br>
                <label>Rating:</label>
                <select name="rating" required>
                    <option value="5">5 - Sangat Baik</option>
                    <option value="4">4 - Baik</option>
                    <option value="3">3 - Cukup</option>
                    <option value="2">2 - Kurang</option>
                    <option value="1">1 - Buruk</option>
                </select><br>
                <input type="submit" name="submit_ulasan" value="Kirim Ulasan">
            </form>

            <?php
            if (isset($_POST['submit_ulasan'])) {
                $nama_pengguna = $_POST['nama_pengguna'];
                $komentar = $_POST['komentar'];
                $rating = $_POST['rating'];

                $insert_ulasan = "INSERT INTO ulasan (film_id, nama_pengguna, komentar, rating) 
                                  VALUES ('$id', '$nama_pengguna', '$komentar', '$rating')";
                mysqli_query($conn, $insert_ulasan);
                echo "<p>Ulasan berhasil dikirim!</p>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Rekomendasi Film | Dibuat dengan ❤️ oleh Penggemar Film</p>
    </footer>
</body>
</html>
