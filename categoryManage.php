<?php
class Category{
    private $category_name;
    private $category_desc;
    private $category_image;
    private $conn;
    
    function __construct()
    {
        $this->conn=mysqli_connect("127.0.0.1","root","","ecom6");
    }
    public function setCategory($name,$desc,$img)
    {
        
    }
    function newCategory($catName,$catDesc,$image)
    {
     $query=mysqli_query($this->conn,"INSERT INTO category (cat_name,cat_desc,cat_image)
                                        VALUES('$catName','$catDesc','$image')");
    }
    function showCategory()
    {
     $query=mysqli_query($this->conn,"SELECT * FROM category");
     $array = array();
     $i=0;
                  while($row=mysqli_fetch_assoc($query))
                  {  
                    $array[$i]['cat_id']=$row['cat_id'];
                    $array[$i]['cat_name']=$row['cat_name'];
                    $array[$i]['cat_desc']=$row['cat_desc'];
                    $array[$i]['cat_img']=$row['cat_image'];
                    $i++;
                  }
                  return $array;
    }
   function deleteCategory($id)
   {
    $query=mysqli_query($this->conn,"DELETE FROM category WHERE cat_id={$id}");
   }
   function editCategory($id,$name,$desc,$img)
   {
    $query=mysqli_query($this->conn,"UPDATE category
                              SET cat_name='$name',
                              cat_desc='$desc',
                              cat_image='$img'
                              WHERE cat_id='$id'");
   }
   function categoryInformationById($id)
   {
     $query=mysqli_query($this->conn,"SELECT * FROM category WHERE cat_id=$id");
     $row=mysqli_fetch_assoc($query);
     $catInfo = array();
     $catInfo['name']=$row['cat_name'];
     $catInfo['desc']=$row['cat_desc'];
     $catInfo['img']=$row['cat_image'];
     return $catInfo;
   }
}
include("includes/header.php");
$action = isset($_GET['action']) ? $_GET['action'] : 'dash';
$cat=new Category();
if(isset($_POST['submit']))
{
    $cat_name=$_POST['cat_name'];
    $cat_desc=$_POST['cat_desc'];
    $image = $_FILES['cat_img']['name'];
    $tmp   = $_FILES['cat_img']['tmp_name'];
    $path  = 'images/';
    move_uploaded_file($tmp,$path.$image);
    $cat->newCategory($cat_name,$cat_desc,$image);
  
}
if ($action == 'dash'){
    ?>
    <div class="row">
                           <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Manage Categories</div>
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center title-2">Catrgory Info</h3>
                                        </div>
                                        <hr>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="control-label mb-1">Category Name</label>
                                                <input  type="text" class="form-control" name="cat_name">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Description</label>
                                                <input  type="text" class="form-control"  name="cat_desc">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Image</label>
                                                <input  type="file" class="form-control"  name="cat_img">
                                            </div>
                                            <div>
                                                <button id="payment-button" type="submit"  name="submit"class="btn btn-lg btn-info btn-block">
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
                                                <th>image</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                             foreach($cat->showCategory() as $row)
                                                {
                                                 echo"<tr><td>".$row['cat_id']."</td><td>".$row['cat_name']."</td><td>".$row['cat_desc']."</td><td><img src='images/{$row['cat_img']}' width='100px' height='100px'></td>";
                                                 echo"<td><a href='categoryManage.php?action=edit&&id=".$row['cat_id']."'class='btn btn-primary'>Edit</a></td>";
                                                 echo "<td><a href='categoryManage.php?action=delete&&id=".$row['cat_id']."' class='btn btn-danger'>Delete</a></td></tr>";
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
    $cat->deleteCategory($_GET['id']);
    ?>
     <script type="text/javascript">
     window.location.href = 'categoryManage.php';
     </script>
    <?php
    
}
elseif($action == 'edit'){
    $arr=$cat->categoryInformationById($_GET['id']);
    if(isset($_POST['save']))
                        {
                          $n=$_POST['cat_name'];
                          $d=$_POST['cat_desc'];
                          
                          if(($_FILES['cat_img']['name']))
                          {
                            $image = $_FILES['cat_img']['name'];
                            $tmp   = $_FILES['cat_img']['tmp_name'];
                            $path  = 'images/';
                            move_uploaded_file($tmp,$path.$image);
                            $cat->editCategory($_GET['id'],$n,$d,$image);
                          }
                          else{
                            $cat->editCategory($_GET['id'],$n,$d,$arr['img']);
                          }
                          ?>
                          <script type="text/javascript">
                          window.location.href = 'categoryManage.php';
                          </script>
                          <?php
                        }                      
?>

                     <div class="row">
                           <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Manage Categories</div>
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center title-2">Catrgory Info</h3>
                                        </div>
                                        <hr>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="control-label mb-1">Category Name</label>
                                                <input  type="text" class="form-control" name="cat_name" value="<?php echo $arr['name'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Description</label>
                                                <input  type="text" class="form-control"  name="cat_desc" value="<?php echo $arr['desc'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Image</label>
                                                <input  type="file" class="form-control"  name="cat_img">
                                            </div>
                                            <div>
                                                <button id="payment-button" type="submit"  name="save" class="btn btn-lg btn-info btn-block">
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