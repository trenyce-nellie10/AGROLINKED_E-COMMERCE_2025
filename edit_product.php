<?php
// edit_product.php
require_once "core.php";
require_once "controllers/product_controller.php";
require_once "controllers/category_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 2) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$product = get_product_ctr($id);

if (!$product) {
    echo "Product not found";
    exit();
}

$categories = get_all_categories_ctr();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Product - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .form-card { background:#fff;padding:24px;border-radius:12px;max-width:700px;margin:auto;box-shadow:0 4px 20px rgba(0,0,0,.08); }
    .preview-img { width:120px;height:120px;object-fit:cover;border-radius:8px;margin-top:8px;border:1px solid #ddd; }
  </style>
</head>
<body class="page">

<?php include 'nav.php'; ?>

<main class="center-wrap" style="padding:36px 20px;">
  <h1>Edit Product</h1>

  <div class="form-card">
    <form id="editProductForm" enctype="multipart/form-data">
      <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

      <label>Product Name</label>
      <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

      <label>Category</label>
      <select name="category_id" required>
        <?php foreach ($categories as $c): ?>
          <option value="<?php echo $c['category_id']; ?>"
            <?php if ($c['category_id'] == $product['category_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($c['category_name']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Description</label>
      <textarea name="product_description" rows="4" required><?php echo htmlspecialchars($product['product_description']); ?></textarea>

      <label>Price (GHS)</label>
      <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

      <label>Quantity</label>
      <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required>

      <label>Current Image</label><br>
      <img src="<?php echo htmlspecialchars($product['main_image'] ?? 'assets/images/default.png'); ?>" class="preview-img">

      <label>Change Image (optional)</label>
      <input type="file" name="main_image" accept="image/*">

      <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>

    <p id="msg" style="margin-top:12px;"></p>
  </div>
</main>

<script>
document.getElementById("editProductForm").addEventListener("submit", async e => {
  e.preventDefault();

  let fd = new FormData(e.target);

  const res = await fetch("actions/edit_product_action.php", {
    method: "POST",
    body: fd
  });

  const data = await res.json();
  const msg = document.getElementById("msg");

  if (data.status === "success") {
    msg.style.color = "green";
    msg.textContent = "Product updated!";
  } else {
    msg.style.color = "red";
    msg.textContent = data.message;
  }
});
</script>

</body>
</html>
