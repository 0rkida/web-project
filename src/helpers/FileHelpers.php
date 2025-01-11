<?php

class FileHelpers
{
    // Function to upload an image
    public static function uploadImage($image): string
    {
        $imageName = time() . '-' . $image['name'];
        $target = app_path('img/' . $imageName);


        move_uploaded_file($image['tmp_name'], $target);

        return $imageName;
    }

}