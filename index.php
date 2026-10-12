<?php
/* The home page. Students log in; students on OSA's imported list can
   activate their account.
   Staff use the hidden shortcut (Ctrl+Shift+S, or type gwcstaff). If you're
   already signed in, you go straight to your dashboard. */
require_once __DIR__ . "/auth/session.php";   // safe session settings + security headers

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case "Admin":        header("Location: admin/dashboard.php");       exit();
        case "OSA":          header("Location: admin/dashboard.php");       exit();
        case "OSA Staff":    header("Location: osa_staff/dashboard.php");   exit();
        /* Marshals don't have a dashboard: their home is the scanner app. */
        case "Guard":        header("Location: scanner/spck_scanner.html"); exit();
        case "Student":      header("Location: student/dashboard.php");     exit();
        default:             unset($_SESSION['role']);                      break;
    }
}
$loggedOut = isset($_GET['logout']);   // show a "you signed out" message
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Shield: Student Violation Tracking System | Golden West Colleges</title>
<!-- Fonts come from our own server, so the page works without internet. -->
<link rel="stylesheet" href="assets/vendor/fonts/css/fonts.css">
<link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
<link rel="stylesheet" href="assets/css/landing.css?v=<?php echo @filemtime(__DIR__."/assets/css/landing.css"); ?>">
<?php /* The home page's own styles (a PHP comment, so it isn't sent to the
         browser). */ ?>
  <!-- The app's look (light and dark). Loaded last. -->
  <link rel="stylesheet" href="assets/css/vts-apple.css?v=<?php echo @filemtime(__DIR__."/assets/css/vts-apple.css"); ?>">
</head>
<body class="qs qs-landing">
<?php include __DIR__ . '/includes/bfcache_guard.php'; ?>

<?php /* ===================================================================
         THE TOP BAR: the logo, a search, Sign in and account activation. See-through over
         the photo, and solid once you scroll down.
         =================================================================== */ ?>
<nav class="lp-nav" id="lpNav">
  <a class="lp-brand" href="index.php" aria-label="QR Shield home">
    <img src="assets/images/logo-sm.jpg" alt="">
    <span class="lp-brand-t">
      <span class="lp-brand-n">QR Shield</span>
      <span class="lp-brand-s">Golden West Colleges</span>
    </span>
  </a>

  <div class="lp-nav-links">
    <a href="#why">Why it exists</a>
    <a href="#how">How it works</a>
    <a href="#proof">Tested</a>
  </div>

  <div class="lp-nav-right">
    <?php /* The search finds your record as you type, using the same public search
             as the sign-in page (auth/student_lookup_search.php): names only, never
             School IDs. Picking a name takes you to the sign-in page, the only page
             that signs anyone in. */ ?>
    <form class="lp-search" id="lpSearchForm" action="account/student_search.php" method="GET" role="search">
      <i class="fas fa-magnifying-glass ic" aria-hidden="true"></i>
      <label for="lpSearchInput" class="sr-only">Log in to your student account</label>
      <input type="text" id="lpSearchInput" name="q" placeholder="Search your name to log in"
             autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false"
             role="combobox" aria-expanded="false" aria-autocomplete="list"
             aria-controls="lpResults" aria-describedby="lpSearchHelp">
      <span id="lpSearchHelp" class="sr-only">Type at least three letters of your name. Matching students appear below.</span>
      <div class="lp-results" id="lpResults" role="listbox" aria-label="Matching students"></div>
      <?php /* A hidden Search button, so the box can be sent without pressing Enter
               (screen readers, voice control). The whole box is hidden on phones,
               where the big search in the middle of the page takes over. */ ?>
      <button type="submit" class="sr-only">Log in</button>
    </form>
    <a class="lp-login" href="account/student_search.php">Log in</a>
    <a class="lp-pill" href="account/register.php">Activate your account</a>
    <button class="lp-burger" id="lpBurger" type="button"
            aria-label="Menu" aria-expanded="false" aria-controls="lpDrawer">
      <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
  </div>
</nav>

<div class="lp-drawer" id="lpDrawer">
  <a href="#why">Why it exists</a>
  <a href="#how">How it works</a>
  <a href="#proof">Tested</a>
  <a href="account/student_search.php">Log In</a>
  <a href="account/register.php">Activate your account</a>
