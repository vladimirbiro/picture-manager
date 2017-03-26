<?php
namespace VladimirBiro\Picture;

use Nette\Utils\Strings;
use Nette\Utils\Image;
use Nette\Utils\Random;
use Nette\Utils\Json;
use Nette\Utils\FileSystem;
use Nette\Http\FileUpload;


class PictureManager
{
    private $pictureRoot;

    private $typeArray = array(
        array('thumb', 140, 140),
        array('small', 300, 300),
        array('normal', 600, 600),
        array('big', 1200, 1000)
    );


    /**
     * SavePicture
     */
    public function savePicture($file, $name, $dir)
    {
        $file = new FileUpload($file);





        if ($file->isImage() and $file->isOk())
        {
            $end = strtolower(mb_substr($file->getSanitizedName(), strrpos($file->getSanitizedName(), ".")));

            $newFileName = $this->namePicture($name);

            $newFileName .= $end;

            $file->move($this->pictureRoot . '/' . $dir . '/' . $newFileName);

            foreach($this->typeArray as $ta)
            {
                $thumb = Image::fromFile($this->pictureRoot . '/' . $dir . '/' . $newFileName);

                if($thumb->getWidth() > $thumb->getHeight()) {
                    $thumb->resize($ta[1], NULL);
                }
                else {
                    $thumb->resize(NULL, $ta[2]);
                }

                $thumb->sharpen();

                FileSystem::createDir($this->pictureRoot . '/' . $dir . '/' . $ta[0]);

                $thumb->save($this->pictureRoot . '/' . $dir . '/' . $ta[0] . '/'. $newFileName);
            }

            return Json::encode([
                'dir' => $dir,
                'name' => $newFileName
            ]);
        }

        return false;
    }



    /**
     * Delete picture
     */
    public function deletePicture($dir, $name = null)
    {
        if ($name === null)
        {
            FileSystem::delete($this->pictureRoot . '/' . $dir);
        }
        else
        {
            foreach($this->typeArray as $ta)
            {
                FileSystem::delete($this->pictureRoot . '/' . $dir . '/' . $ta[0] . '/' . $name);
            }

            FileSystem::delete($this->pictureRoot . '/' . $dir . '/' . $name);
        }

    }



    /**
     * Vygenerovanie nazvu obrazku
     */
    private function namePicture($name)
    {
        $fileName = $name . '-' . date('Ymdhis') . '-' . Random::generate(8, 'a-z0-9');
        $fileName = Strings::webalize($fileName);

        return $fileName;
    }


    /**
     * Nastavenie sirky
     */
    public function setType($type)
    {
        $this->typeArray = $type;
    }


    /**
     * Nastavenie hlavneho adresara s obrazkami
     */
    public function setPictureRoot($dir)
    {
        $this->pictureRoot = $dir;
    }



    public function hello()
    {
        return 'Picture Manager Say: Hello world';
    }
}