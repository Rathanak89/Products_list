<?php
$pro_id = $_GET['pro_id'];

$sql = "SELECT 
            p.id, 
            p.image, 
            p.barcode, 
            p.name_product, 
            c.category_id, 
            p.price, 
            p.qty, 
            p.status 
        FROM products p
        INNER JOIN categorys c ON c.category_id = p.category_id
        WHERE p.id = ?"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pro_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_array();

if (!$product) {
    echo "<div class='alert alert-danger'>Product not found.</div>";
    exit;
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $barcode = $_POST['barcode'];
    $name_product = $_POST['name_product'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $status = $_POST['status'];

    $imagePath = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "assets/images/myimages/";
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            echo "<div class='alert alert-danger'>Error uploading image.</div>";
            exit;
        }
    }
    $sql = "UPDATE products SET 
                barcode = ?, 
                name_product = ?, 
                category_id = ?, 
                price = ?, 
                qty = ?, 
                status = ?, 
                image = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiisss", $barcode, $name_product, $category, $price, $qty, $status, $imagePath, $id);

    if ($stmt->execute()) {
        echo '
        <script>
            window.location.replace("index.php?p=product_list");
        </script>
        ';
    } else {
        echo "<div class='alert alert-danger'>Error updating product: " . $stmt->error . "</div>";
    }
}
?>

<div class="main-panel col-lg-12">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title"> Edit Product </h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?p=product_list">Product List</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Edit Product Details</h4>
            <form action="#" method="POST" enctype="multipart/form-data" class="settings-form">
              <input type="hidden" name="id" value="<?= $product['id'] ?>">

              <div class="row">
                <div class="col-md-6">
                  <label for="barcode" class="form-label">Barcode *</label>
                  <input type="text" name="barcode" id="barcode" class="form-control" value="<?= $product['barcode'] ?>" placeholder="Input Barcode" required>

                  <label for="category" class="form-label">Category *</label>
                  <select style="color: white;" name="category" id="category" class="form-control" required>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM categorys");
                    while ($row = mysqli_fetch_array($sql)) {
                      $selected = ($row['category_id'] == $product['category_id']) ? 'selected' : '';
                      echo "<option $selected value='".$row['category_id']."'>".$row['category_name']."</option>";
                    }
                    ?>
                  </select>

                  <label for="qty" class="form-label">Qty *</label>
                  <input type="number" id="qty" name="qty" class="form-control" value="<?= $product['qty'] ?>" placeholder="Enter Quantity" required>

                  <label for="imageUpload" class="form-label">Product Image</label>
                  <input type="file" id="imageUpload" class="form-control" accept="image/*" name="image" onchange="previewImage(event)">
                  
                </div>

                <div class="col-md-6">
                  <label for="name_product" class="form-label">Name Product *</label>
                  <input type="text" id="name_product" name="name_product" class="form-control" value="<?= $product['name_product'] ?>" placeholder="Enter Name" required>

                  <label for="price" class="form-label">Price *</label>
                  <input type="number" name="price" id="price" class="form-control" value="<?= $product['price'] ?>" placeholder="Enter Sale Price" required>

                  <label for="status" class="form-label">Status</label>
                  <select style="color: white;" id="status" class="form-control" name="status" required>
                    <option value="Active" <?= ($product['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                    <option value="No-Active" <?= ($product['status'] == 'No-Active') ? 'selected' : '' ?>>No-Active</option>
                  </select>
                  <?php if ($product['image']) { ?>
                    <img id="imagePreview" src="<?= $product['image'] ?>" alt="Current Image" style="width: 100px; margin-top: 10px;">
                  <?php } ?>
                </div>
              </div>

              <div class="modal-footer">
                <a type="button" href="index.php?p=product_list" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="btnSave" class="btn btn-success">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
