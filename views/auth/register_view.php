<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Runtut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #F5F6FA;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }

        .btn-primary {
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
        }

        .form-control {
            padding: 0.8rem;
            border-radius: 8px;
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: var(--primary-color);">Buat Akun</h3>
            <p class="text-muted small">Bergabunglah untuk mengatur tugasmu.</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 text-center small mb-3">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="actions/auth.php?action=register" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">NAMA LENGKAP</label>
                <input type="text" name="full_name" class="form-control shadow-none" placeholder="Contoh: Reiza Aliditia" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">USERNAME</label>
                <input type="text" name="username" class="form-control shadow-none" placeholder="Username unik" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">PASSWORD</label>
                <input type="password" name="password" class="form-control shadow-none" placeholder="Minimal 6 karakter" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold mb-3">Daftar Akun</button>
        </form>

        <div class="text-center">
            <p class="small text-muted mb-0">Sudah punya akun? <a href="index.php" class="text-primary fw-bold text-decoration-none">Masuk</a></p>
        </div>
    </div>

</body>

</html>