</div>

<?php /* ONE photograph behind the band and the headline (the user, 2026-10-05):
         clear at the top, fading into blur behind the text, instead of a
         sharp copy over a separate blurred copy that never lined up. The
         blurred layer is the same image, same box, same position, masked in
         from below (vts-apple.css, "LANDING"). */ ?>
<div class="lp-stage">
<img class="lp-stage-img" src="assets/images/school.jpg" alt="Golden West Colleges campus">
<div class="lp-stage-blur" aria-hidden="true"></div>
<header class="lp-hero"></header>

<section class="lp-head">
  <h1 >Every violation, recorded once.</h1>
  <p >
    QR Shield replaces the handwritten violation slip with one scan of the student&rsquo;s
    QR code, so each record is saved once, with a proof photo, and cannot be lost or
    written twice. Students can check their own standing any time.
  </p>
  <div class="lp-cta">
    <a class="lp-btn lp-btn-primary" href="account/student_search.php">
      <i class="fas fa-right-to-bracket"></i> Log In
    </a>
    <a class="lp-btn lp-btn-ghost" href="account/register.php">Activate your account</a>
  </div>
  <div class="lp-sdg">
    <i class="fas fa-graduation-cap" aria-hidden="true"></i>
    Supporting UN SDG&nbsp;4: Quality Education
  </div>
</section>
</div>

<?php /* MARQUEE: what the system does, scrolling past. The list is written
         twice so the loop has no seam; the copy is hidden from screen readers,
         which read the first list once. Stops for reduced motion and on hover
         (landing.css, "MARQUEE"). */
$lpFeatures = [
    ['fa-qrcode',          'One scan at the gate'],
    ['fa-camera',          'Proof photo on every record'],
    ['fa-plug-circle-xmark','Works offline'],
    ['fa-rotate',          'Syncs itself when the signal returns'],
    ['fa-envelope',        'Gmail notice to the student'],
    ['fa-clone',           'Same scan within 120 seconds is ignored'],
    ['fa-user-shield',     'Five roles, each with its own access'],
    ['fa-file-excel',      'Official Sheet and Excel export'],
    ['fa-clipboard-list',  'Audit log of every change'],
    ['fa-cloud-arrow-up',  'Automatic backups'],
];
?>
<div class="lp-marquee" role="region" aria-label="What QR Shield does">
  <div class="lp-marquee-track">
    <?php for ($copy = 0; $copy < 2; $copy++): ?>
      <ul<?php echo $copy ? ' aria-hidden="true"' : ''; ?>>
        <?php foreach ($lpFeatures as [$ic, $txt]): ?>
          <li><i class="fas <?php echo $ic; ?>" aria-hidden="true"></i> <?php echo $txt; ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endfor; ?>
  </div>
</div>

<?php /* =================================================================
         WHY IT EXISTS: the four findings from the study. Placed before "how it
         works", because you want to know what it's for first.
         ================================================================= */ ?>
<section class="lp-sec" id="why">
  <div class="lp-wrap">
    <span class="lp-eyebrow">Why it exists</span>
    <h2 >The paper it replaced could not be trusted to still be there.</h2>
    <p class="lp-lead">
      Before this, the Office of Student Affairs tracked discipline by hand. Interviews
      with 60 students, 10 marshals, 2 guards and the OSA found four problems with that,
      and each one shaped a part of QR Shield.
    </p>
    <?php /* The four findings of Chapter 4 (Existing System Analysis). */ ?>
    <ol class="lp-reasons">
      <li>
        <h3>Slips, then spreadsheets</h3>
        <p>Guards and marshals wrote each violation on a slip or log book with a ballpen.
           The OSA encoded it into an Excel file later and found records with Ctrl+F.</p>
      </li>
      <li>
        <h3>Too many hands</h3>
        <p>Slips, log books and confiscated IDs went from the marshal to a team leader
           before reaching the OSA: one more manual step at the end of every shift.</p>
      </li>
      <li>
        <h3>No standard, few staff</h3>
        <p>Forms were often not filled in completely, because there was no standard for
           them, and since 2024 the OSA has run on student assistants who graduate and leave.</p>
      </li>
      <li>
        <h3>Students could not check</h3>
        <p>31 of the 60 student respondents (52%) had no way to track their own violation
           records, or relied on memory.</p>
      </li>
    </ol>
  </div>
