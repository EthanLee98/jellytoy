<?php
include '../_base.php';

// Redirect if the cart is empty
$cart = get_cart();
if (empty($cart)) {
    redirect('/cart.php'); // Redirect to the cart page if the cart is empty
}

// Process the checkout submission
if (is_post()) {
    // Example: processing order details here
    $payment_method = req('payment_method');

    // Perform validations
    if (empty($name) || empty($address)) {
        $error = 'Please fill in all the required fields.';
    } else {
        // TODO: Save order to the database
        // Example: save_order($name, $address, $payment_method, $cart);

        // Clear the cart after successful order placement
        set_cart(); // clear cart

        // Redirect to thank you page or order confirmation
        redirect('/thankyou.php');
    }
}

$_title = 'Lozodo - Checkout';
include '../_head.php';

// Fetch product details from the database
$stm = $_db->prepare('SELECT * FROM product WHERE id = ?');

// Calculate totals
$count = 0;
$total = 0;
?>
<link rel="stylesheet" type="text/css" href="/css/checkout.css">

<div class="checkout-wrapper">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: transparent; padding-left: 0;">
            <li class="breadcrumb-item">
                <a href="/order/index.php">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/order/product.php">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/order/cart.php">Cart</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <div class="checkout-container">
        <div class="container">
            <div class="row">
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <img
                                                src="/images/logo_text.png"
                                                style="width: 100%; max-width: 300px" />
                                        </td>

                                        <td>
                                            Transaction ID: <br />
                                            Created: <br />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="information">
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td>
                                            Shipping Address:<br />
                                            <br /><br />
                                            Payment Method:<br />
                                        </td>

                                        <td>
                                            Name: <br />
                                            Contact: <br />
                                            Email: <br />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="heading">
                            <td>Item</td>
                            <td>Price (RM)</td>
                        </tr>
                        <?php
                        // Process cart items and calculate totals
                        $count = 0;
                        $total = 0;
                        $shipping = 10.00; // Example shipping fee
                        $tax_rate = 0.08; // 8% tax
                        $discount_rate = 0.08; // 8% discount
                        $card_charges = 12.12; // Example card charges

                        $index = 1;
                        foreach ($cart as $id => $unit):
                            $stm->execute([$id]);
                            $p = $stm->fetch(PDO::FETCH_OBJ);

                            $subtotal = $p->price * $unit;
                            $count += $unit;
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= $index++ . '. P' . sprintf('%03d', $p->id) . ' - ' . htmlspecialchars($p->name) . ' (RM ' . sprintf('%.2f', $p->price) . ') x' . $unit ?></td>
                                <td class="right"><?= sprintf('%.2f', $subtotal) ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $discount = $total * $discount_rate;
                        $discounted_total = $total - $discount;

                        $tax = $discounted_total * $tax_rate;

                        $final_total = $discounted_total + $shipping + $tax + $card_charges;
                        ?>

                        <tr class="item">
                            <td style="border-top: 1px solid rgb(199, 199, 199);">Subtotal</td>
                            <td style="border-top: 1px solid rgb(199, 199, 199);"><?= sprintf('%.2f', $total) ?></td>
                        </tr>

                        <tr class="item">
                            <td>Discount (8%)</td>
                            <td colspan="5">-<?= sprintf('%.2f', $discount) ?></td>
                        </tr>

                        <tr class="item">
                            <td>Shipping</td>
                            <td colspan="5"><?= sprintf('%.2f', $shipping) ?></td>
                        </tr>

                        <tr class="item">
                            <td>Sales Tax (8%)</td>
                            <td colspan="5"><?= sprintf('%.2f', $tax) ?></td>
                        </tr>

                        <tr class="heading">
                            <td style="border-top: 1px solid rgb(199, 199, 199);border-bottom: 1px solid rgb(199, 199, 199);">Total Amount (RM)</td>
                            <td colspan="5" style="border-top: 1px solid rgb(199, 199, 199);border-bottom: 1px solid rgb(199, 199, 199);"><?= sprintf('%.2f', $final_total) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.invoice -->
        <div class="print-btn">
            <div class="container">
                <div class="row justify-content-center">
                    <button class="button-54 button-55" type="button" id="printInvoice">Print Invoice</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../_foot.php';
?>