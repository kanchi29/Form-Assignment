<?php
// Retrieve the email ID from the query string or form submission
$email = $_GET['email']; // Assuming email is passed via query string

// Connect to the database
$servername = "localhost";
$username = "kanchi29";
$password = "louis369";
$dbname = "form_details";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL query to fetch the report file path
$sql = "SELECT report FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($report);
$stmt->fetch();

$stmt->close();
$conn->close();

// Check if a report is found for the given email ID
if ($report) {
  // Send the file to the browser for download
  header("Content-type: application/pdf");
  header("Content-Disposition: attachment; filename=\"report.pdf\"");
  readfile($report);
} else {
  echo "No health report found for the provided email ID.";
}
?>
