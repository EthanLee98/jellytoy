<?php
include '_base.php';

// ----------------------------------------------------------------------------



// ----------------------------------------------------------------------------

$_title = 'Lozodo - Product Management';
include '_head.php';
?>

<h2>Member Management</h2>

<!-- Sorting and Add Member Button -->
<div class="top-bar">
    <div class="sorting-options">
        <label>Sort By:</label>
        <button class="btn btn-sort" onclick="sortTable('id')">Member ID</button>
        <button class="btn btn-sort" onclick="sortTable('name')">Name</button>
        <button class="btn btn-sort" onclick="sortTable('email')">Email</button>
        <button class="btn btn-sort" onclick="sortTable('join_date')">Join Date</button>
    </div>
    <div class="button-container">
        <a href="addmember.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Member</a>
    </div>
</div>

<!-- Members Table -->
<table class="member-table">
    <thead>
        <tr>
            <th>Member ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Join Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Example Member Row -->
        <tr>
            <td>1</td>
            <td>Jane Smith</td>
            <td>jane.smith@example.com</td>
            <td>2024-01-15</td>
            <td>
                <a href="viewmember.php?id=1" class="btn btn-view"><i class="fas fa-eye"></i> View</a>
                <a href="updatemember.php?id=1" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                <a href="deletemember.php?id=1" class="btn btn-delete"><i class="fas fa-trash"></i> Delete</a>
            </td>
        </tr>
        <!-- Repeat rows as needed -->
    </tbody>
</table>
</div>
</div>
</section>

<?php
include '_foot.php';
?>

<script>
    function sortTable(column) {
        // Implement sorting logic here, possibly by using JavaScript to sort table rows.
        console.log('Sorting by ' + column);
    }
</script>