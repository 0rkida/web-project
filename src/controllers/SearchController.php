<?php

class SearchController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn; // Database connection
    }

    public function getView()
    {
        include 'public/home.php'; // Display the home page with the form
    }

    // Handle the search logic (POST request)
    public function postSearch($data)
    {
        if (isset($data['name']) && !empty($data['name'])) {
            $name = htmlspecialchars($data['name']);

            // Query to search for users by name
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE full_name LIKE ?");
            $stmt->execute(['%' . $name . '%']);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Load the search results view with results
            include 'C:\xampp\htdocs\web-project\src\views\searchResults.php';
        } else {
            // Redirect to home if no name is provided
            header('Location: /search');
        }
    }
}
