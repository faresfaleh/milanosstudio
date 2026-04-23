<?php
// ================================================
// admin/dashboard.php - Tableau de bord Admin
// ================================================
require_once __DIR__ . '/../php/config.php';

// Vérifier connexion
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../php/login.php');
    exit;
}

// Actions sur les réservations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id     = (int)($_POST['res_id'] ?? 0);
    $action = $_POST['action'];

    if ($action === 'confirmer') {
        $pdo->prepare("UPDATE reservations SET statut='confirmée' WHERE id=?")->execute([$id]);
    } elseif ($action === 'annuler') {
        $pdo->prepare("UPDATE reservations SET statut='annulée' WHERE id=?")->execute([$id]);
    } elseif ($action === 'supprimer') {
        $pdo->prepare("DELETE FROM reservations WHERE id=?")->execute([$id]);
    } elseif ($action === 'modifier') {
        $nom_homme   = trim($_POST['nom_homme'] ?? '');
        $nom_femme   = trim($_POST['nom_femme'] ?? '');
        $telephone   = trim($_POST['telephone'] ?? '');
        $cin         = trim($_POST['cin'] ?? '');
        $date_soiree = trim($_POST['date_soiree'] ?? '');
        $choix_prix  = trim($_POST['choix_prix'] ?? '');
        $pdo->prepare("UPDATE reservations SET nom_homme=?, nom_femme=?, telephone=?, cin=?, date_soiree=?, choix_prix=? WHERE id=?")
            ->execute([$nom_homme, $nom_femme, $telephone, $cin, $date_soiree, $choix_prix, $id]);
    }
    header('Location: dashboard.php');
    exit;
}

// Statistiques
$total     = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$attente   = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut='en attente'")->fetchColumn();
$confirmee = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut='confirmée'")->fetchColumn();
$annulee   = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut='annulée'")->fetchColumn();

