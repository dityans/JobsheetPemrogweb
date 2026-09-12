// Mengambil & menampilkan Daftar Anggota secara asinkron dari data/anggota.json
function muatDaftarAnggota() {
    muatDataGenerik(
        "../data/anggota.json",               // Parameter 1: File JSON
        ".table-responsive table tbody",     // Parameter 2: Selector Tabel
        ["no_anggota", "nama", "alamat", "no_hp"]     // Parameter 3: Daftar Kunci / Field
    );
}

document.addEventListener("DOMContentLoaded", muatDaftarAnggota);
