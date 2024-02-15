<?php
// Include your database connection file
include 'koneksi.php';

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute a query to retrieve product data by ID
    $stmt = $conn->prepare("SELECT * FROM laporan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if data is found
    if ($result->num_rows > 0) {
        // Fetch product data as an associative array
        $data = $result->fetch_assoc();

        // Return JSON response with success and product data
        echo json_encode(array('success' => true, 'data' => $data));
    } else {
        // Return JSON response indicating that no data was found
        echo json_encode(array('success' => false, 'message' => 'Data not found.'));
    }

    // Close the statement
    $stmt->close();
} else {
    // Return JSON response indicating missing 'id' parameter
    echo json_encode(array('success' => false, 'message' => 'Missing ID parameter.'));
}

// Close the database connection
$conn->close();
?>
