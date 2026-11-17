<?php
declare(strict_types=1);
ini_set('session.use_strict_mode', '1');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/config/database.php';
$errors = [];
$data = ['full_name'=>'','email'=>'','phone'=>'','membership_type'=>'','favorite_genre'=>'','notes'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $_) $data[$key] = trim((string)($_POST[$key] ?? ''));
    if (!hash_equals(csrf_token(), (string)($_POST['csrf_token'] ?? ''))) $errors[] = 'Invalid session token. Refresh and try again.';
    $errors = array_merge($errors, validate_member($data));
    if (!$errors) {
        try {
            $stmt = $pdo->prepare('INSERT INTO members (full_name, email, phone, membership_type, favorite_genre, notes) VALUES (:full_name, :email, :phone, :membership_type, :favorite_genre, :notes)');
            $stmt->execute($data);
            $_SESSION['flash'] = 'Member registered successfully!';
            header('Location: members.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = 'That email address is already registered.';
            else { error_log('Insert error: ' . $e->getMessage()); $errors[] = 'Unable to save your information. Try again.'; }
        }
    }
}
$pageTitle = 'Register a Member';
require __DIR__ . '/includes/header.php';
?>
<section class="hero"><p class="eyebrow">CIS 344 • Database-backed PHP platform</p><h1>Welcome to the Library Member Hub</h1><p>Register new library members and keep their information organized in a secure MySQL database.</p></section>
<div class="layout"><section class="panel"><h2>New member registration</h2><p class="muted">Fields marked * are required.</p>
<?php if ($errors): ?><div class="alert error" role="alert"><strong>Please fix the following:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form action="index.php" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label for="full_name">Full name *</label><input id="full_name" name="full_name" maxlength="120" required value="<?= e($data['full_name']) ?>">
<label for="email">Email *</label><input id="email" type="email" name="email" maxlength="190" required value="<?= e($data['email']) ?>">
<label for="phone">Phone *</label><input id="phone" type="tel" name="phone" maxlength="30" required value="<?= e($data['phone']) ?>">
<label for="membership_type">Membership type *</label><select id="membership_type" name="membership_type" required><option value="">Select one</option><?php foreach (['Student','Faculty','Community'] as $option): ?><option value="<?= e($option) ?>" <?= $data['membership_type'] === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select>
<label for="favorite_genre">Favorite book genre *</label><input id="favorite_genre" name="favorite_genre" maxlength="60" required placeholder="e.g., Mystery" value="<?= e($data['favorite_genre']) ?>">
<label for="notes">Additional notes</label><textarea id="notes" name="notes" maxlength="1000" rows="4" placeholder="Optional reading preferences"><?= e($data['notes']) ?></textarea>
<button type="submit">Save member to database</button></form></section>
<aside class="panel side"><h2>How it works</h2><ol><li>Fill out the registration form.</li><li>PHP validates each field and checks the security token.</li><li>A prepared SQL statement saves the member to MySQL.</li><li>View the saved entry in the member directory.</li></ol><div class="feature"><strong>Unique feature</strong><p>Favorite genre and membership type help organize library outreach and book recommendations.</p></div></aside></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
