document.addEventListener("DOMContentLoaded", function() {
    const btnMin = document.getElementById("btn-min");
    const btnPlus = document.getElementById("btn-plus");
    const jumlahInput = document.getElementById("jumlah-input");
    const totalHargaTampil = document.getElementById("total-harga");
    
    // Ambil harga dari tag HTML (hilangkan titik pemisah ribuan)
    const hargaSatuanText = document.getElementById("harga-satuan").innerText;
    const hargaSatuan = parseInt(hargaSatuanText.replace(/\./g, ''));

    // Fungsi untuk memperbarui total harga
    function updateTotal() {
        let jumlah = parseInt(jumlahInput.value);
        let total = jumlah * hargaSatuan;
        // Format ke format uang Rupiah (contoh: 25000 jadi 25.000)
        totalHargaTampil.innerText = total.toLocaleString('id-ID');
    }

    // Tombol Tambah
    btnPlus.addEventListener("click", function() {
        jumlahInput.value = parseInt(jumlahInput.value) + 1;
        updateTotal();
    });

    // Tombol Kurang
    btnMin.addEventListener("click", function() {
        if (parseInt(jumlahInput.value) > 1) {
            jumlahInput.value = parseInt(jumlahInput.value) - 1;
            updateTotal();
        }
    });
});