<form action="{{ route('download.store') }}" method="POST">
    @csrf
    <label>Kategori Pengunduh:</label>
    <input type="text" name="kategori_pengunduh" required><br>

    <label>Nama Instansi:</label>
    <input type="text" name="nama_instansi" required><br>

    <label>Email:</label>
    <input type="email" name="email_pengunduh" required><br>

    <label>Telpon:</label>
    <input type="text" name="telpon"><br>

    <label>Keperluan:</label>
    <textarea name="keperluan"></textarea><br>

    <button type="submit">Unduh Data</button>
</form>
