<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    // TODO
    $id     = req('id');
    $unit   = req('unit');
    update_cart($id, $unit);
    redirect();
}

$arr = $_db->query('SELECT * FROM product');

// ----------------------------------------------------------------------------

$_title = 'JellyToy - Product List';
include '../_head.php';
?>

<div id="products">
    <?php foreach ($arr as $p): ?>
        <!-- TODO -->
         <?php
         $cart = get_cart();
         $id   = $p->id;
         $unit = $cart[$p->id] ?? 0;
         ?>
        <div class="product">
            <form method="post">
                <!-- TODO ✅ -->
                <?= $unit ? '✅' : '' ?>
                <?= html_hidden('id') ?>
                <?= html_select('unit', $_units, '') ?>
            </form>
                
            <img src="/images/products/<?= $p->photo1 ?>"
                 data-get="/product/product_detail.php?id=<?= $p->id ?>">

            <div><?= $p->name ?> | RM <?= $p->price ?></div>
        </div>
    <?php endforeach ?>
</div>

<script>
    // TODO
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';