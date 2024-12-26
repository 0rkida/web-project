<?php
class Analytics {
    public static function getNewUsersCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    public static function getMatchesCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM matches";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    public static function getReportsCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM reports";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    public static function getActiveUsersCount() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    public static function getSubscriptionStats() {
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS count, subscription_type FROM payments GROUP BY subscription_type";
        $result = mysqli_query($conn, $sql);
        $stats = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $stats[$row['subscription_type']] = $row['count'];
        }
        return $stats;
    }
}
?>