<?php
// index.php - Hello / Logout app using PHP sessions
session_start();

$page = $_GET['page'] ?? 'home';
$error = '';

// ---- Handle form submit: store name and start time in session ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $_SESSION['name']  = $name;
        $_SESSION['start'] = time();
        header('Location: index.php?page=hello');
        exit;
    }
    $error = 'please enter your name';
}

// ---- Logout: compute duration, then destroy session ----
$duration = '';
$loggedName = '';
if ($page === 'logout') {
    if (!isset($_SESSION['start'])) {
        header('Location: index.php');
        exit;
    }
    $loggedName = $_SESSION['name'];
    $secs = time() - $_SESSION['start'];
    $duration = sprintf('%dh %dm %ds', intdiv($secs, 3600), intdiv($secs % 3600, 60), $secs % 60);
    session_unset();
    session_destroy();
}

// ---- Guard hello page ----
if ($page === 'hello' && !isset($_SESSION['name'])) {
    header('Location: index.php');
    exit;
}
$e = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>session demo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #3b3b3b;
    --paper: #fcfcff;
    --ink: #161616;
    --dark: #1a1a1a;
    --muted: #8c8c8c;
    --line: #2a2a2a;
    --radius: 14px;
  }
  * { box-sizing: border-box; }
  html { scroll-behavior: smooth; }
  body {
    margin: 0; background: var(--bg); color: var(--ink);
    font-family: "Inter Tight", "Helvetica Neue", Arial, sans-serif;
    letter-spacing: -0.02em;
  }
  .label { font-size: 11px; line-height: 1.2; padding: 22px 24px 14px; color: #fff; }
  .sheet { max-width: 620px; margin: 0 auto 60px; border-radius: 22px; overflow: hidden; }
  .light { background: var(--paper); padding: 0 80px 56px; }
  .darkband { background: var(--dark); color: #fff; padding: 20px 80px 64px; }

  nav { display: flex; align-items: center; gap: 22px; padding: 22px 0 0; font-size: 11px; }
  nav a { color: var(--ink); text-decoration: none; }
  nav a:focus-visible, button:focus-visible, input:focus-visible { outline: 2px solid var(--ink); outline-offset: 3px; }
  nav .right { margin-left: auto; }

  .hero { padding: 86px 0 80px; min-height: 270px; }
  .hero h1 {
    margin: 0 0 12px; font-size: 40px; font-weight: 600;
    letter-spacing: -0.05em; line-height: 1;
  }
  .row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .pill {
    border: 0; background: var(--ink); color: #fff; font: inherit; font-size: 11px;
    letter-spacing: -0.01em; padding: 0 18px; height: 28px; border-radius: 999px;
    cursor: pointer; text-decoration: none; display: inline-flex; align-items: center;
  }
  .pill.big { height: 30px; padding: 0 20px; }
  .tag {
    border: 1px solid var(--ink); border-radius: 8px; font-size: 9.5px;
    text-transform: uppercase; padding: 6px 9px; transform: rotate(-3deg);
    letter-spacing: 0;
  }
  .name-input {
    font: inherit; font-size: 11px; height: 28px; width: 190px; padding: 0 14px;
    border: 1px solid var(--ink); border-radius: 999px; background: transparent; color: var(--ink);
  }
  .err { font-size: 11px; margin: 10px 0 0; color: #b00020; }

  h2.section { font-weight: 400; font-size: 22px; letter-spacing: -0.04em; color: var(--muted); margin: 0 0 14px; }
  .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
  .card {
    border: 1px solid var(--line); border-radius: var(--radius); background: var(--paper);
    aspect-ratio: 1 / 1.15; padding: 14px 14px 12px;
    display: flex; flex-direction: column; justify-content: space-between;
  }
  .card h3 {
    margin: 0; font-size: 25px; font-weight: 600; line-height: .9; letter-spacing: -0.06em;
  }
  .card p { margin: 0; font-size: 8.5px; line-height: 1.15; }
  .dark .card { background: transparent; border-color: #9a9a9a; color: #fff; }
  .cta { display: flex; justify-content: center; margin-top: 24px; }

  .big-thanks { font-size: 52px; font-weight: 600; letter-spacing: -0.06em; line-height: .92; margin: 0 0 14px; }

  @media (max-width: 640px) {
    .light { padding: 0 22px 40px; } .darkband { padding: 20px 22px 48px; }
    .sheet { margin: 0 10px 40px; }
    .grid { grid-template-columns: 1fr; }
    .card { aspect-ratio: auto; min-height: 150px; gap: 40px; }
    .hero h1 { font-size: 34px; } .big-thanks { font-size: 42px; }
  }
</style>
</head>
<body>

<div class="label">php session demo<br>@hello-logout</div>

<main class="sheet">

<?php if ($page === 'hello'): ?>
  <div class="light">
    <nav>
      <a href="#session">session</a>
      <a href="index.php?page=logout">log out</a>
      <span class="right" id="start">started <?= date('H:i:s', $_SESSION['start']) ?></span>
    </nav>
    <section class="hero">
      <h1>Hello, <?= $e($_SESSION['name']) ?></h1>
      <div class="row">
        <a class="pill" href="index.php?page=logout">log out</a>
        <span class="tag">session active</span>
      </div>
    </section>

    <section id="session">
      <h2 class="section">(session)</h2>
      <div class="grid">
        <div class="card"><h3>your name</h3><p>taken from the form and saved in the session<br><?= $e($_SESSION['name']) ?></p></div>
        <div class="card"><h3>start time</h3><p>saved when you pressed submit<br><?= date('d M Y, H:i:s', $_SESSION['start']) ?></p></div>
        <div class="card"><h3>time here</h3><p>updates every second<br><span id="elapsed">0s</span></p></div>
      </div>
      <div class="cta"><a class="pill big" href="index.php?page=logout">log out</a></div>
    </section>
  </div>
  <script>
    const start = <?= (int)$_SESSION['start'] ?> * 1000;
    const el = document.getElementById('elapsed');
    function tick() {
      const s = Math.max(0, Math.floor((Date.now() - start) / 1000));
      el.textContent = Math.floor(s / 3600) + 'h ' + Math.floor(s % 3600 / 60) + 'm ' + (s % 60) + 's';
    }
    tick(); setInterval(tick, 1000);
  </script>

<?php elseif ($page === 'logout'): ?>
  <div class="light">
    <nav>
      <a href="index.php">log in again</a>
      <span class="right">logged out</span>
    </nav>
    <section class="hero">
      <h1 class="big-thanks">Thank you,<br><?= $e($loggedName) ?></h1>
      <div class="row">
        <a class="pill" href="index.php">log in again</a>
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

<?php else: ?>
  <div class="light">
    <nav>
      <a href="#how">how it works</a>
      <span class="right">login</span>
    </nav>
    <section class="hero">
      <h1>Who's there?</h1>
      <form method="post" action="index.php" class="row">
        <input class="name-input" type="text" name="name" placeholder="your name" aria-label="your name" required>
        <button class="pill" type="submit">say hello</button>
        <span class="tag">php sessions</span>
      </form>
      <?php if ($error): ?><p class="err"><?= $e($error) ?></p><?php endif; ?>
    </section>

    <section id="how">
      <h2 class="section">(how it works)</h2>
      <div class="grid">
        <div class="card"><h3>enter name</h3><p>your name is sent with the form and saved in the session</p></div>
        <div class="card"><h3>hello page</h3><p>greets you by name and shows the start time at the top right</p></div>
        <div class="card"><h3>log out</h3><p>ends the session and shows how long you used the app</p></div>
      </div>
    </section>
  </div>
<?php endif; ?>

</main>
</body>
</html>