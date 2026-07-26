<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Runtut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Syne:wght@400..800&display=swap');
    </style>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: var(--neo-light-green);
            /* Pola titik-titik (polkadot) bergaya retro */
            background-image: radial-gradient(var(--black) 1.5px, transparent 1.5px);
            background-size: 25px 25px;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: var(--white);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }
    </style>
</head>

<body>

    <div class="auth-card neo-border neo-shadow neo-rounded">
        <div class="text-center mb-4">
            <img src="assets/img/logo/icon.png" class="img-fluid mb-4" style="max-height: 100px;" alt="Logo Runtut">
            <h2 class="fw-bold mb-1" style="color: var(--black);">WELCOME BACK</h2>
            <p class="text-dark fw-bold small mb-0" style="font-family: 'JetBrains Mono', monospace;">Take a deep breath and start planning. </p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 text-center small mb-3 neo-border fw-bold text-dark" style="background-color: var(--neo-pink);">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success py-2 text-center small mb-3 neo-border fw-bold text-dark" style="background-color: var(--neo-light-green);">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="actions/auth.php?action=login" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">USERNAME</label>
                <input type="text" name="username" class="form-control" placeholder="Type username..." required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="Type password..." required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold mb-3 fs-5">Let's Go!</button>
        </form>

        <div class="text-center mt-3 border-top border-dark pt-3" style="border-top-width: 2px !important;">
            <p class="small text-dark fw-bold mb-0">New here? <a href="index.php?page=register" class="text-decoration-none" style="color: var(--neo-blue); text-shadow: 1px 1px 0px var(--black);">Join now</a></p>
        </div>
    </div>

</body>

</html>