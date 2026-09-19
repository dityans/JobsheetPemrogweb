<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Ambil kata kunci pencarian
$keyword = $_GET['keyword'] ?? '';

if (!empty($keyword)) {
    // Query pencarian dengan ILIKE
    $sql = "SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    // Tambahkan wildcard % agar mencari teks yang mengandung keyword
    $stmt->execute([':keyword' => "%$keyword%"]);
} else {
    // Query default jika tidak ada pencarian
    $sql = "SELECT * FROM buku ORDER BY id DESC";
    $stmt = $pdo->query($sql);
}

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <!-- Form Pencarian HTML -->
            <form action="" method="GET" class="mb-3">
                <div class="search-box">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari judul buku..." value="<?= htmlspecialchars($keyword) ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <?php if (!empty($keyword)): ?>
                        <a href="list.php" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="5">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?= htmlspecialchars($buku['judul']) ?></td>
                            <td><?= htmlspecialchars($buku['pengarang']) ?></td>
                            <td><?= htmlspecialchars($buku['tahun']) ?></td>
                            <td><?= htmlspecialchars($buku['stok']) ?></td>
                            <td><?= htmlspecialchars($buku['tanggal_ditambahkan']) ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
