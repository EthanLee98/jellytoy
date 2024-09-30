<?php
include '_base.php';

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Lozodo - Product Management';
include '_head.php';
?>
<h2>Sales Management</h2>
<div class="button-container">
    <a href="addsale.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Sale</a>
</div>

<!-- Sales Table -->
<table class="sales-table">
    <thead>
        <tr>
            <th>Sale ID</th>
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Total Amount</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Example Sale Row -->
        <tr>
            <td>2001</td>
            <td>Example Product</td>
            <td>5</td>
            <td>$99.95</td>
            <td>2024-09-15</td>
            <td>
                <a href="viewsale.php?id=2001" class="btn btn-view"><i class="fas fa-eye"></i> View</a>
                <a href="updatesale.php?id=2001" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                <a href="deletesale.php?id=2001" class="btn btn-delete"><i class="fas fa-trash"></i> Delete</a>
            </td>
        </tr>
        <!-- Repeat rows as needed -->
    </tbody>
</table>
</div>
</div>
<?php
include '_foot.php';
?>