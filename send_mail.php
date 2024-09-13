<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $affiliation = htmlspecialchars($_POST['affiliation']);
    $country = htmlspecialchars($_POST['country']);
    $pdf = $_FILES['pdf'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($pdf["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a PDF
    if ($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($pdf["tmp_name"], $target_file)) {
            // Send email
            $to = "recipient@example.com";
            $subject = "New Registration from $name";
            $message = "Name: $name\nEmail: $email\nAffiliation: $affiliation\nCountry: $country\nPDF: $target_file";
            $headers = "From: $email";

            if (mail($to, $subject, $message, $headers)) {
                echo "Registration successful. Email sent.";
            } else {
                echo "Email sending failed.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo "Invalid request method.";
}
?>