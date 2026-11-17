<?php
declare(strict_types=1);
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function validate_member(array $data): array {
    $errors = [];
    if ($data['full_name'] === '' || mb_strlen($data['full_name']) > 120) $errors[] = 'Enter a name (up to 120 characters).';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 190) $errors[] = 'Enter a valid email address.';
    if (!preg_match('/^[0-9+(). -]{7,30}$/', $data['phone'])) $errors[] = 'Enter a valid phone number (7–30 characters).';
    if (!in_array($data['membership_type'], ['Student','Faculty','Community'], true)) $errors[] = 'Select a membership type.';
    if ($data['favorite_genre'] === '' || mb_strlen($data['favorite_genre']) > 60) $errors[] = 'Enter a favorite genre (up to 60 characters).';
    if (mb_strlen($data['notes']) > 1000) $errors[] = 'Notes must be 1,000 characters or fewer.';
    return $errors;
}
