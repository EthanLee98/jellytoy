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

$id  = req('id');
$stm = $_db->prepare('SELECT * FROM product WHERE id = ?');
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) redirect('product_list.php');

// ----------------------------------------------------------------------------

$_title = 'JellyToy - Product Detail';
include '../_head.php';
?>

<p>
    <img src="/images/products/<?= $p->photo1 ?>" id="photo">
</p>

<table class="table detail">
    <tr>
        <th>Id</th>
        <td><?= $p->id ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= $p->name ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= $p->price ?></td>
    </tr>
    <tr>
        <th>Unit</th>
        <td>
            <!-- TODO -->
            <?php
            $cart = get_cart();
            $id   = $p->id;
            $unit = $cart[$p->id] ?? 0;
            ?>
            <form method="post">
                <!-- TODO ✅ -->
                <?= html_hidden('id') ?>
                <?= html_select('unit', $_units, '') ?>
                <?= $unit ? '✅' : '' ?>
            </form>
        </td>
    </tr>
</table>

<p>
    <button data-get="list.php">List</button>
</p>

<script>
    // TODO
    $('select').on('change', e => e.target.form.submit());
</script>

<?php
include '../_foot.php';