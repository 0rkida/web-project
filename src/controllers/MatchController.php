<?php
namespace App\controllers;
session_start();
use App\models\Matches;


class MatchController
{
    public Matches $matches;
    private $dbConnection;

    public function __construct($dbConnection)
    {
        // Initialize the Matches model with the database connection
        $this->matches = new Matches($dbConnection);
        $this->dbConnection = $dbConnection;
    }

    /**
     * Show the match view to the logged-in user.
     */
    public function getView(): void
    {
        // Check if the user is logged in
        if (!isset($_SESSION['userId'])) {
            // Redirect to profile if not logged in
            header('Location: /profile');
            exit();
        }

        // Fetch match data for the user
        $matches = $this->matches->getMatchesByUser($_SESSION['userId']);

        // Include the view to display the matches
        require 'views/match_view.php';
    }

    /**
     * Handle the match creation or update from the POST request.
     */
    public function handlePostRequest(): void
    {
        // Only handle POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Retrieve match data from the form (use null coalescing operator)
        $matchName = $_POST['matchName'] ?? '';
        $matchDetails = $_POST['matchDetails'] ?? '';

        // Validate the match data
        if (empty($matchName) || empty($matchDetails)) {
            echo "Please fill in all fields.";
            return;
        }

        // Sanitize inputs to prevent XSS and other attacks
        $matchName = htmlspecialchars($matchName);
        $matchDetails = htmlspecialchars($matchDetails);

        // Call the model method to create the match
        if ($this->matches->createMatch($_SESSION['userId'], $matchName, $matchDetails)) {
            ($this->matches->updateMatch($_SESSION['userId'], $matchName, $matchDetails));
            ($this->matches->deleteMatch($_SESSION['userId'], $matchDetails));
            // Redirect to the matches page upon success
            header('Location: /matches');
            exit();
        } else {
            // Show an error if the match couldn't be created
            echo "An error occurred while creating the match.";
        }
    }
}
