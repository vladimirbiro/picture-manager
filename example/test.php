<?php
namespace VladimirBiro\TestAplication;

use Nette\Http\FileUpload;
use VladimirBiro\Picture\PictureManager;

require __DIR__ . '/bootstrap.php';


$pm = new PictureManager();

/** Default parameters - BasePressenter */
$pm->setPictureRoot(__DIR__ . '/savepic');
$pm->setType([
    array('thumb', 150, 150),
    array('medium', 300, 300),
    array('big', 600, 600)
]);

if (isset($_POST))
{
    /** Input */
    $file = new FileUpload($_FILES['file']);
    $dir = '1';
    $name = 'Product name';

    echo $pm->savePicture($file, $name, $dir);
}


// Delete picture
//$pm->deletePicture('1', 'product-name-bsx-20170326122327-87q684nh.jpg');
// OR Delete all pictures in dir
// $pm->deletePicture('1');
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit">
</form>