</section>

<?php /* =================================================================
         HOW IT'S BUILT: three modes, because the gate doesn't always have
         Wi-Fi, and a violation that can't be recorded without it wouldn't get
         recorded at all.
         ================================================================= */ ?>
<section class="lp-sec navy" id="how">
  <div class="lp-wrap">
    <span class="lp-eyebrow">Why it works the way it does</span>
    <h2 >A campus gate does not always have a signal.</h2>
    <p class="lp-lead">
      That single fact shaped the whole design. A marshal cannot wait for a connection with
      a student standing in front of them, so the scanner works whether or not there is one
      and settles up afterwards.
    </p>
    <div class="lp-modes">
      <article class="lp-mode">
        <span class="ic"><i class="fas fa-plug-circle-xmark"></i></span>
        <h3>Offline</h3>
        <p>Guards and marshals scan with no network at all. The shift is saved as one
           encrypted .vtsl file, carried by USB and OTG to the Head Marshal, and imported
           by the OSA.</p>
      </article>
      <article class="lp-mode">
        <span class="ic"><i class="fas fa-bolt"></i></span>
        <h3>Online</h3>
        <p>Each scan reaches the server as it is made, and the student gets a notice in
           QR Shield and by Gmail straight away.</p>
      </article>
      <article class="lp-mode">
        <span class="ic"><i class="fas fa-rotate"></i></span>
        <h3>Hybrid</h3>
        <p>Signal comes and goes. Anything recorded while it was gone syncs by itself the
           moment it returns: nobody has to remember to do it.</p>
      </article>
    </div>
    <p class="lp-lead" style="margin-top:30px;">
      In every mode, a violation needs a proof photo, and the server ignores the same scan
      sent more than once within 120 seconds.
    </p>
  </div>
</section>

<?php /* =================================================================
         HOW IT WAS TESTED: the evaluation results.
         ================================================================= */ ?>
<section class="lp-sec paper" id="proof">
  <div class="lp-wrap">
    <span class="lp-eyebrow">Tested, not assumed</span>
    <h2 >Seventy-three people used it before anyone called it finished.</h2>
    <p class="lp-lead">
      QR Shield was built with Agile (planning, requirements, design, development,
      testing, feedback and improvement, repeated) and rated against <b>ISO/IEC&nbsp;25010</b>
      on eight characteristics: functional suitability, performance efficiency,
      compatibility, usability, reliability, security, maintainability and portability.
    </p>
    <div class="lp-stats">
      <div class="lp-stat"><div class="v">60</div><div class="k">students, 15 from each of four departments</div></div>
      <div class="lp-stat"><div class="v">10</div><div class="k">marshals</div></div>
      <div class="lp-stat"><div class="v">2</div><div class="k">guards</div></div>
      <div class="lp-stat"><div class="v">1</div><div class="k">OSA personnel</div></div>
    </div>
    <div class="lp-stats" style="margin-top:18px;">
      <div class="lp-stat"><div class="v">3.61</div><div class="k">overall weighted mean: Strongly Agree</div></div>
      <div class="lp-stat"><div class="v">8&thinsp;/&thinsp;8</div><div class="k">ISO/IEC 25010 characteristics rated Strongly Agree</div></div>
      <div class="lp-stat"><div class="v">15&thinsp;/&thinsp;15</div><div class="k">features passed system testing</div></div>
      <div class="lp-stat"><div class="v">4.00</div><div class="k">portability, the highest rating</div></div>
    </div>
    <p class="lp-lead" style="margin-top:30px;">
      Built by eight BSIT III-D students of Golden West Colleges, Inc. for System Analysis
      and Design, A.Y. 2026&ndash;2027, with PHP, MySQL, HTML, CSS, JavaScript and JSON.
    </p>
  </div>
</section>

