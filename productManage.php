<?php
class Product {
   private $product_name;
   private $product_desc;
   private $product_image;
   private $product_price;
   private $conn;
   
   function __construct()
    {
        $this->conn=mysqli_connect("127.0.0.1","root","","ecom6");
    }
    function newProduct($pro_name,$pro_desc,$pro_image,$pro_price,$pro_qty,$catOption)
    {
      $query=mysqli_query($this->conn,"INSERT INTO products(pro_name,pro_desc,pro_price,qty,cat_id,pro_image)
                                                 VALUES('$pro_name','$pro_desc','$pro_price','$pro_qty','$catOption','$pro_image')");
    }
    function showProduct()
    {
     $query=mysqli_query($this->conn,"SELECT products.*,category.cat_name FROM products
                                      INNER JOIN category
                                      ON category.cat_id = products.cat_id");
     $array = array();
     $i=0;
                  while($row=mysqli_fetch_assoc($query))
                  {  
                    $array[$i]['pro_id']=$row['pro_id'];
                    $array[$i]['pro_name']=$row['pro_name'];
                    $array[$i]['pro_desc']=$row['pro_desc'];
                    $array[$i]['pro_img']=$row['pro_image'];
                    $array[$i]['pro_price']=$row['pro_price'];
                    $array[$i]['pro_qty']=$row['qty'];
                    $array[$i]['category']=$row['cat_name'];
                    $i++;
                  }
                  return $array;
    }
    function showCategory(){
        $query=mysqli_query($this->conn,"SELECT * FROM category");
        $array = array();
        $i=0;
        while($row=mysqli_fetch_assoc($query)){
            $array[$i]['cat_id']=$row['cat_id'];
            $array[$i]['cat_name']=$row['cat_name'];
            $i++;
        }
         return $array;
    }
    function deleteProduct($id)
    {
        $query=mysqli_query($this->conn,"DELETE FROM products WHERE pro_id={$id}");
    }
      function productInformationById($id)
   {
     $query=mysqli_query($this->conn,"SELECT products.*,category.cat_name,category.cat_id FROM products
                                      INNER JOIN category
                                      ON category.cat_id = products.cat_id
                                      WHERE pro_id=$id");
     $row=mysqli_fetch_assoc($query);
     $proInfo = array();
     $proInfo['name']=$row['pro_name'];
     $proInfo['desc']=$row['pro_desc'];
     $proInfo['img']=$row['pro_image'];
     $proInfo['price']=$row['pro_price'];
     $proInfo['qty']=$row['qty'];
     $proInfo['category']=$row['cat_name'];
     $proInfo['categoryID']=$row['cat_id'];
     return $proInfo;
   }
    
    function editProduct($id,$pro_name,$pro_desc,$pro_price,$pro_qty,$catOption,$image){
        
        $query=mysqli_query($this->conn,"UPDATE products SET
                                                 pro_name='$pro_name',
                                                 pro_desc='$pro_desc',
                                                 pro_price='$pro_price',
                                                 cat_id='$catOption',
                                                 qty='$pro_qty',
                                                 pro_image='$image'
                                                 WHERE pro_id='$id'");
        
    }
}
include("includes/header.php");
$action = isset($_GET['action']) ? $_GET['action'] : 'dash';
$product=new Product();
if(isset($_POST['submit']))
{
    $pro_name=$_POST['p_name'];
    $pro_desc=$_POST['p_desc'];
    $pro_price=$_POST['p_price'];
    $pro_qty=$_POST['p_qty'];
    $pro_image = $_FILES['pro_img']['name'];
    $tmp   = $_FILES['pro_img']['tmp_name'];
    $path  = 'images/';
    move_uploaded_file($tmp,$path.$pro_image);
    
        $catOption=$_POST['cateforyID'];
        $product->newProduct($pro_name,$pro_desc,$pro_image,$pro_price,$pro_qty,$catOption);

    
  
}
if ($action == 'dash'){
    ?>
     <div class="row">
                           <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Manage Products</div>
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center title-2">Product Info</h3>
                                        </div>
                                        <hr>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="control-label mb-1">Product Name</label>
                                                <input  type="text" class="form-control" name="p_name">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Description</label>
                                                <input  type="text" class="form-control" name="p_desc">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Price</label>
                                                <input  type="text" class="form-control" name="p_price">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Quantity</label>
                                                <input  type="text" class="form-control" name="p_qty">
                                            </div>
                                             <div class="form-group">
                                                <label class="control-label mb-1">Image</label>
                                                <input  type="file" class="form-control"  name="pro_img">
                                            </div>
                                            <select name="cateforyID" class="custom-select mb-3">
                                              <?php
                                                    foreach($product->showCategory() as $row){
                                                  ?>
                                                    <option value="<?php echo$row['cat_id'];?>"><?php echo $row['cat_name'];?></option>
                                                   <?php }  ?> 
                                            </select>
                                            <div>
                                                <button id="payment-button" type="submit" name="submit" class="btn btn-lg btn-info btn-block">
                                                   Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row m-t-30">
                            <div class="col-md-12">
                                <!-- DATA TABLE-->
                                <div class="table-responsive m-b-40">
                                    <table class="table table-borderless table-data3">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>description</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Image</th>
                                                <th>Category</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                           foreach($product->showProduct() as $row){
                                                  echo"<tr><td>{$row['pro_id']}</td><td>{$row['pro_name']}</td><td>{$row['pro_desc']}</td>";
                                                  echo"<td>{$row['pro_price']}</td><td>{$row['pro_qty']}</td><td><img src='images/{$row['pro_img']}' width='100px' height='100px'></td><td>{$row['category']}</td>";
                                                  echo"<td><a href='productManage.php?action=edit&&id=".$row['pro_id']."'class='btn btn-primary'>Edit</a></td>";
                                                  echo "<td><a href='productManage.php?action=delete&&id=".$row['pro_id']."' class='btn btn-danger'>Delete</a></td></tr>";
                                           }
                                           ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END DATA TABLE-->
                            </div>
                        </div>
<?php

}
elseif($action == 'delete'){
    $product->deleteProduct($_GET['id']);
    ?>
     <script type="text/javascript">
     window.location.href = 'productManage.php';
     </script>
    <?php
    
}elseif($action == 'edit'){
    $arr=$product->productInformationById($_GET['id']);
    if(isset($_POST['save'])){
        
        $pro_name=$_POST['p_name'];
        $pro_desc=$_POST['p_desc'];
        $pro_price=$_POST['p_price'];
        $pro_qty=$_POST['p_qty'];
        $catOption=$_POST['category'];
        if(($_FILES['pro_image']['name']))
                          {
                            $image = $_FILES['pro_image']['name'];
                            $tmp   = $_FILES['pro_image']['tmp_name'];
                            $path  = 'images/';
                            move_uploaded_file($tmp,$path.$image);
                            
                            $product->editProduct($_GET['id'],$pro_name,$pro_desc,$pro_price,$pro_qty,$catOption,$image);
                          }
                          else{
                            $product->editProduct($_GET['id'],$pro_name,$pro_desc,$pro_price,$pro_qty,$catOption,$arr['img']);
                          }
        ?>
     <script type="text/javascript">
      window.location.href = 'productManage.php';
     </script>
    <?php
    }
    ?>
    <div class="row">
                           <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Manage Products</div>
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center title-2">Product Info</h3>
                                        </div>
                                        <hr>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="control-label mb-1">Product Name</label>
                                                <input  type="text" class="form-control" name="p_name" value="<?php echo$arr['name'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Description</label>
                                                <input  type="text" class="form-control" name="p_desc" value="<?php echo$arr['desc'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Price</label>
                                                <input  type="text" class="form-control" name="p_price" value="<?php echo$arr['price'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Quantity</label>
                                                <input  type="text" class="form-control" name="p_qty" value="<?php echo$arr['qty'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Image</label>
                                                <input  type="file" class="form-control"  name="pro_image">
                                            </div>
                                            <select name="category" class="custom-select mb-3">
                                              <?php
                                             
                                                    echo "<option value='{$arr['categoryID']}'>{$arr['category']}</option>";
                                                    foreach($product->showCategory() as $row){
                                                        if($row['cat_name']==$arr['category'])continue;
                                                    ?>
                                                    <option value="<?php echo$row['cat_id'];?>"><?php echo $row['cat_name'];?></option>
                                                   <?php }  ?> 
                                            </select>
                                            <div>
                                                <button id="payment-button" type="submit" name="save" class="btn btn-lg btn-info btn-block">
                                                   Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
}
include("includes/footer.html") ;
?>