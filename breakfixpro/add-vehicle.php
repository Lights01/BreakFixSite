<?php
session_start();
include 'includes/db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch vehicles for the logged-in user
try {
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Error fetching vehicles: " . $e->getMessage() . "');</script>";
    $vehicles = [];
}

// Handle vehicle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make'])) {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $registration_number = $_POST['registration'];
    $color = $_POST['color'];
    $mileage = $_POST['mileage'];

    try {
        $stmt = $pdo->prepare("INSERT INTO vehicles (user_id, make, model, year, registration_number, color, mileage) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $make, $model, $year, $registration_number, $color, $mileage]);
        echo "<script>alert('Vehicle added successfully!');</script>";

        // Redirect to refresh the page and show the new vehicle
        header("Location: add-vehicle.php");
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Handle vehicle deletion
if (isset($_GET['delete_id'])) {
    $vehicle_id = $_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ? AND user_id = ?");
        $stmt->execute([$vehicle_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Vehicle deleted successfully!');</script>";
        } else {
            echo "<script>alert('Failed to delete vehicle.');</script>";
        }

        // Redirect to refresh the page
        header("Location: add-vehicle.php");
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error deleting vehicle: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Vehicle | BreakFixPro</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link href="auth.css" rel="stylesheet" type="text/css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <header id="main-header">
    <div class="container">
      <div class="logo">
        <h1><a href="index.html">BreakFixPro</a></h1>
      </div>
      <nav>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="add-vehicle.php" class="active">Register Vehicle</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="auth-container">
    <div class="auth-box">
      <h2>Register Your Vehicle</h2>
      <form class="auth-form" id="vehicle-form" method="POST">
        <div class="form-group">
          <label for="make">Vehicle Make</label>
          <select id="make" name="make" required>
            <option value="">Select Make</option>
            <option value="toyota">Toyota</option>
            <option value="honda">Honda</option>
            <option value="tata">Tata</option>
            <option value="mahindra">Mahindra</option>
            <option value="hyundai">Hyundai</option>
          </select>
        </div>
        <div class="form-group">
          <label for="model">Vehicle Model</label>
          <select id="model" name="model" required>
            <option value="">Select Model</option>
          </select>
        </div>
        <div class="form-group">
          <label for="year">Manufacturing Year</label>
          <select id="year" name="year" required>
            <option value="">Select Year</option>
            <?php
              $currentYear = date('Y');
              for ($year = $currentYear; $year >= $currentYear - 15; $year--) {
                  echo "<option value='$year'>$year</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="registration">Registration Number</label>
          <input type="text" id="registration" name="registration" required>
        </div>
        <div class="form-group">
          <label for="color">Vehicle Color</label>
          <input type="text" id="color" name="color" required>
        </div>
        <div class="form-group">
          <label for="mileage">Current Mileage</label>
          <input type="number" id="mileage" name="mileage" required>
        </div>
        <button type="submit" class="auth-button">Register Vehicle</button>
      </form>
    </div>

    <div class="vehicle-data">
      <h2>Your Registered Vehicles</h2>
      <div class="vehicle-cards" id="vehicle-cards">
        <?php if (empty($vehicles)): ?>
          <p>No vehicles registered yet.</p>
        <?php else: ?>
          <?php foreach ($vehicles as $vehicle): ?>
            <div class="vehicle-card" data-id="<?= $vehicle['id'] ?>">
              <div class="vehicle-icon">
                <i class="fas fa-car"></i>
              </div>
              <h3><?= htmlspecialchars($vehicle['make']) ?> <?= htmlspecialchars($vehicle['model']) ?></h3>
              <ul>
                <li><strong>Registration:</strong> <?= htmlspecialchars($vehicle['registration_number']) ?></li>
                <li><strong>Year:</strong> <?= htmlspecialchars($vehicle['year']) ?></li>
                <li><strong>Color:</strong> <?= htmlspecialchars($vehicle['color']) ?></li>
                <li><strong>Mileage:</strong> <?= htmlspecialchars($vehicle['mileage']) ?> km</li>
              </ul>
              <div class="vehicle-actions">
                <a href="?delete_id=<?= $vehicle['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this vehicle?');">
                  <i class="fas fa-trash"></i> Delete
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Dynamic Model Selection
      const makeSelect = document.getElementById('make');
      const modelSelect = document.getElementById('model');

      const vehicleModels = {
        toyota: ['Corolla', 'Camry', 'RAV4', 'Fortuner'],
        honda: ['Civic', 'Accord', 'City', 'CR-V'],
        tata: ['Nexon', 'Harrier', 'Safari', 'Tiago'],
        mahindra: ['Scorpio', 'XUV700', 'Thar', 'Bolero'],
        hyundai: ['i10', 'i20', 'Creta', 'Venue']
      };

      makeSelect.addEventListener('change', () => {
        const make = makeSelect.value;
        modelSelect.innerHTML = '<option value="">Select Model</option>';

        if (make && vehicleModels[make]) {
          vehicleModels[make].forEach(model => {
            const option = document.createElement('option');
            option.value = model.toLowerCase();
            option.textContent = model;
            modelSelect.appendChild(option);
          });
        }
      });
    });
  </script>
</body>
</html>