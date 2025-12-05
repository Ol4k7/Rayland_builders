<?php
  // ==========================
  // 1. PHP FORM LOGIC (Top)
  // ==========================
  $msg = '';
  $msgClass = '';

  // Check if form was submitted
  if(filter_has_var(INPUT_POST, 'submit')){
    // Get Form Data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Check Required Fields
    if(!empty($email) && !empty($name) && !empty($message)){
      // Check Email Validity
      if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        $msg = 'Please use a valid email';
        $msgClass = 'alert-danger';
      } else {
        // Recipient Email
        $toEmail = 'rashymil@gmail.com';
        $subject = 'Contact Request From ' . $name;
        $body = "<h2>Contact Request</h2>
                 <h4>Name</h4><p>$name</p>
                 <h4>Email</h4><p>$email</p>
                 <h4>Message</h4><p>$message</p>";

        // Email Headers
        $headers = "MIME-Version: 1.0" ."\r\n";
        $headers .="Content-Type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $name . "<".$email.">". "\r\n";

        // Attempt to send
        if(mail($toEmail, $subject, $body, $headers)){
          $msg = 'Your email has been sent';
          $msgClass = 'alert-success';
          // Clear fields after success so form is empty
          $name = ''; $email = ''; $message = '';
          // Clear $_POST so the inputs below don't refill
          $_POST = array(); 
        } else {
          $msg = 'Your email was not sent (Server Error)';
          $msgClass = 'alert-danger';
        }
      }
    } else {
      $msg = 'Please fill in all fields';
      $msgClass = 'alert-danger';
    }
  }
?>

<?php 
  // ==========================
  // 2. PAGE HEADER
  // ==========================
  $page_title = "Contact Us"; 
  include 'includes/header.php'; 
?>

<main>
  <div style="text-align: center; margin: 3rem 0 2rem;">
    <h1>Contact Us</h1>
    <p class="lead">Get in touch with Rayland Builders Services.</p>
  </div>

  <!-- <div class="container" style="max-width: 800px; margin-bottom: 40px;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-left: 5px solid var(--primary);">
      <h3 style="color: var(--dark); margin-bottom: 10px;">💰 Quick Project Estimator</h3>
      <p style="margin-bottom: 20px; font-size: 0.9rem; color: #666;">Note: These are rough estimates only. Please contact us for a precise quote.</p>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div>
          <label style="font-weight: bold; display: block; margin-bottom: 5px;">Service Required</label>
          <select id="calc-service" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; background: #f9fafb;">
            <option value="0">Select Service...</option>
            <option value="4000">Bathroom Renovation (Full)</option>
            <option value="6000">Kitchen Installation</option>
            <option value="250">Plumbing Repair</option>
            <option value="1200">Tiling (Per Room)</option>
            <option value="500">Painting & Decorating</option>
          </select>
        </div>
        <div>
          <label style="font-weight: bold; display: block; margin-bottom: 5px;">Room Size / Complexity</label>
          <select id="calc-size" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; background: #f9fafb;">
            <option value="1">Standard / Small</option>
            <option value="1.4">Medium (+40%)</option>
            <option value="2">Large / Luxury (+100%)</option>
          </select>
        </div>
      </div>

      <button type="button" onclick="calculateQuote()" class="btn btn-outline" style="width: 100%;">Calculate Estimate</button>
      
      <div id="calc-result" style="display: none; margin-top: 20px; padding: 15px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; text-align: center;">
        <p style="margin-bottom: 5px; color: #166534;">Estimated Price Range:</p>
        <h2 style="color: var(--dark); margin: 0;">£<span id="price-min">0</span> - £<span id="price-max">0</span></h2>
        <small style="color: #666;">Labor estimate. Materials may vary.</small>
      </div>
    </div>
  </div> -->

  <section class="contact-section">
    <div class="contact-card">
      <ul class="contact-list">
        <li>
          <i class="fas fa-envelope"></i>
          <div>
            <strong>Email:</strong><br>
            <a href="mailto:rashymil@gmail.com">rashymil@gmail.com</a>
          </div>
        </li>
        <li>
          <i class="fas fa-phone"></i>
          <div>
            <strong>Phone:</strong><br>
            <a href="tel:+447496597414">+44 74 9659 7414</a>
          </div>
        </li>
        <li>
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <strong>Address:</strong><br>
            <span>3 Pipchin Road, Chelmsford, CM1 4XT</span>
          </div>
        </li>
      </ul>
    </div>

    <form id="contactForm" class="contact-form" method="POST" action="">
      
      <?php if($msg != ''): ?>
        <div style="padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; 
             background: <?php echo ($msgClass == 'alert-success') ? '#d4edda' : '#f8d7da'; ?>; 
             color: <?php echo ($msgClass == 'alert-success') ? '#155724' : '#721c24'; ?>;">
          <?php echo $msg; ?>
        </div>
      <?php endif; ?>

      <input type="text" name="name" id="name" placeholder="Your Name" required 
             value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" />
      
      <input type="email" name="email" id="email" placeholder="Your Email" required 
             value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
      
      <textarea name="message" id="message" placeholder="Your Message" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
      
      <button type="submit" name="submit" class="btn gold">Send Message</button>
    </form>
  </section>
</main>

<script>
function calculateQuote() {
  const servicePrice = parseInt(document.getElementById('calc-service').value);
  const multiplier = parseFloat(document.getElementById('calc-size').value);
  const resultBox = document.getElementById('calc-result');
  const minSpan = document.getElementById('price-min');
  const maxSpan = document.getElementById('price-max');

  if (servicePrice === 0) {
    alert("Please select a service first.");
    return;
  }

  // Calculate Base
  const baseEstimate = servicePrice * multiplier;
  
  // Create a Range (-10% to +20%)
  const minPrice = Math.floor(baseEstimate * 0.9);
  const maxPrice = Math.floor(baseEstimate * 1.2);

  // Animate Numbers
  minSpan.textContent = minPrice.toLocaleString();
  maxSpan.textContent = maxPrice.toLocaleString();
  
  // Show Result with animation
  resultBox.style.display = 'block';
  resultBox.style.animation = 'fadeIn 0.5s ease';
}
</script>

<?php include 'includes/footer.php'; ?>