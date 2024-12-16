<style>
  .product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 5px;
  }
</style>
<div class="main-panel col-lg-12">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Products List</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Product List</a></li>
          <li class="breadcrumb-item active" aria-current="page">Product List</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">List Product</h4>
            <form method="GET">
            <div class="d-flex mb-3">
            <input type="hidden" name="p" value="product_list">
            <select class="col-lg-3" style="margin-right: 15px;" id="category" name="category" >
                  <option value="">---Category---</option>
                  <?php
                  $sql = mysqli_query($conn, "select * from categorys");
                  while($row = mysqli_fetch_array($sql)){
                    echo "<option value=".$row['category_id'].">".$row['category_name']."</option>";
                  }
                  ?>
            </select>
              <input style="margin-right: 15px;" id="txt_search" name="txt_search" type="text" class="col-lg-3" placeholder="Search products">
              <button style="margin-right: 15px; text-align:center;" name="btnSearch" type="submit" class="btn btn-primary">Search</button>
              <a class="btn btn-primary" href="index.php?p=insert_product">Insert Products</a> 
            </div>
            </form>
            <div class="table-responsive">
              <table class="table table-bordered table-contextual">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Images</th>
                    <th>Barcode</th>
                    <th>Name Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>View/Edit/Delete</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  
                  #search Data
                  if (isset($_GET['btnSearch'])) {
                    $key_category = $_GET['category'];
                    $key_inputdata = $_GET['txt_search'];
                    $sql_select = "SELECT p.id, p.image, p.barcode, p.name_product, c.category_name, p.price, p.qty, p.status
                            FROM products p
                            INNER JOIN categorys c ON c.category_id = p.category_id ";
                            if($key_inputdata=="" && $key_category==""){
                              $sql = $sql_select;
                            }
                            if($key_category){
                              $sql = $sql_select. "WHERE c.category_id = '$key_category'";
            
                            } 
                            if($key_inputdata){
                              $sql = $sql_select. " WHERE 
                            p.name_product LIKE '%$key_inputdata%' OR
                            p.barcode LIKE '%$key_inputdata%' OR
                            c.category_name LIKE '%$key_inputdata%'";
                            }
                    $result = mysqli_query($conn, $sql);
                    $num_row = $result->num_rows;    
                  }else{
                    $sql = "SELECT p.id, p.image, p.barcode, p.name_product, c.category_name, p.price, p.qty, p.status
                            FROM products p
                            INNER JOIN categorys c ON c.category_id = p.category_id";
                    $result = mysqli_query($conn, $sql);
                  }
                    while ($row = mysqli_fetch_array($result)) {    
                  ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><img src="<?= $row['image'] ?>" class="product-image"></td>
                    <td><?= $row['barcode'] ?></td>
                    <td><?= $row['name_product'] ?></td>
                    <td><?= $row['category_name'] ?></td>
                    <td>$<?= $row['price'] ?></td>
                    <td><?= $row['qty'] ?></td>
                    <td class="cell">
												  <?php
												  $status = $row['status'];
												  $badge_class = '';

												  if ($status == 'Active') {
													$badge_class = 'badge bg-success'; 
												  } elseif ($status == 'No-Active') {
													$badge_class = 'badge bg-danger';
												  } 
												  echo '<span class="' . $badge_class . '">' . $status . '</span>';
												  ?>
												</td>
                    <td>
                    <a class="btn btn-info" href="index.php?p=view_product&pro_id=<?= $row['id'] ?>">View</a> 
                    <a class="btn btn-primary" href="index.php?p=edit_product&pro_id=<?= $row['id'] ?>">Edit</a>
                    <a href="pages/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete it?')" class="btn btn-danger">Delete</a>
                  </td>
                  </tr>
                  <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
