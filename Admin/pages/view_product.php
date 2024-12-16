<style>
  .form-control {
   background-color: black;
   color:rgb(0, 0, 0);
   font-size: 18px;
  }
</style>

<?php
$pro_id = $_GET['pro_id'];

$sql = "SELECT 
            p.id, 
            p.image, 
            p.barcode, 
            p.name_product, 
            c.category_id, 
            c.category_name,
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
<div class="main-panel col-lg-12">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">View Product</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?p=product_list">Product List</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Product</li>
        </ol>
      </nav>
    </div>

    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">View Product Details</h4>
            <form action="#" method="POST" enctype="multipart/form-data" class="settings-form">
              <input type="hidden" name="id" value="<?= $product['id'] ?>">

              <div class="row">
                <div class="col-md-6">
                  <label for="barcode" class="form-label">Barcode</label>
                  <input type="text" name="barcode" id="barcode" class="form-control" value="<?= $product['barcode'] ?>" placeholder="Input Barcode" disabled>

                  <label for="category" class="form-label">Category</label>
                  <label for="category" class="form-label">Category</label>
                  <input name="category" id="category" class="form-control" value="<?= $product['category_name'] ?>" disabled>


                  <label for="qty" class="form-label">Qty</label>
                  <input type="number" id="qty" name="qty" class="form-control" value="<?= $product['qty'] ?>" placeholder="Enter Quantity" disabled>

                  <label for="imageUpload" class="form-label">Product Image</label>
                  <input type="text" id="imageUpload" class="form-control" accept="image/*" name="image" value="<?= $product['image']?>" disabled>
                
                </div>

                <div class="col-md-6">
                  <label for="name_product" class="form-label">Name Product</label>
                  <input type="text" id="name_product" name="name_product" class="form-control" value="<?= $product['name_product'] ?>" placeholder="Enter Name" disabled>

                  <label for="price" class="form-label">Price</label>
                  <input type="number" name="price" id="price" class="form-control" value="<?= $product['price'] ?>" disabled>

                  <label for="status" class="form-label">Status</label>
                  <input type="text" name="status" id="status" class="form-control" value="<?= $product['status'] ?>" disabled>
                  <?php if ($product['image']) { ?>
                    <img id="imagePreview" src="<?= $product['image'] ?>" alt="Current Image" style="width: 100px; margin-top: 10px;">
                  <?php } ?>
                </div>
              </div>

              <div class="modal-footer">
                <a type="button" href="index.php?p=product_list" class="btn btn-secondary">Close</a>
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
