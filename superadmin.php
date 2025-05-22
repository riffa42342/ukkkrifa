<?php
session_start();
include('koneksi.php');
if(isset($_POST['tambah' ]) ){
$username = $_POST['username' ];
$password = $_POST['password'];
$role = $_POST['role'];
mysqli_query($koneksi, "INSERT INTO user(username, password, role)
VALUES ('$username', '$password', '$role' )");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUPERADMIN</title>
    <style>
       body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5; /* Lighter, more modern background */
    margin: 20px;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Ensure body fills viewport height */
    color: #333; /* Default text color */
}

form {
    background-color: rgba(255, 255, 255, 0.95); /* Slightly less transparent, matches other forms */
    border-radius: 15px; /* More rounded corners, consistent */
    padding: 40px; /* Increased padding */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* More pronounced shadow */
    width: 450px; /* Consistent form width */
    max-width: 90%; /* Responsive width */
    display: flex;
    flex-direction: column;
    gap: 18px; /* Increased space between inputs */
    border: 1px solid #e0e0e0; /* Subtle border */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

form:hover {
    transform: translateY(-5px); /* Subtle lift on hover */
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

h2 {
    color: #2c3e50;
    margin-bottom: 25px; /* More space below heading */
    font-weight: 700; /* Bolder font for heading */
    border-bottom: 3px solid #007bff; /* Consistent modern blue accent */
    padding-bottom: 12px; /* Slightly more padding */
    text-align: center;
    font-size: 2em; /* Larger heading font */
    letter-spacing: 0.5px;
}

input[type="text"],
input[type="password"], /* Added password type for consistency with other inputs */
select {
    width: calc(100% - 20px); /* Adjust width for padding */
    padding: 14px 10px; /* More vertical padding */
    border: 1px solid #ccc; /* Lighter border color */
    border-radius: 8px; /* Slightly more rounded inputs */
    box-sizing: border-box;
    font-size: 1.05rem; /* Slightly larger text in inputs */
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    background-color: #fdfdfd; /* Subtle off-white background for inputs */
    -webkit-appearance: none; /* Remove default styling for select on some browsers */
    -moz-appearance: none;
    appearance: none;
}

input[type="text"]:focus,
input[type="password"]:focus, /* Added password type for consistency */
select:focus {
    border-color: #007bff; /* Focus color matches accent */
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* Glow effect on focus */
}

input[type="submit"] {
    width: 100%;
    padding: 15px 20px; /* Larger button padding */
    background-color: #007bff; /* Consistent primary blue button */
    color: white;
    border: none;
    border-radius: 8px; /* Matches input border-radius */
    cursor: pointer;
    font-size: 1.2rem; /* Larger font for button */
    font-weight: 600; /* Bolder button text */
    transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    letter-spacing: 0.5px;
}

input[type="submit"]:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: translateY(-3px); /* Subtle lift on hover */
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Shadow on hover */
}

input[type="submit"]:active {
    background-color: #004085; /* Even darker blue on active */
    transform: translateY(0); /* Resets on click */
    box-shadow: none; /* Removes shadow on active */
}
    </style>
</head>
<body>
   <form action="superadmin.php" method="post">
    <input type="text" name="username" placeholder="username" required>
    <input type="text" name="password" placeholder="password" required>
    <select name="role">
        <option value="user">user</option>
        <option value="admin">admin</option>
        <option value="superadmin">superadmin</option>
    </select>
<input type="submit" name="tambah" value="TAMBAH">
   </form> 
</body>
</html>