<?php
if (!isLoggedIn()) {
    redirect(BASE_URL . 'index.php?page=auth&action=login');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien - SIMRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-hospital-alt"></i> SIMRS</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=dashboard">Dashboard</a>
                <a class="nav-link active" href="<?php echo BASE_URL; ?>index.php?page=patient&action=index">Pasien</a>
                <span class="navbar-text me-3">
                    <?php echo $_SESSION['nama_pegawai']; ?>
                </span>
                <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=auth&action=logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Pasien</h2>
            <a href="<?php echo BASE_URL; ?>index.php?page=patient&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pasien
            </a>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. RM</th>
                            <th>NIK</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?php echo $patient['no_rekam_medis']; ?></td>
                            <td><?php echo $patient['nik']; ?></td>
                            <td><?php echo $patient['nama_pasien']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($patient['tanggal_lahir'])); ?></td>
                            <td><?php echo $patient['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                            <td><?php echo $patient['no_telepon']; ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>index.php?page=patient&action=edit&id=<?php echo $patient['no_rekam_medis']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>index.php?page=patient&action=delete&id=<?php echo $patient['no_rekam_medis']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>