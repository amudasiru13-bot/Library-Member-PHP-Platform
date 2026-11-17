<?php
declare(strict_types=1);
ini_set('session.use_strict_mode', '1');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/config/database.php';
$search = trim((string)($_GET['search'] ?? ''));
$search = mb_substr($search, 0, 100);
if ($search !== '') {
    $stmt = $pdo->prepare('SELECT id, full_name, email, phone, membership_type, favorite_genre, notes, created_at FROM members WHERE full_name LIKE :search OR favorite_genre LIKE :genre ORDER BY created_at DESC, id DESC LIMIT 100');
    $term = '%' . $search . '%';
    $stmt->execute(['search'=>$term,'genre'=>$term]);
} else {
    $stmt = $pdo->query('SELECT id, full_name, email, phone, membership_type, favorite_genre, notes, created_at FROM members ORDER BY created_at DESC, id DESC LIMIT 100');
}
$members = $stmt->fetchAll();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$pageTitle = 'Member Directory';
require __DIR__ . '/includes/header.php';
?>
<section class="hero"><p class="eyebrow">Saved database records</p><h1>Member Directory</h1><p>Browse the latest 100 registered members or search by name and favorite genre.</p></section>
<?php if ($flash): ?><div class="alert success" role="status"><?= e((string)$flash) ?></div><?php endif; ?>
<section class="panel"><form method="get" action="members.php" class="search"><label for="search">Search members</label><div class="search-row"><input id="search" name="search" maxlength="100" value="<?= e($search) ?>" placeholder="Name or genre"><button type="submit">Search</button></div></form>
<?php if (!$members): ?><p>No matching members yet. <a href="index.php">Register a member</a>.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Membership</th><th>Genre</th><th>Notes</th><th>Registered</th></tr></thead><tbody><?php foreach ($members as $member): ?><tr><td><?= e($member['full_name']) ?></td><td><?= e($member['email']) ?></td><td><?= e($member['phone']) ?></td><td><?= e($member['membership_type']) ?></td><td><?= e($member['favorite_genre']) ?></td><td><?= e($member['notes']) ?></td><td><?= e($member['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<p class="muted privacy">Demo note: the directory displays personal information without login. Add authentication and access control before using real member data on a public website.</p></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