<section class="lp-sec">
  <div class="lp-wrap u-center">
    <h2  style="max-width:none;margin-inline:auto;">Check what is on your record.</h2>
    <p class="lp-lead" style="margin-inline:auto;">
      Sign in with your School ID and password, or get a one-time code by Gmail.
      Students on OSA's imported list can activate an account.
    </p>
    <div class="lp-cta">
      <a class="lp-btn lp-btn-primary" href="account/student_search.php">
        <i class="fas fa-right-to-bracket"></i> Log In
      </a>
      <a class="lp-btn lp-btn-ghost" href="account/register.php">Activate your account</a>
    </div>
  </div>
</section>

<footer class="lp-foot">
  <div class="row">
    <a href="account/student_search.php">Log In</a>
    <a href="account/register.php">Activate your account</a>
    <a href="#why">Why it exists</a>
    <a href="#how">How it works</a>
  </div>
  <div>&copy; <?php echo date('Y'); ?> Golden West Colleges, Inc. &middot;
    QR Shield: Student Violation Tracking System</div>
</footer>

<button id="lpTop" type="button" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>

<?php if ($loggedOut): ?>
<div id="logoutToast" style="position:fixed;top:88px;left:50%;transform:translateX(-50%) translateY(-16px);z-index:9000;
     display:flex;align-items:center;gap:11px;background:#fff;color:#0f172a;border-radius:12px;
     padding:13px 20px;box-shadow:0 16px 40px rgba(8,18,40,.35);opacity:0;transition:opacity .35s ease, transform .35s ease;">
  <span style="width:30px;height:30px;border-radius:50%;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:.95rem;"><i class="fas fa-check"></i></span>
  <span style="font-weight:700;font-size:.92rem;">Logged out successfully</span>
</div>
<?php endif; ?>

<script>
/* Keyboard shortcut (desktop only; phones use the pad on the seal). It
   only opens the sign-in page. The password is still checked on the
   server. */
document.addEventListener('keydown', function(e){
  /* Carries the key itself (?q=gwcstaff): a bare ?staff=1 no longer opens
     the staff pop-up (vts_staff_gate_open()). */
  if (e.ctrlKey && e.shiftKey && (e.key === 'S' || e.key === 's')){ e.preventDefault(); window.location.href = 'account/student_search.php?q=gwcstaff'; }
});
/* "You signed out" message: slides in, goes away by itself, and cleans the
   address bar. */
(function(){
  var t = document.getElementById('logoutToast');
  if (!t) return;
  requestAnimationFrame(function(){ t.style.opacity='1'; t.style.transform='translateX(-50%) translateY(0)'; });
  if (history.replaceState) history.replaceState(null,'',window.location.pathname);
  setTimeout(function(){ t.style.opacity='0'; t.style.transform='translateX(-50%) translateY(-16px)'; }, 3200);
  setTimeout(function(){ if (t.parentNode) t.parentNode.removeChild(t); }, 3700);
})();
</script>

<script src="assets/js/landing.js?v=<?php echo @filemtime(__DIR__."/assets/js/landing.js"); ?>" defer></script>
<script src="assets/js/vts-touch.js?v=<?php echo @filemtime(__DIR__."/assets/js/vts-touch.js"); ?>" defer></script>
<script src="assets/js/vts-offline.js?v=<?php echo @filemtime(__DIR__."/assets/js/vts-offline.js"); ?>" defer></script>
<script src="assets/js/vts-tooltip.js?v=<?php echo @filemtime(__DIR__."/assets/js/vts-tooltip.js"); ?>" defer></script>
<script>
/* "gwcstaff" / "gwcscanner" typed anywhere on this page (not in a box; the
   search box sends what it holds to the sign-in page, which reads the same
   codes). The only staff ways in, with Ctrl+Shift+S above (2026-10-03). */
(function(){
  var buf = '';
  document.addEventListener('keydown', function(e){
    var tag = (e.target.tagName || '').toLowerCase();
    if (tag === 'input' || tag === 'textarea' || e.ctrlKey || e.altKey || e.metaKey) return;
    if (!e.key || e.key.length !== 1) return;
    buf = (buf + e.key.toLowerCase()).slice(-10);
    if (buf.slice(-8) === 'gwcstaff') { buf = ''; location.href = 'account/student_search.php?q=gwcstaff'; }
    else if (buf === 'gwcscanner') { buf = ''; location.href = 'scanner/spck_scanner.html'; }
  });
})();
</script>
</body>
</html>
