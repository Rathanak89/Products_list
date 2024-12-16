<?php
$msg3 = '';

if (isset($_POST['btnSave'])) {
  $barcode = $_POST['barcode'];
  $name_product = $_POST['name_product'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $qty = $_POST['qty'];
  $status = $_POST['status'];

  $filetmp = $_FILES["image"]["tmp_name"];
  $filename = $_FILES["image"]["name"];
  $filetype = $_FILES["image"]["type"];
  $filesize = $_FILES["image"]["size"];

  $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  $allowed_extensions = array("jpeg", "jpg", "png");

  if (empty($filename)) {
    $msg3 = "Please select an image.";
  } elseif ($filesize > 2097152) {
    $msg3 = "File size must not exceed 2 MB.";
  } elseif (!in_array($file_ext, $allowed_extensions)) {
    $msg3 = "Invalid file type. Allowed: jpeg, jpg, png.";
  } else {
    if (!empty($barcode) && !empty($name_product) && !empty($category) &&
        !empty($price) && !empty($qty) && !empty($status)) {
      $upload_path = "assets/images/myimages/" . $filename;
      move_uploaded_file($filetmp, $upload_path);

      $sql = "INSERT INTO products (image, barcode, name_product, category_id, price, qty, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssdis", $upload_path, $barcode, $name_product, $category, $price, $qty, $status);

      if ($stmt->execute()) {
        // $msg3 = "Data saved successfully!";
        echo '
        <script>
            window.location.replace("index.php?p=product_list");
        </script>
        ';
      } else {
        $msg3 = "Data insertion unsuccessful.";
      }
    } else {
      $msg3 = "All fields are required!";
    }
  }
}
?>

<div class="main-panel col-lg-12">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Insert Products</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?p=product_list">Product List</a></li>
          <li class="breadcrumb-item active" aria-current="page">Product List</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">List Product</h4>
            <form action="#" method="POST" enctype="multipart/form-data" class="settings-form">
              <div class="row">
                <div class="col-md-6">
                  <label for="barcode" class="form-label">Barcode *</label>
                  <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Input Barcode" required>

                  <label for="category" class="form-label">Category *</label>
                  <select name="category" id="category" class="form-control" required>
                    <?php
                      $sql = mysqli_query($conn, "SELECT * FROM categorys");
                      while ($row = mysqli_fetch_array($sql)) {
                        echo "<option value='".$row['category_id']."'>".$row['category_name']."</option>";
                      }
                    ?>
                  </select>

                  <label for="qty" class="form-label">Qty *</label>
                  <input type="number" id="qty" name="qty" class="form-control" placeholder="Enter Quantity" required>

                  <label for="imageUpload" class="form-label">Product Image</label>
                  <input type="file" id="imageUpload" class="form-control" accept="image/*" name="image" required onchange="previewImage(event)">
                  
                </div>

                <div class="col-md-6">
                  <label for="name" class="form-label">Name Product *</label>
                  <input type="text" id="name_product" name="name_product" class="form-control" placeholder="Enter Name" required>

                  <label for="price" class="form-label">Price *</label>
                  <input type="number" name="price" id="price" class="form-control" placeholder="Enter Sale Price" required>

                  <label for="status" class="form-label">Status</label>
                  <select id="status" class="form-control" name="status" required>
                    <option value="Active">Active</option>
                    <option value="No-active">No-Active</option>
                  </select>
                  <img id="imagePreview" class="image-preview" alt="Preview" style="display: none; width: 100px; margin: 10px;">
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

<?php if (!empty($msg3)) { ?>
  <div class="alert alert-info mt-3"><?= $msg3 ?></div>
<?php } ?>

<script>
  function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
      output.style.display = "block"; // Display the image preview
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>


