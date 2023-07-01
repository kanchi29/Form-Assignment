<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve form data
  $name = $_POST["name"];
  $age = $_POST["age"];
  $weight = $_POST["weight"];
  $email = $_POST["email"];

  // Handle file upload
  $targetDir = "uploads/"; // Directory to store uploaded files
  $targetFile = $targetDir . basename($_FILES["report"]["name"]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Check if file is a PDF
  if ($fileType != "pdf") {
    echo "Only PDF files are allowed.";
    $uploadOk = 0;
  }

  // Check if file already exists
  if (file_exists($targetFile)) {
    echo "File already exists.";
    $uploadOk = 0;
  }

  // Upload file
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["report"]["tmp_name"], $targetFile)) {
      // File uploaded successfully, insert data into database
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

      // Prepare and execute SQL query
      $sql = "INSERT INTO users (name, age, weight, email, report) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sisss", $name, $age, $weight, $email, $targetFile);
      $stmt->execute();

      $stmt->close();
      $conn->close();

      echo "Form submitted successfully.";
    } else {
      echo "Error uploading file.";
    }
  }
}
?>
