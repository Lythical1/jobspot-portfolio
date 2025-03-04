<?php

require_once 'database.php';

class FileUpload
{
    private $targetDirectory = '../../assets/';

    private $avatarFileTypes = ['jpg', 'jpeg', 'png'];

    private $cvFileTypes = ['pdf', 'docx'];

    private $maxFileSize = 16 * 1024 * 1024; // MAX 16MB

    public function __construct($targetDirectory)
    {
        $this->targetDirectory .= $targetDirectory . '/';
    }

    public function uploadAvatar($file)
    {
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception("File size exceeds the maximum limit.");
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $this->avatarFileTypes)) {
            throw new Exception("Invalid file type.");
        }

        $fileName = uniqid('avatar_', true) . '.' . $fileExtension;
        $targetFilePath = $this->targetDirectory . $fileName;

        if (file_put_contents($targetFilePath, file_get_contents($file['tmp_name']))) {
            return $fileName;
        } else {
            throw new Exception("Error uploading the file.");
        }
    }

    public function uploadCV($file)
    {
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception("File size exceeds the maximum limit.");
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $this->cvFileTypes)) {
            throw new Exception("Invalid file type.");
        }

        $fileName = uniqid('cv_', true) . '.' . $fileExtension;
        $targetFilePath = $this->targetDirectory . $fileName;

        if (file_put_contents($targetFilePath, file_get_contents($file['tmp_name']))) {
            return $fileName;
        } else {
            throw new Exception("Error uploading the file.");
        }
    }
    public function delete($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            throw new Exception("File not found1`.");
        }
    }
}
