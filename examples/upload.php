<?php

require '../src/FileStorage.php';

$uid = $_GET['uid'];

if(isset($_POST["submit"])) {
    $storage = new \Myvon\DeviceChanger\FileStorage('./upload');
    $target_dir = $storage->getDirectory($uid);
    if(!file_exists($target_dir)) {
        mkdir($target_dir);
    }
    $target_file = $target_dir .'/'. basename($_FILES["fileToUpload"]["name"]);
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
} else {
?>
<!DOCTYPE html>
<html>
<body>

<form action="#" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>
<?php

}
?>