// Liste des réservations (les plus récentes en premier)
$reservations = $pdo->query("SELECT * FROM reservations ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Milano Studio — Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --red: #E31E24;
            --red-light: rgba(227,30,36,0.08);
            --white: #ffffff;
            --off-white: #f8f8f8;
            --text: #1a1a1a;
            --text-light: #555;
            --gray: #888;
            --border: rgba(0,0,0,0.08);
            --green: #2e9e5b;
            --yellow: #c9860a;
            --red-status: #d94040;
        }
        body {
            background: var(--off-white);
            color: var(--text);
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
        }
        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            height: 100vh;
            background: var(--white);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 32px 24px;
            box-shadow: 2px 0 12px rgba(0,0,0,0.05);
        }
        .sidebar-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--red);
            margin-bottom: 4px;
        }
        .sidebar-sub {
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 40px;
        }
        .sidebar-nav a {
            display: block;
            padding: 10px 14px;
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 4px;
            transition: all 0.2s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: var(--red-light);
            color: var(--red);
        }
        .sidebar-logout { margin-top: auto; }
        .sidebar-logout a {
            display: block;
            padding: 10px 14px;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--red-status);
            text-decoration: none;
            border: 1px solid rgba(227,30,36,0.25);
            border-radius: 4px;
            transition: all 0.2s;
            text-align: center;
        }
        .sidebar-logout a:hover { background: rgba(227,30,36,0.06); }

        /* MAIN */
        .main { margin-left: 240px; padding: 40px 48px; }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--text);
            margin-bottom: 4px;
        }
        .page-sub {
            font-size: 0.65rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 40px;
        }

        /* STAT CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border-top: 3px solid var(--red);
        }
        .stat-label {
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 8px;
        }
        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: var(--text);
        }

        /* TABLE */
        .table-wrapper {
            background: var(--white);
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        th {
            font-size: 0.58rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--red);
            padding: 14px 14px;
            text-align: left;
            border-bottom: 2px solid rgba(227,30,36,0.12);
            background: rgba(227,30,36,0.02);
            white-space: nowrap;
        }
        td {
            padding: 13px 14px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            color: var(--text);
            white-space: nowrap;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(227,30,36,0.02); }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 0.58rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-radius: 20px;
            font-weight: 500;
        }
        .badge-attente  { background: rgba(201,134,10,0.1);  color: var(--yellow); border: 1px solid rgba(201,134,10,0.25); }
        .badge-confirmee { background: rgba(46,158,91,0.1);  color: var(--green);  border: 1px solid rgba(46,158,91,0.25); }
        .badge-annulee  { background: rgba(217,64,64,0.1);   color: var(--red-status); border: 1px solid rgba(217,64,64,0.25); }

        .actions { display: flex; gap: 5px; }
        .btn-action {
            border: none;
            padding: 6px 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.6rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 4px;
            transition: opacity 0.2s, transform 0.2s;
            font-weight: 500;
        }
        .btn-action:hover { opacity: 0.85; transform: translateY(-1px); }
        .btn-confirm { background: var(--green);      color: #fff; }
        .btn-cancel  { background: var(--yellow);     color: #fff; }
        .btn-delete  { background: var(--red-status); color: #fff; }

        @media (max-width: 900px) {
            .sidebar { display: none; }
            .main { margin-left: 0; padding: 24px; }
            .stats { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">Milano Studio</div>
    <p class="sidebar-sub">Administration</p>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="active">📋 Réservations</a>
        <a href="../index.php" target="_blank">🌐 Voir le site</a>
    </nav>
    <div class="sidebar-logout">
        <a href="logout.php">Déconnexion</a>
    </div>
</div>

<div class="main">
    <h1 class="page-title">Tableau de bord</h1>
    <p class="page-sub">Bienvenue, <?= htmlspecialchars($_SESSION['admin_name']) ?></p>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <p class="stat-label">Total</p>
            <p class="stat-num"><?= $total ?></p>
        </div>
        <div class="stat-card">
            <p class="stat-label">En attente</p>
            <p class="stat-num" style="color: var(--yellow)"><?= $attente ?></p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Confirmées</p>
            <p class="stat-num" style="color: var(--green)"><?= $confirmee ?></p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Annulées</p>
            <p class="stat-num" style="color: var(--red)"><?= $annulee ?></p>
        </div>
    </div>

    <!-- Table des réservations -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Homme</th>
                    <th>Femme</th>
                    <th>📞 Téléphone</th>
                    <th>🪪 CIN</th>
                    <th>Date soirée</th>
                    <th>Package</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Reçu le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservations)): ?>
                    <tr>
                        <td colspan="11" style="text-align:center; color:var(--gray); padding:40px">
                            Aucune réservation pour l'instant.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['nom_homme']) ?></td>
                        <td><?= htmlspecialchars($r['nom_femme']) ?></td>
                        <td><a href="tel:<?= htmlspecialchars($r['telephone']) ?>" style="color:var(--red); text-decoration:none; font-weight:500;"><?= htmlspecialchars($r['telephone']) ?></a></td>
                        <td style="font-family:monospace; letter-spacing:0.05em; color:var(--text-light);"><?= htmlspecialchars($r['cin']) ?></td>
                        <td><?= $r['date_soiree'] ?></td>
                        <td><?= htmlspecialchars($r['choix_prix'] ?? $r['choix_package'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($r['wedding_type'] ?? '—') ?></td>
                        <td>
                            <?php
                            $statut = $r['statut'];
                            $cls = match($statut) {
                                'en attente' => 'badge-attente',
                                'confirmée'  => 'badge-confirmee',
                                'annulée'    => 'badge-annulee',
                                default      => ''
                            };
                            ?>
                            <span class="badge <?= $cls ?>"><?= $statut ?></span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                        <td>
                            <div class="actions">
                                <?php if ($r['statut'] === 'en attente'): ?>
                                <form method="POST">
                                    <input type="hidden" name="res_id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="action" value="confirmer">
                                    <button class="btn-action btn-confirm">✓</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="res_id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="action" value="annuler">
                                    <button class="btn-action btn-cancel">✕</button>
                                </form>
                                <?php endif; ?>

                                <form method="POST" onsubmit="return confirm('Supprimer cette réservation ?')">
                                    <input type="hidden" name="res_id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="action" value="supprimer">
                                    <button class="btn-action btn-delete">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="edit-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9000; align-items:center; justify-content:center;" onclick="if(event.target===this)closeEdit()">
    <div style="background:#fff; border-radius:12px; padding:36px; max-width:520px; width:90%; position:relative; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <button onclick="closeEdit()" style="position:absolute;top:14px;right:18px;background:none;border:none;color:#aaa;font-size:1.3rem;cursor:pointer;">✕</button>
        <h3 style="font-family:'Playfair Display',serif; font-size:1.5rem; color:#1a1a1a; margin-bottom:4px;">Modifier la réservation</h3>
        <p id="edit-subtitle" style="font-size:0.62rem; letter-spacing:0.15em; text-transform:uppercase; color:#E31E24; margin-bottom:24px;"></p>
        <form method="POST" id="edit-form">
            <input type="hidden" name="res_id" id="edit-res-id">
            <input type="hidden" name="action" value="modifier">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; color:#888; display:block; margin-bottom:6px;">Nom Homme</label>
                    <input type="text" name="nom_homme" id="edit-homme" style="width:100%; border:1px solid rgba(0,0,0,0.12); border-radius:6px; padding:10px 12px; font-size:0.9rem; outline:none; transition:border 0.2s;" onfocus="this.style.borderColor='#E31E24'" onblur="this.style.borderColor='rgba(0,0,0,0.12)'">
                </div>
                <div>
                    <label style="font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; color:#888; display:block; margin-bottom:6px;">Nom Femme</label>
                    <input type="text" name="nom_femme" id="edit-femme" style="width:100%; border:1px solid rgba(0,0,0,0.12); border-radius:6px; padding:10px 12px; font-size:0.9rem; outline:none; transition:border 0.2s;" onfocus="this.style.borderColor='#E31E24'" onblur="this.style.borderColor='rgba(0,0,0,0.12)'">
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; color:#888; display:block; margin-bottom:6px;">📞 Téléphone</label>
                    <input type="tel" name="telephone" id="edit-tel" maxlength="8" style="width:100%; border:1px solid rgba(0,0,0,0.12); border-radius:6px; padding:10px 12px; font-size:0.9rem; outline:none; transition:border 0.2s;" onfocus="this.style.borderColor='#E31E24'" onblur="this.style.borderColor='rgba(0,0,0,0.12)'">
                </div>
                <div>
                    <label style="font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; color:#888; display:block; margin-bottom:6px;">🪪 CIN</label>
                    <input type="text" name="cin" id="edit-cin" maxlength="8" style="width:100%; border:1px solid rgba(0,0,0,0.12); border-radius:6px; padding:10px 12px; font-size:0.9rem; outline:none; transition:border 0.2s;" onfocus="this.style.borderColor='#E31E24'" onblur="this.style.borderColor='rgba(0,0,0,0.12)'">
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; color:#888; display:block; margin-bottom:6px;">📅 Date de la soirée</label>
                <input type="date" name="date_soiree" id="edit-date" style="width:100%; border:1px solid rgba(0,0,0,0.12); border-radius:6px; padding:10px 12px; font-size:0.9rem; outline:none; transition:border 0.2s; color-scheme:light;" onfocus="this.style.borderColor='#E31E24'" onblur="this.style.borderColor='rgba(0,0,0,0.12)'">
            </div>
            <div style="margin-bottom:24px;">
                <label style="font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; color:#888; display:block; margin-bottom:6px;">💰 Options / Prix</label>
                <input type="text" name="choix_prix" id="edit-prix" placeholder="ex: 2 caméras + drone — 1150 DT" style="width:100%; border:1px solid rgba(0,0,0,0.12); border-radius:6px; padding:10px 12px; font-size:0.9rem; outline:none; transition:border 0.2s;" onfocus="this.style.borderColor='#E31E24'" onblur="this.style.borderColor='rgba(0,0,0,0.12)'">
            </div>
            <button type="submit" style="width:100%; background:#E31E24; color:#fff; border:none; padding:13px; border-radius:6px; font-family:'Montserrat',sans-serif; font-size:0.65rem; letter-spacing:0.2em; text-transform:uppercase; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#b81519'" onmouseout="this.style.background='#E31E24'">
                Enregistrer les modifications
            </button>
        </form>
    </div>
</div>

<style>
</style>

<script>
function openEdit(r) {
    document.getElementById('edit-res-id').value  = r.id;
    document.getElementById('edit-subtitle').textContent = (r.choix_prix || r.choix_package || '') + ' — ' + (r.wedding_type || '');
    document.getElementById('edit-homme').value   = r.nom_homme || '';
    document.getElementById('edit-femme').value   = r.nom_femme || '';
    document.getElementById('edit-tel').value     = r.telephone || '';
    document.getElementById('edit-cin').value     = r.cin || '';
    document.getElementById('edit-date').value    = r.date_soiree || '';
    document.getElementById('edit-prix').value    = r.choix_prix || '';
    const overlay = document.getElementById('edit-overlay');
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeEdit() {
    document.getElementById('edit-overlay').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeEdit();
});
</script>

</body>
</html>