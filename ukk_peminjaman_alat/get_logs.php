<?php
// Query untuk mengambil 5 aktivitas terbaru
$log_query = mysqli_query($conn, "SELECT l.*, u.nama_lengkap 
                                 FROM log_aktivitas l 
                                 JOIN users u ON l.id_user = u.id_user 
                                 ORDER BY waktu DESC LIMIT 5");

while($log = mysqli_fetch_assoc($log_query)) {
    echo "<div class='alert alert-light border-0 shadow-sm mb-2' style='font-size: 0.85rem;'>
            <span class='badge bg-secondary'>" . date('H:i', strtotime($log['waktu'])) . "</span> 
            <strong>" . $log['nama_lengkap'] . "</strong>: " . $log['aksi'] . "
          </div>";
}
?>