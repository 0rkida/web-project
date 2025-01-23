<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Europe/Rome');


$query_insert_log = "INSERT INTO logs
                            SET user_id = '".$_SESSION['id']."',
                                action = '".$action."',
                                data = '".$data."',
                                created_at = '". date("Y-m-d H:i:s") ."'
                            ";
$result_stripe_logs_cards = mysqli_query($conn, $query_insert_log);