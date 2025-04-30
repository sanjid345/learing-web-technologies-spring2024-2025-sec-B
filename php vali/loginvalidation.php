<?php
// Sample valid credentials (static)
$valid_email = 'user@example.com';
$valid_password = '123456';

// Get POST inputs safely
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

$errors = [];

// Check if empty
if (!$email || !$password) {
    $errors[] = "All fields are required.";
}

// Validate email with FILTER (not regex)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email is not valid.";
}

// Check password length only (no pattern)
$length = strlen($password);
if ($length < 6) {
    $errors[] = "Password must be at least 6 characters long.";
}
if ($length > 32) {
    $errors[] = "Password must not exceed 32 characters.";
}

// Simple character-type checks using loops instead of regex
$has_upper = false;
$has_lower = false;
$has_digit = false;

for ($i = 0; $i < $length; $i++) {
    $char = $password[$i];

    if ($char >= 'A' && $char <= 'Z') {
        $has_upper = true;
    } elseif ($char >= 'a' && $char <= 'z') {
        $has_lower = true;
    } elseif ($char >= '0' && $char <= '9') {
        $has_digit = true;
    }
}

if (!$has_upper) {
    $errors[] = "Password must include at least one uppercase letter.";
}
if (!$has_lower) {
    $errors[] = "Password must include at least one lowercase letter.";
}
if (!$has_digit) {
    $errors[] = "Password must include at least one number.";
}

// If there are errors
if (!empty($errors)) {
    $query = http_build_query(['error' => implode(' ', $errors)]);
    header("Location: index.html?$query");
    exit;
}

// Compare credentials
if ($email === $valid_email && $password === $valid_password) {
    header("Location: index.html?success=Login successful!");
    exit;
} else {
    header("Location: index.html?error=Invalid email or password.");
    exit;
}
?>

