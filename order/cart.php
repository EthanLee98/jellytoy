<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Handle search
$search_term = req('search') ?? ''; // Get the search term from the request
$search_sql = '';
if ($search_term) {
    $search_sql = 'AND (id = ? OR name LIKE ?)';
    $search_param = [$search_term, '%' . $search_term . '%'];
}

// Handle cart operations
if (is_post()) {
    $btn = req('btn');
    if ($btn == 'clear') {
        set_cart();
        redirect('?');
    }

    $id     = req('id');
    $unit   = req('unit');
    update_cart($id, $unit);
    redirect();
}

// ----------------------------------------------------------------------------

$_title = 'Lozodo - Shopping Cart';
include '../_head.php';
?>
<link rel="stylesheet" type="text/css" href="/css/cart.css">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="margin-bottom: 20px;">
    <ol class="breadcrumb" style="background-color: transparent; padding-left: 0;">
        <li class="breadcrumb-item">
            <a href="/index.php">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="/product/product_list.php">Product</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Cart</li>
    </ol>
</nav>

<!-- Search Form -->
<form method="get" class="search-bar">
    <input type="text" name="search" placeholder="Search by product ID or name" value="<?= htmlentities($search_term) ?>">
    <button type="submit">Search</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Price (RM)</th>
                <th>Unit</th>
                <th>Subtotal (RM)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            $total = 0;

            $stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
            $cart = get_cart();

            foreach ($cart as $id => $unit):
                // If search term is provided, filter products accordingly
                $stm->execute([$id]);
                $p = $stm->fetch();

                // Skip non-matching items if a search is active
                if ($search_term && !(stripos($search_term, 'P' . sprintf('%03d', $p->id)) !== false || $p->id == $search_term)) {
                    continue;
                }

                $subtotal = $p->price * $unit;
                $count += $unit;
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= 'P' . sprintf('%03d', $p->id) ?></td>
                    <td>
                        <?= $p->name ?>
                        <img src="/images/products/<?= $p->photo1 ?>" class="popup" alt="Product Image">
                    </td>
                    <td class="right"><?= $p->price ?></td>
                    <td>
                        <form method="post">
                            <?= html_hidden('id', $p->id) ?>
                            <?php $_units = array_combine(range(1, $p->stock), range(1, $p->stock)); ?>
                            <?= html_select('unit', $_units, $unit) ?>
                        </form>
                    </td>
                    <td class="right"><?= sprintf('%.2f', $subtotal) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="cart-summary">Total Items:</th>
                <th class="right"><?= $count ?></th>
                <th class="right"><?= sprintf('%.2f', $total) ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<p class="buttons">
    <?php if ($cart): ?>
        <button data-post="?btn=clear">Clear Cart</button>

        <?php if (isset($_SESSION['user'])): ?>
            <button data-post="checkout.php">Checkout</button>
        <?php else: ?>
            Please <a href="/login.php">login</a> as member to checkout
        <?php endif ?>
    <?php endif ?>
</p>

<?php
include '../_foot.php';
?>

<script src="/js/cart.js"></script>