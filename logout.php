<?php
// logout.php - calculates duration of usage, ends the session, shows the Thank You page
session_start();

if (!isset($_SESSION['start'], $_SESSION['name'])) {
    header('Location: index.html');
    exit;
}

$name = htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8');
$secs = time() - (int) $_SESSION['start'];
$duration = sprintf('%dh %dm %ds', intdiv($secs, 3600), intdiv($secs % 3600, 60), $secs % 60);

// End the session
$_SESSION = [];
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>thank you</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="label">hello, session<br>@php-demo</div>

<main class="sheet">
  <div class="light">
    <nav>
      <a href="index.html">log in again</a>
      <span class="right">logged out</span>
    </nav>
    <section class="hero">
      <h1 class="big-thanks">Thank you,<br><?= $name ?></h1>
      <div class="row">
        <a class="pill" href="index.html">log in again</a>
        <span class="tag">session ended</span>
      </div>
    </section>
  </div>

  <div class="darkband dark">
    <h2 class="section">(usage)</h2>
    <div class="grid">
      <div class="card"><h3>time spent</h3><p>from login to logout<br><?= $duration ?></p></div>
      <div class="card"><h3>session</h3><p>cleared and destroyed</p></div>
    </div>
  </div>
</main>

</body>
</html>
