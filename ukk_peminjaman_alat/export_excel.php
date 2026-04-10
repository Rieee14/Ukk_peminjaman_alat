<?php
include 'config.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Peminjaman_SMK.xls");

?>
<center><h3>LAPORAN PEMINJAMAN ALAT SMK</h3></center>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Nama Alat</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $res = mysqli_query($conn, "SELECT u.nama_lengkap, a.nama_alat, p.*, COALESCE(pg.denda, 0) as total_denda 
                                   FROM peminjaman p 
                                   JOIN users u ON p.id_user = u.id_user 
                                   JOIN alat a ON p.id_alat = a.id_alat
                                   LEFT JOIN pengembalian pg ON p.id_peminjaman = pg.id_peminjaman");
        while($row = mysqli_fetch_assoc($res)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_lengkap']; ?></td>
            <td><?= $row['nama_alat']; ?></td>
            <td><?= $row['tgl_pinjam']; ?></td>
            <td><?= $row['tgl_kembali_rencana']; ?></td>
            <td><?= $row['status']; ?></td>
            <td><?= $row['total_denda']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>