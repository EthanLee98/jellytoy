<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// If the user submits a GET request with filters
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000;
$keyword   = isset($_GET['keyword']) ? '%' . $_GET['keyword'] . '%' : '%';
$sort      = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Determine the sorting order based on the selected option
switch ($sort) {
    case 'name_desc':
        $order_by = 'name DESC';
        break;
    case 'description_asc':
        $order_by = 'description ASC';
        break;
    case 'description_desc':
        $order_by = 'description DESC';
        break;
    case 'price_asc':
        $order_by = 'price ASC';
        break;
    case 'price_desc':
        $order_by = 'price DESC';
        break;
    case 'stock_asc':
        $order_by = 'stock ASC';
        break;
    case 'stock_desc':
        $order_by = 'stock DESC';
        break;
    default:
        $order_by = 'name ASC';
}

$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 10000;
$keyword = isset($_GET['keyword']) ? '%' . $_GET['keyword'] . '%' : '%';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$products_per_page = 4;
$offset = ($page - 1) * $products_per_page;

$arr = $_db->prepare("
    SELECT * 
    FROM product 
    WHERE price BETWEEN :min_price AND :max_price 
    AND (name LIKE :keyword OR description LIKE :keyword)
    ORDER BY $order_by 
    LIMIT $products_per_page OFFSET $offset
");

$arr->execute([
    'min_price' => $min_price,
    'max_price' => $max_price,
    'keyword'   => $keyword,
]);

// Get total product count
$total_products_query = $_db->prepare("
    SELECT COUNT(*) 
    FROM product 
    WHERE price BETWEEN :min_price AND :max_price 
    AND (name LIKE :keyword OR description LIKE :keyword)
");

$total_products_query->execute([
    'min_price' => $min_price,
    'max_price' => $max_price,
    'keyword'   => $keyword,
]);

$total_products = $total_products_query->fetchColumn();
$total_pages = ceil($total_products / $products_per_page);

if (is_post()) {
    // TODO
    $id     = req('id');
    $unit   = req('unit');
    update_cart($id, $unit);
    redirect();
}

// ----------------------------------------------------------------------------

$_title = 'Lozodo - Product List';
include '../_head.php';
?>

<link rel="stylesheet" type="text/css" href="/css/product_list.css">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" style="margin-bottom: 20px;">
    <ol class="breadcrumb" style="background-color: transparent; padding-left: 0;">
        <li class="breadcrumb-item">
            <a href="/index.php">Home</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Product</li>
    </ol>
</nav>

<div id="products">
    <div class="products">
        <div class="wrapper">
            <div class="price-range">
                <h2>Price Range</h2>
                <form id="filter-form" method="get" action="product_list.php">
                    <div class="price-input">
                        <div class="field">
                            <span>Min</span>
                            <input type="number" name="min_price" class="input-min" value="<?= isset($_GET['min_price']) ? $_GET['min_price'] : '0' ?>">
                        </div>
                        <div class="separator">-</div>
                        <div class="field">
                            <span>Max</span>
                            <input type="number" name="max_price" class="input-max" value="<?= isset($_GET['max_price']) ? $_GET['max_price'] : '10000' ?>">
                        </div>
                    </div>
                    <div class="slider">
                        <div class="progress"></div>
                    </div>
                    <div class="range-input">
                        <input type="range" name="range_min" class="range-min" min="0" max="10000" value="0" step="100">
                        <input type="range" name="range_max" class="range-max" min="0" max="10000" value="10000" step="100">
                    </div>

                    <div class="input-box">
                        <i class="uil uil-search"></i>
                        <input type="text" name="keyword" placeholder="Search here..." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>" />
                        <button type="submit" class="button">Search</button>
                    </div>

                    <!-- Sorting Dropdown -->
                    <div class="sort-box">
                        <label for="sort">Sort By:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
                            <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name Z-A</option>
                            <option value="description_asc" <?= $sort === 'description_asc' ? 'selected' : '' ?>>Description A-Z</option>
                            <option value="description_desc" <?= $sort === 'description_desc' ? 'selected' : '' ?>>Description Z-A</option>
                            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price Low to High</option>
                            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price High to Low</option>
                            <option value="stock_asc" <?= $sort === 'stock_asc' ? 'selected' : '' ?>>Stock Low to High</option>
                            <option value="stock_desc" <?= $sort === 'stock_desc' ? 'selected' : '' ?>>Stock High to Low</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="products">
        <?php foreach ($arr as $p): ?>
            <?php
            $cart = get_cart();
            $id   = $p->id;
            $unit = $cart[$p->id] ?? 0;
            ?>
            <div class="product-card">
                <div class="logo-cart">
                    <img src="/images/products/<?= $p->photo1 ?>" data-get="/product/detailpage/productmain.php?id=<?= $p->id ?>">
                    <i class="bx bx-shopping-bag"></i>
                </div>
                <div class="shoe-details">
                    <span class="shoe_name"><?= $p->name ?></span>
                    <p>Brand: <?= $p->brand ?></p>
                    <p><?= $p->description ?></p>
                    <div class="stars">
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bx-star"></i>
                    </div>
                    <div class="stock">
                        Stock: <?= $p->stock ?>
                    </div>
                </div>
                <div class="color-price">
                    <div class="price">
                        <span class="price_num">RM <?= $p->price ?></span>
                    </div>
                </div>
                <br />
                <form method="post">
                    Items in Cart:
                    <?= $unit ? '✅' : '' ?>
                    <?= html_hidden('id') ?>
                    <?php $_units = array_combine(range(1, $p->stock), range(1, $p->stock)); ?>
                    <?= html_select('unit', $_units, '') ?>
                </form>
                <div class="button">
                    <div class="button-layer"></div>
                    <button data-get="/product/detailpage/productmain.php?id=<?= $p->id ?>">Product Detail</button>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1" class="arrow">First</a>
            <a href="?page=<?= $page - 1 ?>" class="arrow">←</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="arrow">→</a>
            <a href="?page=<?= $total_pages ?>" class="arrow">Last</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
include '../_foot.php';
?>

<script src="/js/product_list.js"></script>