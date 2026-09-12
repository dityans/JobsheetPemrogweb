// Mengambil & menampilkan Daftar Buku secara asinkron dari data/buku.json
function muatDaftarBuku() {
    muatDataGenerik(
        "../data/buku.json",                 // Parameter 1: File JSON
        ".table-responsive table tbody",     // Parameter 2: Selector Tabel
        ["judul", "pengarang", "tahun", "stok", "kategori"] // Parameter 3: Daftar Kunci / Field
    );
}

const btnReload = document.querySelector('#btn-reload');
if (btnReload) {
  btnReload.addEventListener('click', function () {
    muatDaftarBuku();
  });
}

document.addEventListener("DOMContentLoaded", muatDaftarBuku);
