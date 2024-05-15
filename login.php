<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "data";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql_create_table = "CREATE TABLE IF NOT EXISTS emails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    number VARCHAR(20) NOT NULL
)";

if ($connection->query($sql_create_table) === TRUE) {
} else {
    echo "Error creating table: " . $connection->error . "<br>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['number']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['number'])) 
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $number = $_POST['number'];

        $check_sql = "SELECT * FROM emails WHERE email='$email'";
        $check_result = $connection->query($check_sql);
        if ($check_result->num_rows > 0) {
            echo "Error: Email already exists";
        } else {
            $insert_sql = "INSERT INTO emails (name, email, number) VALUES ('$name', '$email', '$number')";
            if ($connection->query($insert_sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $insert_sql . "<br>" . $connection->error;
            }
        }
    } else {
        echo "Name, email, and number are required";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Management System</title>
    <style>body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="text"],
        form input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Email Management</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>
            <label for="email">Email:</label>
            <input type="email" name="email" required><br>
            <label for="number">Number:</label>
            <input type="text" name="number" required><br>

            <input type="submit" value="Submit">
        </form>

        <h2>Email List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Number</th>
            </tr>
            <?php
            $sql = "SELECT name, email, number FROM emails";

            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["number"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No entries found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$connection->close();
?>
