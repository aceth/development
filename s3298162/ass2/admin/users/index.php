<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location: ../../login.php');
    exit();
}
include('../../include/function.php');
include('../header.php');
?>

<div id="main-content" class="pull-left">
    <div class="list-box">
        <h3 class="header">List user</h3>
        <table class="data-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $db = connectDb();
            $stmt = $db->prepare('SELECT * FROM users ORDER BY id DESC');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['email']}</td>";
                echo '<td>';
                echo "<a href='./edit.php?id={$row['id']}'>Edit</a><br />";
                if ($row['username'] != 'admin') {
                    echo "<a href='./delete.php?id={$row['id']}' class='action-delete'>Delete</a>";
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
    <br class="clear" />
</div>

<script type="text/javascript">
    $(function(){
        $('.action-delete').click(function(e){
            e.preventDefault();
            if (confirm("Are you sure you want to delete?")) {
                document.location = $(this).attr('href');
            }
        });


    });
</script>
<?php include('../footer.php'); ?>