<?php
include '_base.php';

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Lozodo - Product Management';
include '_head.php';
?>

<h2>Order Management</h2>

<!-- Sorting and Add Order Button -->
<div class="top-bar">
    <div class="sorting-options">
        <label>Sort By:</label>
        <button class="btn btn-sort" onclick="sortTable('id')">Order ID</button>
        <button class="btn btn-sort" onclick="sortTable('date')">Order Date</button>
        <button class="btn btn-sort" onclick="sortTable('status')">Status</button>
        <button class="btn btn-sort" onclick="sortTable('total')">Total Amount</button>
    </div>
    <div class="button-container">
        <a href="addorder.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Order</a>
    </div>
</div>

<!-- Orders Table -->
<table class="order-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Total Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Example Order Row -->
        <tr>
            <td>1</td>
            <td>2024-01-20</td>
            <td>Shipped</td>
            <td>$39.99</td>
            <td>
                <a href="vieworder.php?id=1" class="btn btn-view"><i class="fas fa-eye"></i> View</a>
                <a href="updateorder.php?id=1" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                <a href="deleteorder.php?id=1" class="btn btn-delete"><i class="fas fa-trash"></i> Delete</a>
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

<script>
    function sortTable(column) {
        // Implement sorting logic here, possibly by using JavaScript to sort table rows.
        console.log('Sorting by ' + column);
    }
</script>