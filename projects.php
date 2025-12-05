<?php $page_title = "Our Projects"; ?>
<?php include 'includes/header.php'; ?>
<?php require_once 'includes/db.php'; ?>

<main>
  <section class="section">
    <div class="container">
      <h1 class="text-center" style="margin-bottom: 10px;">Our Projects</h1>
      <p class="lead text-center" style="margin-bottom: 40px;">Take a look at some of our recent transformations.</p>
      
      <div class="projects-grid">
        <?php
          // 1. Get projects from Database (Newest first)
          $sql = "SELECT * FROM projects ORDER BY created_at DESC";
          $result = $conn->query($sql);

          if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              // We store data in "data-" attributes so JS can grab them easily for the modal
              ?>
              <div class="service-card project-card" 
                   data-title="<?php echo htmlspecialchars($row['title']); ?>"
                   data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                   data-before="uploads/projects/<?php echo $row['before_image']; ?>"
                   data-after="uploads/projects/<?php echo $row['after_image']; ?>"
                   style="cursor: pointer;">
                
                <div style="height: 250px; overflow: hidden; border-radius: 8px; margin-bottom: 15px;">
                  <img src="uploads/projects/<?php echo $row['after_image']; ?>" 
                       alt="Project After" 
                       style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                </div>
                
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p style="font-size: 0.9rem; color: var(--primary);">Tap to see Before & After <i class="fas fa-arrow-right"></i></p>
              </div>
              <?php
            }
          } else {
            echo "<p class='text-center' style='width:100%;'>No projects uploaded yet.</p>";
          }
        ?>
      </div>
    </div>
  </section>
</main>

<div id="projectModal" class="modal">
  <div class="modal-content">
    <span class="close-modal">&times;</span>
    <h2 id="modalTitle">Project Title</h2>
    
    <div class="comparison-container">
      <div class="comp-box">
        <span class="badge">BEFORE</span>
        <img id="modalBefore" src="" alt="Before">
      </div>
      <div class="comp-box">
        <span class="badge badge-gold">AFTER</span>
        <img id="modalAfter" src="" alt="After">
      </div>
    </div>
    
    <p id="modalDesc" style="margin-top: 20px; color: #555;"></p>
  </div>
</div>

<style>
/* Dark Overlay Background */
.modal {
  display: none; 
  position: fixed; 
  z-index: 2000; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgba(0,0,0,0.8);
  backdrop-filter: blur(5px);
}

/* White Content Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto; 
  padding: 20px;
  border-radius: 12px;
  width: 90%; 
  max-width: 1000px;
  position: relative;
  animation: slideDown 0.3s ease-out;
}

/* Animation */
@keyframes slideDown {
  from {transform: translateY(-50px); opacity: 0;}
  to {transform: translateY(0); opacity: 1;}
}

/* Close (X) Button */
.close-modal {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}
.close-modal:hover { color: #000; }

/* Grid Layout for Images */
.comparison-container {
  display: flex;
  gap: 10px;
  margin-top: 15px;
}
.comp-box {
  flex: 1;
  position: relative;
}
.comp-box img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #ddd;
}

/* Labels (Before/After) */
.badge {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 0.8rem;
  font-weight: bold;
}
.badge-gold { background: var(--primary); color: #000; }

/* Mobile Stack (Vertical) */
@media (max-width: 768px) {
  .comparison-container { flex-direction: column; }
  .comp-box img { height: 200px; }
  .modal-content { margin: 10% auto; width: 95%; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById("projectModal");
  const closeBtn = document.querySelector(".close-modal");
  const cards = document.querySelectorAll(".project-card");

  // Elements to update inside modal
  const mTitle = document.getElementById("modalTitle");
  const mDesc = document.getElementById("modalDesc");
  const mBefore = document.getElementById("modalBefore");
  const mAfter = document.getElementById("modalAfter");

  // Open Modal on Card Click
  cards.forEach(card => {
    card.addEventListener("click", () => {
      // Get data from HTML attributes
      const title = card.getAttribute("data-title");
      const desc = card.getAttribute("data-desc");
      const before = card.getAttribute("data-before");
      const after = card.getAttribute("data-after");

      // Set content inside the modal
      mTitle.textContent = title;
      mDesc.textContent = desc;
      mBefore.src = before;
      mAfter.src = after;

      // Show the modal
      modal.style.display = "block";
    });
  });

  // Close Modal on X click
  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Close Modal on Outside click
  window.addEventListener("click", (e) => {
    if (e.target == modal) {
      modal.style.display = "none";
    }
  });
});
</script>

<?php include 'includes/footer.php'; ?>