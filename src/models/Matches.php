<?php
namespace App\models;
use PDO;

class Matches {
    private PDO $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getMatchesByUser($userId): array {
        // Query the database to fetch matches for the user
        $stmt = $this->dbConnection->prepare("SELECT * FROM matches WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createMatch($userId, $matchName, $matchDetails): bool {
        // Insert a new match into the database
        $stmt = $this->dbConnection->prepare("INSERT INTO matches (user_id, match_name, match_details) VALUES (:userId, :matchName, :matchDetails)");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':matchName', $matchName);
        $stmt->bindParam(':matchDetails', $matchDetails);
        return $stmt->execute();
    }
    public function deleteMatch($userId, $matchId): bool {
        $stmt = $this->dbConnection->prepare("DELETE FROM matches WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':matchId', $matchId);
        return $stmt->execute();
    }

    public function updateMatch($userId, $matchName, $matchDetails): bool
    {
        $stmt = $this->dbConnection->prepare("UPDATE  matches SET match_name = :matchName, match_details = :matchDetails WHERE user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':matchName', $matchName);
        $stmt->bindParam(':matchDetails', $matchDetails);

        return $stmt->execute();
    }

    public function getAllMatches($matchName): array {
        $stmt = $this->dbConnection->prepare("SELECT * FROM matches WHERE match_name = :matchName");
        $stmt->bindParam(':matchName', $matchName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
