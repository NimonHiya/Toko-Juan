<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
    $message[] = 'Cart quantity updated successfully!';
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
}

include 'header.php';
?>

<div class="container">

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message" onclick="this.remove();">' . $msg . '</div>';
        }
    }
    ?>

    <div class="shopping-cart">
        <h1 class="heading">Keranjang</h1>
        <table>
            <thead>
                <th>Image</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Hapus</th>
            </thead>
            <tbody>
                <?php
                $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                $grand_total = 0;
                if (mysqli_num_rows($cart_query) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                        // Calculate subtotal for this item
                        $fetch_cart['price'] = floatval($fetch_cart['price']);
                        $fetch_cart['quantity'] = intval($fetch_cart['quantity']);
                        $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $grand_total += $sub_total;
                ?>
                        <tr>
                            <td><img src="<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
                            <td><?php echo $fetch_cart['name']; ?></td>
                            <td>$<?php echo $fetch_cart['price']; ?>/-</td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                    <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                                    <input type="submit" name="update_cart" value="Update" class="option-btn">
                                </form>
                            </td>
                            <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Hapus</a></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="5">No item added</td></tr>';
                }
                ?>
                <tr class="table-bottom">
                    <td colspan="3">Total Harga:</td>
                    <td>$<?php echo $grand_total; ?>/-</td>
                    <td><a href="cart.php?delete_all" onclick="return confirm('Delete all from cart?');" class="delete-btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Hapus semua</a></td>
                </tr>
            </tbody>
        </table>

        <div class="cart-btn">
            <a href="#" class="btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Proses checkout</a>
        </div>

    </div>

</div>

</body>
</html>