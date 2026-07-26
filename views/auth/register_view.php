<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Runtut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Syne:wght@400..800&display=swap');
    </style>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: var(--neo-purple);
            background-image: radial-gradient(var(--black) 1.5px, transparent 1.5px);
            background-size: 25px 25px;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
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
            <h2 class="fw-bold mb-1" style="color: var(--black);">CREATE ACCOUNT</h2>
            <p class="text-dark fw-bold small mb-0" style="font-family: 'JetBrains Mono', monospace;">Join us to organize your tasks.</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger py-2 text-center small mb-3 neo-border fw-bold text-dark" style="background-color: var(--neo-pink);">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="actions/auth.php?action=register" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">FULL NAME</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">USERNAME</label>
                <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="Create a strong password" required>
            </div>
            <button type="submit" class="btn btn-warning w-100 fw-bold mb-3 fs-5">COUNT ME IN!</button>
        </form>

        <div class="text-center mt-3 border-top border-dark pt-3" style="border-top-width: 2px !important;">
            <p class="small text-dark fw-bold mb-0">Been here before? <a href="index.php" class="text-decoration-none" style="color: var(--neo-blue); text-shadow: 1px 1px 0px var(--black);">LOG IN</a></p>
        </div>
    </div>

</body>

</html>