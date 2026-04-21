<?php
// ================================================
// login.php - Connexion Admin
// ================================================
require_once __DIR__ . '/config.php';

$error = '';

// Si déjà connecté → rediriger vers admin
if (isset($_SESSION['admin_id'])) {
    header('Location: ../admin/dashboard.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // Rechercher l'utilisateur dans la BD
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['username'];
            $_SESSION['admin_role'] = $user['role'];
            header('Location: ../admin/dashboard.php');
            exit;
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Milano Studio — Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --gold: #c9a96e;
            --dark: #0e0e0e;
            --dark-2: #1a1a1a;
            --dark-3: #252525;
            --white: #f5f0eb;
            --gray: #888;
            --border: rgba(201, 169, 110, 0.2);
        }
        body {
            background: var(--dark);
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: var(--dark-2);
            border: 1px solid var(--border);
            padding: 50px 48px;
            width: 100%;
            max-width: 420px;
            border-radius: 4px;
        }
        .login-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: var(--gold);
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: 0.08em;
        }
        .login-sub {
            text-align: center;
            font-size: 0.65rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 40px;
        }
        label {
            display: block;
            font-size: 0.62rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            background: var(--dark-3);
            border: 1px solid var(--border);
            color: var(--white);
            padding: 12px 16px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            margin-bottom: 24px;
            outline: none;
            transition: border-color 0.3s;
        }
        input:focus { border-color: var(--gold); }
        .btn-login {
            width: 100%;
            background: transparent;
            border: 1px solid var(--gold);
            color: var(--gold);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            padding: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover { background: var(--gold); color: var(--dark); }
        .error {
            background: rgba(200, 50, 50, 0.15);
            border: 1px solid rgba(200, 50, 50, 0.4);
            color: #ff6b6b;
            padding: 12px 16px;
            font-size: 0.8rem;
            margin-bottom: 24px;
            border-radius: 2px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            font-size: 0.65rem;
            letter-spacing: 0.15em;
            color: var(--gray);
            text-decoration: none;
            text-transform: uppercase;
            transition: color 0.3s;
        }
        .back-link:hover { color: var(--gold); }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo">Milano Studio</div>
        <p class="login-sub">Administration</p>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" 
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                   placeholder="admin" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" 
                   placeholder="••••••••" required>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <a href="../index.php" class="back-link">← Retour au site</a>
    </div>
</body>
</html>
