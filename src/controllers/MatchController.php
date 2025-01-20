<?php
namespace App\controllers;
session_start();
use App\models\Matches;


class MatchController
{
    private $matchesModel;

    public function __construct($db)
    {
        $this->matchesModel = new Matches($db);
    }

    public function getView($type, $userId)
    {
        if ($type === 'matches') {
            $matches = $this->matchesModel->getMatchesForUser($userId);
            require 'src/views/matches.php';
        } elseif ($type === 'notifications') {
            $notifications = $this->matchesModel->getNotificationsForUser($userId);
            require 'src/views/notifications.php';
        } else {
            echo "Invalid type.";
        }
    }

    public function postLike($likedByUserId, $likedUserId)
    {
        if ($this->matchesModel->addLike($likedByUserId, $likedUserId)) {
            if ($this->matchesModel->checkForMutualLike($likedByUserId, $likedUserId)) {
                $match = $this->matchesModel->createMatch($likedByUserId, $likedUserId);
                echo "It's a match!";
                print_r($match);
            } else {
                echo "Like added.";
            }
        } else {
            echo "Error adding like.";
        }
    }
}
