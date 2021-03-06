<?php require ("include/topheader.php"); ?>
<?php 

if (!$cart->checkCartItem()) {
    header("Location:index.php");
}

if (isset($_GET['DelCart'])) {
    $DelCart = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['DelCart']);
    if($DelCart == 'True'){
        $delAllProducts = $cart->delCustomerCart();
        header("Location:index.php");
    }
}

//Sự kiện xóa từng sản phẩm
if (isset($_GET['ProductID']) && isset($_GET['CartID'])) {
    $ProductID = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['ProductID']);
    $CartID = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['CartID']);
    $delProduct = $cart->delProductByCart($CartID,$ProductID);
}

//Sự kiện cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CartID = $_POST['CartID'];
    $ProductID = $_POST['ProductID'];
    $QtyOrdered = $_POST['QtyOrdered'];
    $updateCart = $cart->updateCartQuantity($CartID, $ProductID, $QtyOrdered);
}
?>
<?php require ("include/header.php"); ?>
<?php require ("include/search.php"); ?>
<?php require ("include/sidebar.php"); ?>
        <div id="content" class="float_r">
            <h1>Giỏ hàng</h1>
        	<table class="table table-responsive table-bordered">
                <tr>
                    <th style="padding-left: 5px; vertical-align: middle;  text-align: center;">Hình ảnh </th> 
                    <th style="vertical-align: middle;  text-align: center;">Mô tả </th> 
                    <th style="padding: 5px; vertical-align: middle;  text-align: center;" >Số lượng </th> 
                    <th style="vertical-align: middle;  text-align: center;">Giá tiền </th> 
                    <th style="padding-right: 5px; vertical-align: middle;  text-align: center;">Tổng cộng </th> 
                    <th style="vertical-align: middle;  text-align: center;"><a href="shoppingcart.php?DelCart=True" class="btnBlack" style="color: crimson; cursor: pointer; padding: 0">Xóa hết</a></th> 
                </tr
                <?php 
                    //Lấy id của khách đang đăng nhập
                    $CustNo = Session::get('customerId');
                    $select = "SELECT * FROM tblCart WHERE CustNo = '$CustNo'";
                    $getCustNo = $database->select($select);
                    if( ($row = $getCustNo->fetch_assoc()) != NULL ) {$CartID = $row['CartID'];}
                    //Lấy sản phẩm trong giỏ hàng đã có của khách
                    $getPro = $cart->getCartProduct($CartID);

                    if($getPro) {
                    //Duyệt để hiển thị thông tin sản phẩm trong giỏ hàng của khách
                    while ($rows = $getPro->fetch_assoc()) {
                    ?>
                <tr>
                    <td><a href="productdetail.php?ProductID=<?php echo $rows['ProductID']; ?>" style="display: block "><img style="max-width: 140px; height: 150px; vertical-align: middle;  text-align: center;" src="<?php echo $product->checkImg($rows['ProductImg']); ?>" alt="image" /></a></td> 
                    <td style="font-size:15px; color: black; font-weight: bold; vertical-align: middle;  text-align: center;"><a href="productdetail.php?ProductID=<?php echo $rows['ProductID']; ?>" style="display: block; color: #000;"><span><?php echo $rows['ProductName']; ?></span></a></td> 
                    <td style="vertical-align: middle;  text-align: center;">
                        <form action="" method="post">
                            <input type="hidden" name="CartID" value="<?php echo $rows['CartID']; ?>"/>
                            <input type="hidden" name="ProductID" value="<?php echo $rows['ProductID']; ?>"/>
                            <input type="number" name="QtyOrdered" value="<?php echo $rows['QtyOrdered']; ?>" min="1" onKeyDown="return false"  max="<?php echo $rows["QtyOnHand"]; ?>" style="width: 50px; text-align: right" />
                            <input class="update btn" type="submit" name="submit" value="Cập nhật"/>
                        </form>
                    </td>
                    <td style="vertical-align: middle;  text-align: center;">₫<?php echo number_format($product->DiscountPrice($rows['UnitPrice'],$rows['PerDiscount'])); ?></td>
                    <td style="vertical-align: middle;  text-align: center;">₫<?php $total = $product->DiscountPrice($rows['UnitPrice'],$rows['PerDiscount']) * $rows['QtyOrdered']; echo number_format($total); ?></td></td>
                    <td style="vertical-align: middle;  text-align: center;"> <a class="btn" href="shoppingcart.php?CartID=<?php echo $rows['CartID']; ?>&ProductID=<?php echo $rows['ProductID']; ?>" style="color: crimson;"><img src="images/remove_x.gif" alt="remove" /><br />Xóa</a> </td>
                </tr>
                    <?php 
                        if ($rows["QtyOnHand"] == 0) {
                            $cart->delProductByCart($CartID,$rows['ProductID']);
                        }
                        }
                    }
                    ?>
                <tr>
                    <td colspan="3" style="font-size:20px; background:#ddd; font-weight:bold; vertical-align: middle;  text-align: right;"> Tổng cộng: </td>
                    <td colspan="3" style="background:#ddd; font-weight:bold; vertical-align: middle;  text-align: left  ;"><p class="product_price" style="margin: 0 10px; "><a>₫</a><?php 
                        //Coi có sp trong giỏ k
                        $total = $cart->getTotalMoney();
                        if ($getData) {
                            echo number_format($total);
                        }?>
                        </p>
                    </td>
                </tr>
            </table>
            <div style="float:right; width: 215px; margin-top: 20px;">
                <a class="blackBtn btn" href="checkout.php" style=" text-align: center; width: 200px; ">MUA HÀNG</a>
                <br>
                <p><a href="javascript:history.back()">Tiếp tục mua sắm</a></p>        	
            </div>
        </div>
        <div class="cleaner"></div>
    </div> <!-- END of main -->
    
    <?php require ("include/footer.php") ?>