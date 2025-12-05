<?php $page_title = "Home"; include 'includes/header.php'; ?>

  <section class="hero">
    <img src="young-black-race-man-with-blueprint-stading-near-glass-building.jpg" alt="Hero Background" class="hero-bg">
    <div class="hero-overlay"> </div>
    <div class="container hero-content">
      <h1>RAYLAND BUILDERS</h1>
    <p class="hero-tagline">Building dreams, one room at a time <br> Expert kitchen, bathroom, plumbing and heating solutions crafted with quality, care, and attention to every detail.</p>
      <div class="hero-buttons">
        <a href="contact.php" class="btn">Contact Us</a>
        <a href="services.php" class="btn btn-outline">Our Services</a>
      </div>
    </div>
  </section>

  <div class="container services-overlay">
    <div class="services-grid">
      <div class="service-card">
        <h3>Kitchen Installation</h3>
        <p>We design and install modern kitchens with top-quality cabinetry, countertops, and appliances. Our expert team ensures precise fitting, functional layouts, and stylish finishes that make your kitchen both practical and stunning. Every project is tailored to the client’s specific style and functionality requirements, ensuring a seamless cooking and entertaining experience.</p>
      </div>
      <div class="service-card">
        <h3>Bathroom Installation</h3>
        <p>Our comprehensive bathroom installation services include plumbing, tiling, fixture installation, and finishing touches. We focus on creating spaces that are both elegant and functional, with attention to detail that guarantees a luxurious and durable finish. From modern designs to classic layouts, we transform bathrooms into comfortable, safe, and visually appealing retreats.</p>
      </div>
      <div class="service-card">
        <h3>Plumbing & Heating</h3>
        <p>We provide expert plumbing and heating services including repairs, installations, and system upgrades. Our team ensures that all work meets safety standards, offering reliable solutions for radiators, boilers, pipework, and more. We aim for efficiency, longevity, and optimal performance, keeping homes warm and plumbing systems running smoothly year-round.</p>
      </div>
    </div>
  </div>

  <section class="section" style="background: white; padding-top: 50px;">
    <div class="container text-center">
      <h2 style="margin-bottom: 40px;">What Our Clients Say</h2>
      <div class="services-grid">
        <?php
        require_once 'includes/db.php';
        
        // Check if table exists to prevent errors before you create the SQL table
        $checkTable = $conn->query("SHOW TABLES LIKE 'testimonials'");
        
        if($checkTable && $checkTable->num_rows > 0) {
            $sql = "SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
            ?>
              <div class="service-card" style="text-align: left;">
                <div style="color: var(--primary); margin-bottom: 10px;">
                  <?php for($i=0; $i<$row['rating']; $i++) { echo "<i class='fas fa-star'></i>"; } ?>
                </div>
                <p style="font-style: italic; color: #555;">"<?php echo htmlspecialchars($row['review_text']); ?>"</p>
                <h4 style="margin-top: 15px; font-size: 1rem;">— <?php echo htmlspecialchars($row['client_name']); ?></h4>
              </div>
            <?php
              }
            } else {
              echo "<p style='grid-column: 1/-1;'>No reviews yet. Be the first!</p>";
            }
        } else {
            echo "<p style='grid-column: 1/-1;'>Testimonials coming soon.</p>";
        }
        ?>
      </div>
    </div>
  </section>

<?php include 'includes/footer.php'; ?>