<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/3/2020
 * Time: 2:20 PM
 */

namespace System\App\UtilFileSystem;


class FileSystem
{
    /**
     * @param $input_name
     * @param $new_file_name
     * @return null|string
     * @throws \Exception
     */
    function uploadImage($input_name, $new_file_name) {
        if(isset($_FILES[$input_name]["tmp_name"])) {
            if(!file_exists(public_path("uploads"))) {
                mkdir(public_path("uploads"));
            }

            if(!file_exists(public_path("uploads/".date("Y-m-d")))) {
                mkdir(public_path("uploads/".date("Y-m-d")));
            }

            $ext = pathinfo($_FILES[$input_name]['name'],PATHINFO_EXTENSION);

            $check = getimagesize($_FILES[$input_name]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES[$input_name]["tmp_name"], getcwd()."/uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext)) {
                    return "uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext;
                } else {
                    throw new \Exception("File can't upload, please make sure that directory is exists or permission is writable");
                }
            } else {
                throw new \Exception("The file type is not an image!");
            }
        } else {
            throw new \InvalidArgumentException("You did not select any file!");
        }
    }

    /**
     * @param $input_name
     * @param $new_file_name
     * @return string
     * @throws \Exception
     */
    function uploadFile($input_name, $new_file_name) {
        if(isset($_FILES[$input_name]["tmp_name"])) {
            if(!file_exists(public_path("uploads"))) {
                mkdir(public_path("uploads"));
            }

            if(!file_exists(public_path("uploads/".date("Y-m-d")))) {
                mkdir(public_path("uploads/".date("Y-m-d")));
            }

            $ext = pathinfo($_FILES[$input_name]['name'],PATHINFO_EXTENSION);

            if (move_uploaded_file($_FILES[$input_name]["tmp_name"], getcwd()."/uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext)) {
                return "uploads/".date("Y-m-d")."/".$new_file_name.'.'.$ext;
            } else {
                throw new \Exception("File can't upload, please make sure that directory is exists or permission is writable");
            }
        } else {
            throw new \InvalidArgumentException("You did not select any file!");
        }
    }

}