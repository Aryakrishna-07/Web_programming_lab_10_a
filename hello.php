<?php
// hello.php - receives the name, stores it in the session, shows the hello page
session_start();

// Form submitted from index.html: save name + start time, then redirect (avoids resubmit on refresh)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        header('Location: index.html');
        exit;
    }
    $_SESSION['name']  = $name;
    $_SESSION['start'] = time();
    header('Location: hello.php');
    exit;
}

// Opened directly without logging in
if (!isset($_SESSION['name'])) {
    header('Location: index.html');
    exit;
}

$name  = htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8');
$start = (int) $_SESSION['start'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>hello, <?= $name ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="label">hello, session<br>@php-demo</div>

<main class="sheet">
  <div class="light">
    <nav>
      <a href="#session">session</a>
      <a href="logout.php">log out</a>
      <span class="right">started <?= date('H:i:s', $start) ?></span>
    </nav>

    <section class="hero">
      <h1>Hello, <?= $name ?></h1>
      <div class="row">
        <a class="pill" href="logout.php">log out</a>
        <span class="tag">session active</span>
      </div>
    </section>

    <section id="session">
      <h2 class="section">(session)</h2>
      <div class="grid">
        <div class="card"><h3>your name</h3><p>taken from the form and saved in the session<br><?= $name ?></p></div>
        <div class="card"><h3>start time</h3><p>saved when you pressed submit<br><?= date('d M Y, H:i:s', $start) ?></p></div>
        <div class="card"><h3>time here</h3><p>updates every second<br><span id="elapsed">0s</span></p></div>
      </div>
      <div class="cta"><a class="pill big" href="logout.php">log out</a></div>
    </section>
  </div>
</main>

<script>
  const start = <?= $start ?> * 1000;
  const el = document.getElementById('elapsed');
  function tick() {
    const s = Math.max(0, Math.floor((Date.now() - start) / 1000));
    el.textContent = Math.floor(s / 3600) + 'h ' + Math.floor(s % 3600 / 60) + 'm ' + (s % 60) + 's';
  }
  tick(); setInterval(tick, 1000);
</script>

</body>
</html>
