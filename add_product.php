<?php
// add_product.php
require_once "core.php";
require_once "controllers/vendor_controller.php";
require_once "controllers/product_controller.php";
require_once "controllers/category_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2) {
    header("Location: login.php");
    exit();
}

$categories = get_all_categories_ctr();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Product - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .form-card { background:#fff;padding:24px;border-radius:12px;max-width:700px;margin:auto;box-shadow:0 4px 20px rgba(0,0,0,.08); }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block;margin-bottom:4px;font-weight:600; }
    .preview-img { width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;margin-top:8px; }
  </style>
</head>
<body class="page">

<?php include 'nav.php'; ?>

<main class="center-wrap" style="padding:36px 20px;">
  <h1>Add New Product</h1>
  <p class="muted">Fill the form below to list a new product on AgroLinked.</p>

  <div class="form-card">
    <form id="addProductForm" enctype="multipart/form-data">

      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="product_name" required>
      </div>

      <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
          <option value="">Select category</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['category_id']; ?>">
              <?php echo htmlspecialchars($c['category_name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="product_description" rows="4" required></textarea>
      </div>

      <div class="form-group">
        <label>Price (GHS)</label>
        <input type="number" name="price" step="0.01" required>
      </div>

      <div class="form-group">
        <label>Quantity</label>
        <input type="number" name="quantity" min="1" required>
      </div>

      <div class="form-group">
        <label>Main Image</label>
        <input type="file" name="main_image" accept="image/*" id="imageInput">
        <img id="preview" class="preview-img" style="display:none;">
      </div>

      <button class="btn btn-primary" type="submit">Add Product</button>
    </form>

    <p id="msg" style="margin-top:12px;color:#d9534f;"></p>
  </div>
</main>

<script>
imageInput.onchange = evt => {
  const f = evt.target.files[0];
  if (f) {
    preview.src = URL.createObjectURL(f);
    preview.style.display = 'block';
  }
};

document.getElementById("addProductForm").addEventListener("submit", async e => {
  e.preventDefault();
  let fd = new FormData(e.target);

  const res = await fetch("actions/add_product_action.php", {
    method: "POST",
    body: fd
  });

  const data = await res.json();
  const msg = document.getElementById("msg");

  if (data.status === "success") {
    msg.style.color = "green";
    msg.textContent = "Product added successfully!";
    e.target.reset();
    preview.style.display = "none";
  } else {
    msg.style.color = "red";
    msg.textContent = data.message;
  }
});
</script>

</body>
</html>
