<?php
class Admin{
    private $adminName;
    private $adminEmail;
    private $adminPassword;
    private $conn;
   function __construct()
   {
    $this->conn=mysqli_connect("127.0.0.1","root","","ecom6");
   }
   public function setAdmin($adminID)
   {
    $query=mysqli_query($this->conn,"SELECT * FROM admin WHERE admin_id='$adminID'");
    $row=mysqli_fetch_assoc($query);
    $this->adminName=$row['admin_fullname'];
    $this->adminEmail=$row['admin_email'];
    $this->adminPassword=$row['admin_password'];
   }
   public function getAdmin()
   {
    //echo $this->adminName;
   }
   function newAdmin($fullname,$email,$password)
   {
    $query=mysqli_query($this->conn,"INSERT INTO admin (admin_fullname,admin_email,admin_password)
                                       VALUES('$fullname','$email','$password')");
   }
   function showAdmin()
   {
    $query=mysqli_query($this->conn,"SELECT * FROM admin");
    $array = array();
    $i=0;
                 while($row=mysqli_fetch_assoc($query))
                {  
                 $array[$i]['id']=$row['admin_id'];
                 $array[$i]['name']=$row['admin_fullname'];
                 $array[$i]['email']=$row['admin_email'];
                 $i++;
                }
                return $array;
   }
   
   function deleteAdmin($id)
   {
    $query=mysqli_query($this->conn,"DELETE FROM admin WHERE admin_id={$id}");
   }
   function editAdmin($id,$name,$email,$password)
   {
    $query=mysqli_query($this->conn,"UPDATE admin
                               SET admin_email='$email',
                               admin_fullname='$name',
                               admin_password='$password'
                               WHERE admin_id='$id'");
   }
   function adminInformationById($id)
   {
    $query=mysqli_query($this->conn,"SELECT * FROM admin WHERE admin_id=$id");
    $row=mysqli_fetch_assoc($query);
    $adminInfo = array();
    $adminInfo['email']=$row['admin_email'];
    $adminInfo['name']=$row['admin_fullname'];
    $adminInfo['pass']=$row['admin_password'];
    return $adminInfo;
   }
}
  include("includes/header.php");
  $action = isset($_GET['action']) ? $_GET['action'] : 'dash';
  $admin=new Admin();
  $admin_id=$_SESSION['admin_id'];
  $admin->setAdmin($admin_id);
 if(isset($_POST['submit']))
{
    $fullname=$_POST['fullname'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $admin->newAdmin($fullname,$email,$password);
  
}
if ($action == 'dash'){
?>
                         <div class="row">
                           <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Manage Admins</div>
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center title-2">Admin Info</h3>
                                        </div>
                                        <hr>
                                        <form action="" method="post">
                                            <div class="form-group">
                                                <label class="control-label mb-1">Full Name</label>
                                                <input  type="text" name="fullname" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Email</label>
                                                <input  type="text" name="email" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Password</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
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
                                                <th>Email</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                          foreach($admin->showAdmin() as $row)
                                          {
                                           echo"<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['email']."</td>";
                                           echo"<td><a href='adminManage.php?action=edit&&id=".$row['id']."'class='btn btn-primary'>Edit</a></td>";
                                           echo "<td><a href='adminManage.php?action=delete&&id=".$row['id']."' class='btn btn-danger'>Delete</a></td></tr>";
                                          }
                                          ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END DATA TABLE-->
                            </div>
                        </div>

 <?php }
elseif($action == 'delete'){
    $admin->deleteAdmin($_GET['id']);
    ?>
     <script type="text/javascript">
     window.location.href = 'adminManage.php';
     </script>
    <?php
    
}
elseif($action == 'edit'){
    $arr=$admin->adminInformationById($_GET['id']);
    if(isset($_POST['save']))
                        {
                          $n=$_POST['fullname'];
                          $e=$_POST['email'];
                          $p=$_POST['password'];
                          $admin->editAdmin($_GET['id'],$n,$e,$p);
                          ?>
                          <script type="text/javascript">
                          window.location.href = 'adminManage.php';
                          </script>
                          <?php
                        }                      
?>

                     <div class="row">
                           <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Manage Admins</div>
                                     <div class="card-body">
                                         <div class="card-title">
                                            <h3 class="text-center title-2">Admin Info</h3>
                                        </div>
                                        <hr>
                                         <form action="" method="post">
                                            <div class="form-group">
                                                <label class="control-label mb-1">Full Name</label>
                                                <input  type="text" name="fullname" class="form-control" value="<?php echo$arr['name'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Email</label>
                                                <input  type="text" name="email" class="form-control" value="<?php echo$arr['email'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-1">Password</label>
                                                <input type="text" name="password" class="form-control" value="<?php echo$arr['pass']; ?>">
                                            </div>
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
?>
<?php include("includes/footer.html") ;?>          