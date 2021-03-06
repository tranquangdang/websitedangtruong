﻿<?php 
include '../lib/Session.php';
Session::checkSession();
?>
<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php include '../classes/Product.php'; ?>
<?php include_once '../helpers/Format.php'; ?>
<?php 
$pd = new Product();
$fm = new Format();
 ?>
 <?php 
if (isset($_GET['delpro'])) {
    $id = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['delpro']);
    $delPro = $pd->delProById($id);
}
 ?>

<div class="grid_10">
    <div class="box round first grid">
        <h2>Quản lý sản phẩm</h2>
        <?php 
                if (isset($delPro)) {
                    echo $delPro;
                }
                 ?>
        <div class="block">  
            <table class="data display datatable" id="example">
			<thead>
				<tr>
					<th	>Mã</th>
					<th >Tên sản phẩm</th>
					<th>Danh mục</th>
					<th>Hãng</th>
					<th>Giá bán</th>
					<th>% Giảm</th>
					<th>Kho</th>
					<th>Sửa/Xóa</th>
				</tr>
			</thead>
			<tbody>
				<?php 
                $getPd = $pd->getAllProduct();
                if ($getPd) {
                    while ($result = $getPd->fetch_assoc()) { ?>
					<tr class="odd gradeX">
						<td><?php echo $result['ProductID']; ?></td>
						<td><?php echo $fm->textShorten($result['ProductName'],50); ?></td>
						<td><?php echo $result['CategoryNo']; ?></td>
						<td><?php echo $result['Brand']; ?></td>
						<td>đ<?php echo number_format($pd->DiscountPrice($result['UnitPrice'],$result['PerDiscount'])); ?></td>
						<td><?php echo $result['PerDiscount']; ?></td>
						<td><?php echo $result['QtyOnHand']." cái"; ?></td>
						<td><a href="productedit.php?proid=<?php echo $result['ProductID']; ?>">Sửa</a> || <a onclick="return confirm('Bạn có muốn xóa sản phẩm này không?')" href="?delpro=<?php echo $result['ProductID']; ?>">Xóa</a></td>
					</tr>
				<?php
                    }
                } ?>
			</tbody>
		</table>

       </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
		setSidebarHeight();
    });
</script>
<?php include 'inc/footer.php';?>
