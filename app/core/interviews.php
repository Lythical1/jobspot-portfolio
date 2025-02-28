<?php

require_once 'database.php';

class Interview
{
    public $id;
    public $job_id;
    public $user_id;
    public $scheduled_at;
    public $status;
    public $created_at;
    // Additional properties from joins
    public $job_title;
    public $company_name;

    public static function getUpcomingInterviews($userId)
    {
        $db = Database::connectDb();
        $query = "SELECT i.*, j.title as job_title, c.name as company_name 
                FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                JOIN companies c ON j.company_id = c.id
                WHERE i.user_id = ? AND i.scheduled_at >= NOW() 
                ORDER BY i.scheduled_at ASC";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public static function getPastInterviews($userId)
    {
        $db = Database::connectDb();
        $query = "SELECT i.*, j.title as job_title, c.name as company_name 
                FROM interviews i
                JOIN jobs j ON i.job_id = j.id
                JOIN companies c ON j.company_id = c.id
                WHERE i.user_id = ? AND i.scheduled_at < NOW() 
                ORDER BY i.scheduled_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
