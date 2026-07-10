<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$message = '';

// Helper function for auto-generating image
function autoGenerateImage($itemName) {
    // Basic SEO friendly keywords from item name
    $keywords = strtolower(str_replace([' ', '(', ')', '/'], [',', '', '', '_'], $itemName));
    $url = "https://loremflickr.com/600/600/" . urlencode($keywords) . "/all?random=" . time();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if($httpCode == 200 && $data) {
        $filename = 'menu_auto_' . time() . '_' . rand(1000, 9999) . '.jpg';
        if (!is_dir('../assets/images/menu')) {
            mkdir('../assets/images/menu', 0777, true);
        }
        file_put_contents('../assets/images/menu/' . $filename, $data);
        return 'menu/' . $filename;
    }
    return false;
}

// Handle Delete
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $pdo->prepare("DELETE FROM menu WHERE id = ?")->execute([$id]);
        $message = "<div class='alert alert-success'>Menu item deleted successfully.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to delete item.</div>";
    }
}

// Handle Add Item
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $cat_id = $_POST['category_id'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $desc = $_POST['description'];
    
    $image = 'premium_coffee_1783449279091.png'; // Fallback default
    
    // Check if auto-generate is requested
    if(isset($_POST['auto_generate_image'])) {
        $autoImage = autoGenerateImage($name);
        if($autoImage) {
            $image = $autoImage;
        } else {
            $message = "<div class='alert alert-warning'>Failed to auto-generate image. Using default.</div>";
        }
    } 
    // Manual upload overrides auto-generate if provided
    elseif(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'menu_' . time() . '.' . $ext;
        if (!is_dir('../assets/images/menu')) {
            mkdir('../assets/images/menu', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/menu/' . $filename);
        $image = 'menu/' . $filename;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO menu (name, category_id, price, type, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $cat_id, $price, $type, $desc, $image]);
        if(empty($message)) $message = "<div class='alert alert-success'>Item added successfully!</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to add item: " . $e->getMessage() . "</div>";
    }
}

// Handle Edit Item
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_item'])) {
    $id = $_POST['item_id'];
    $name = $_POST['name'];
    $cat_id = $_POST['category_id'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $desc = $_POST['description'];
    
    $imageUpdateQuery = "";
    $params = [$name, $cat_id, $price, $type, $desc];
    
    // Check if auto-generate is requested
    if(isset($_POST['auto_generate_image'])) {
        $autoImage = autoGenerateImage($name);
        if($autoImage) {
            $imageUpdateQuery = ", image = ?";
            $params[] = $autoImage;
        } else {
            $message = "<div class='alert alert-warning'>Failed to auto-generate image. Image was not changed.</div>";
        }
    } 
    // Manual upload
    elseif(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'menu_' . time() . '.' . $ext;
        if (!is_dir('../assets/images/menu')) {
            mkdir('../assets/images/menu', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/menu/' . $filename);
        $imageUpdateQuery = ", image = ?";
        $params[] = 'menu/' . $filename;
    }
    
    $params[] = $id; // For WHERE clause
    
    try {
        $stmt = $pdo->prepare("UPDATE menu SET name = ?, category_id = ?, price = ?, type = ?, description = ? $imageUpdateQuery WHERE id = ?");
        $stmt->execute($params);
        if(empty($message)) $message = "<div class='alert alert-success'>Item updated successfully!</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to update item: " . $e->getMessage() . "</div>";
    }
}

// Fetch Categories for dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Fetch Menu Items
$items = $pdo->query("SELECT m.*, c.name as category_name FROM menu m JOIN categories c ON m.category_id = c.id ORDER BY m.id DESC")->fetchAll();
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Menu Management</h2>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#addItemModal"><i class="fas fa-plus me-2"></i>Add New Item</button>
    </div>

    <?= $message ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($items)): ?>
                    <tr><td colspan="6" class="text-center py-4 theme-text-muted">No items found.</td></tr>
                    <?php else: foreach($items as $item): ?>
                    <tr>
                        <td>
                            <img src="../assets/images/<?= htmlspecialchars($item['image']) ?>" alt="" width="50" height="50" class="rounded object-fit-cover shadow-sm">
                        </td>
                        <td class="fw-bold">
                            <?= htmlspecialchars($item['name']) ?><br>
                            <small class="theme-text-muted fw-normal"><?= htmlspecialchars(substr($item['description'], 0, 50)) ?>...</small>
                        </td>
                        <td><?= htmlspecialchars($item['category_name']) ?></td>
                        <td class="text-golden fw-bold">₹<?= $item['price'] ?></td>
                        <td><span class="badge <?= $item['type'] == 'Veg' ? 'bg-success' : 'bg-danger' ?>"><?= $item['type'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning me-1" 
                                onclick='openEditModal(<?= json_encode($item) ?>)'>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="?delete_id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white" style="border: 1px solid var(--golden-accent);">
      <form action="" method="POST" enctype="multipart/form-data">
          <div class="modal-header border-bottom-0">
            <h5 class="modal-title theme-text">Add New Menu Item</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label theme-text-muted">Item Name</label>
                  <input type="text" name="name" class="form-control bg-transparent text-white theme-border" required>
              </div>
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Category</label>
                      <select name="category_id" class="form-select bg-transparent text-white theme-border" required>
                          <?php foreach($categories as $cat): ?>
                              <option class="text-dark" value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Price (₹)</label>
                      <input type="number" step="0.01" name="price" class="form-control bg-transparent text-white theme-border" required>
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Type</label>
                      <select name="type" class="form-select bg-transparent text-white theme-border">
                          <option class="text-dark" value="Veg">Veg</option>
                          <option class="text-dark" value="Non Veg">Non Veg</option>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Image Upload</label>
                      <input type="file" name="image" class="form-control bg-transparent text-white theme-border" accept="image/*">
                      <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="auto_generate_image" id="autoGenerateAdd">
                        <label class="form-check-label text-golden small" for="autoGenerateAdd">
                          Auto-Generate SEO Image
                        </label>
                      </div>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label theme-text-muted">Description</label>
                  <textarea name="description" rows="3" class="form-control bg-transparent text-white theme-border" required></textarea>
              </div>
          </div>
          <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="add_item" class="btn btn-premium">Save Item</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white" style="border: 1px solid var(--golden-accent);">
      <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="item_id" id="edit_item_id">
          <div class="modal-header border-bottom-0">
            <h5 class="modal-title theme-text">Edit Menu Item</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label theme-text-muted">Item Name</label>
                  <input type="text" name="name" id="edit_name" class="form-control bg-transparent text-white theme-border" required>
              </div>
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Category</label>
                      <select name="category_id" id="edit_category_id" class="form-select bg-transparent text-white theme-border" required>
                          <?php foreach($categories as $cat): ?>
                              <option class="text-dark" value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Price (₹)</label>
                      <input type="number" step="0.01" name="price" id="edit_price" class="form-control bg-transparent text-white theme-border" required>
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">Type</label>
                      <select name="type" id="edit_type" class="form-select bg-transparent text-white theme-border">
                          <option class="text-dark" value="Veg">Veg</option>
                          <option class="text-dark" value="Non Veg">Non Veg</option>
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label theme-text-muted">New Image (Optional)</label>
                      <input type="file" name="image" class="form-control bg-transparent text-white theme-border" accept="image/*">
                      <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="auto_generate_image" id="autoGenerateEdit">
                        <label class="form-check-label text-golden small" for="autoGenerateEdit">
                          Auto-Generate New SEO Image
                        </label>
                      </div>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label theme-text-muted">Description</label>
                  <textarea name="description" id="edit_description" rows="3" class="form-control bg-transparent text-white theme-border" required></textarea>
              </div>
          </div>
          <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="edit_item" class="btn btn-warning text-dark fw-bold">Update Item</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
function openEditModal(item) {
    document.getElementById('edit_item_id').value = item.id;
    document.getElementById('edit_name').value = item.name;
    document.getElementById('edit_category_id').value = item.category_id;
    document.getElementById('edit_price').value = item.price;
    document.getElementById('edit_type').value = item.type;
    document.getElementById('edit_description').value = item.description;
    
    var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
    editModal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
