<?php
require_once 'rest/dao/UserDao.php';
$userDao = new UserDao();

// CREATE
if (isset($_POST['create'])) {
    $userDao->create([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'image' => $_POST['image'],
        'role_id' => $_POST['role_id']
    ]);
    echo "User created!<br>";
}

// EDIT
if (isset($_POST['edit'])) {
    $userDao->edit($_POST['id'], [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'image' => $_POST['image'],
        'role_id' => $_POST['role_id']
    ]);
    echo "User updated!<br>";
}

// DELETE
if (isset($_POST['delete'])) {
    $userDao->deleteUser($_POST['id']);
    echo "User deleted!<br>";
}
?>

<h2>Test User</h2>
<form method="POST">
    ID (for update/delete): <input type="number" name="id"><br>
    Name: <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    Password: <input type="text" name="password"><br>
    Image: <input type="text" name="image"><br>
    Role ID: <input type="number" name="role_id"><br><br>

    <button name="create">Create User</button>
    <button name="edit">Edit User</button>
    <button name="delete">Delete User</button>
</form>
