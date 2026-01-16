<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Runtut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: var(--body-bg);
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
<img src="../../runtut-web/assets/img/logo/full.png" alt="Runtut Logo" class="img-fluid mb-2" style="max-height: 80px;">
            <p class="text-muted small">Silakan masuk untuk daftar.</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 text-center small mb-3">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success py-2 text-center small mb-3">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="actions/auth.php?action=login" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">USERNAME</label>
                <input type="text" name="username" class="form-control shadow-none" placeholder="Masukan username" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">PASSWORD</label>
                <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold mb-3">Masuk Sekarang</button>
        </form>

        <div class="text-center">
            <p class="small text-muted mb-0">Belum punya akun? <a href="index.php?page=register" class="text-primary fw-bold text-decoration-none">Daftar</a></p>
        </div>
    </div>

</body>

</html>