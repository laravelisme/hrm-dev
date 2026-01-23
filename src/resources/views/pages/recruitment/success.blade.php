<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pendaftaran Berhasil - Recruitment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid">
        <span class="navbar-brand fw-semibold">
            <i class="bi bi-person-check me-2"></i> Recruitment
        </span>
        <span class="text-muted small d-none d-md-inline">Pendaftaran Berhasil</span>
    </div>
</nav>

<main class="container py-4">
    <div class="mb-3">
        <h4 class="mb-0">Pendaftaran Berhasil</h4>
        <div class="text-muted">Recruitment</div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success mb-0">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle fs-4"></i>
                        <div>
                            <div class="fw-semibold">Terima kasih!</div>
                            <div>Data pendaftaran berhasil dikirim.</div>
                            <div class="text-muted small mt-1">Anda dapat menutup halaman ini.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
