<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }
echo "<BR><BR><A HREF='http://las.mine.nu:49152/uploads/" . basename( $_FILES["fileToUpload"]["name"]) . "'>File was received.</A><BR>";
echo "<BR><BR><A HREF='http://las.mine.nu:49152/upload.html'>Click here to upload another file</A>";
?>
