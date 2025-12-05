<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title . ' — Rayland Builders' : 'Rayland Builders'; ?></title>
  
  <link rel="stylesheet" href="assets/css/style.css">
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <header class="header">
    <div class="container header-flex">
      <div class="logo">
        <h2>RAYLAND <span>BUILDERS</span></h2>
      </div>
      
      <?php 
        // This little PHP script highlights the current page in the menu
        $page = basename($_SERVER['PHP_SELF']); 
      ?>

      <nav class="nav" id="navMenu">
        <a href="index.php" class="<?php echo ($page == 'index.php') ? 'active' : ''; ?>">Home</a>
        <a href="about.php" class="<?php echo ($page == 'about.php') ? 'active' : ''; ?>">About</a>
        <a href="services.php" class="<?php echo ($page == 'services.php') ? 'active' : ''; ?>">Services</a>
        <a href="projects.php" class="<?php echo ($page == 'projects.php') ? 'active' : ''; ?>">Projects</a>
        <a href="contact.php" class="<?php echo ($page == 'contact.php') ? 'active' : ''; ?>">Contact</a>
      </nav>
      <button class="menu-toggle" id="menuToggle">☰</button>
    </div>
  </header>