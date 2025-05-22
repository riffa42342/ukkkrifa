<?php
session_start();
include('koneksi.php');

    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        

        $sql = "SELECT * FROM  user WHERE username='$username'AND password='$password'";
        $result = mysqli_query($koneksi,$sql);

        if($result ->num_rows > 0){
            $data = mysqli_fetch_assoc($result);

            if($data ['role'] == "user"){
                header('location: index.php');
            }
            else if ($data['role'] == "admin"){
                header('location: admin.php');
            }
            else{
                header('location: superadmin.php');
            }
        }else{
            echo ' LOGIN GAGAL';
        }


    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
</head>
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f4f4f4;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-image: url('gam.jpeg'); /* Ganti dengan path gambar perpustakaan Anda */
  background-size: cover;
  background-position: center;
}

.box {
  background-color: rgba(255, 255, 255, 0.9); /* Sedikit transparan */
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 350px;
  text-align: center;
  transition: transform 0.2s ease-in-out;
}

.box:hover {
  transform: translateY(-5px);
}

h2 {
  color: #2c3e50;
  margin-bottom: 20px;
  font-weight: 600;
  border-bottom: 2px solid #4CAF50;
  padding-bottom: 10px;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-sizing: border-box;
  font-size: 1rem;
  transition: border-color 0.3s ease-in-out;
}

input[type="text"]:focus,
input[type="password"]:focus {
  border-color: #4CAF50;
  outline: none;
  box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

input[type="submit"] {
  width: 100%;
  padding: 12px 15px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: background-color 0.3s ease-in-out,
              transform 0.1s ease-in-out;
}

input[type="submit"]:hover {
  background-color: #45a049;
  transform: scale(1.02);
}

input[type="submit"]:active {
  background-color: #388e3c;
  transform: scale(1);
}

.error-message {
  color: #e74c3c;
  margin-bottom: 15px;
  font-size: 0.9rem;
  padding: 10px;
  background-color: #f8d7da;
  border-radius: 5px;
  border: 1px solid #f5c6cb;
}

.register-link {
  margin-top: 15px;
  font-size: 0.9rem;
  color: #2c3e50;
}

.register-link a {
  color: #4CAF50;
  text-decoration: none;
  font-weight: 500;
}

.register-link a:hover {
  text-decoration: underline;
}body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5; /* Lighter, more modern background */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Use min-height for better responsiveness */
    background-image: url('gam.jpeg'); /* Your background image */
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Fix background image on scroll */
    color: #333; /* Default text color */
}

.box {
    background-color: rgba(255, 255, 255, 0.95); /* Slightly less transparent */
    border-radius: 15px; /* More rounded corners */
    padding: 40px; /* Increased padding */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* More pronounced shadow */
    width: 380px; /* Slightly wider */
    max-width: 90%; /* Responsive width */
    text-align: center;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    border: 1px solid #e0e0e0; /* Subtle border */
}

.box:hover {
    transform: translateY(-8px); /* More pronounced lift effect */
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
}

h2 {
    color: #2c3e50;
    margin-bottom: 25px; /* More space below heading */
    font-weight: 700; /* Bolder font for heading */
    border-bottom: 3px solid #007bff; /* Changed to a modern blue accent */
    padding-bottom: 12px; /* Slightly more padding */
    font-size: 2em; /* Larger heading font */
    letter-spacing: 0.5px; /* Slight letter spacing */
}

input[type="text"],
input[type="password"] {
    width: calc(100% - 20px); /* Adjust width for padding */
    padding: 14px 10px; /* More vertical padding */
    margin-bottom: 20px;
    border: 1px solid #ccc; /* Lighter border color */
    border-radius: 8px; /* Slightly more rounded inputs */
    box-sizing: border-box;
    font-size: 1.05rem; /* Slightly larger text in inputs */
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    background-color: #fdfdfd; /* Subtle off-white background for inputs */
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #007bff; /* Focus color matches accent */
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25); /* Glow effect on focus */
}

input[type="submit"] {
    width: 100%;
    padding: 15px 20px; /* Larger button padding */
    background-color: #007bff; /* Primary blue button */
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

.error-message {
    color: #dc3545; /* Standard danger red */
    margin-bottom: 20px; /* More space */
    font-size: 0.95rem;
    padding: 12px;
    background-color: #f8d7da;
    border-radius: 8px; /* Matches other elements */
    border: 1px solid #f5c6cb;
    font-weight: 500; /* Slightly bolder text */
}

.register-link {
    margin-top: 20px; /* More space */
    font-size: 1rem;
    color: #555; /* Softer text color */
}

.register-link a {
    color: #007bff; /* Link color matches accent */
    text-decoration: none;
    font-weight: 600; /* Bolder link text */
    transition: color 0.3s ease-in-out;
}

.register-link a:hover {
    text-decoration: underline;
    color: #0056b3; /* Darker link on hover */
}
</style>

<body>

    <div class="box">
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Input Username" required>
            <input type="password" name="password" placeholder="Input password" required>
            <input type="submit" name="login" value="LOGIN">
        </form>
    </div>
</body>
</html>