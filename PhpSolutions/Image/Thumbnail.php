<?php
namespace PhpSolutions\Image;

class Thumbnail {
    protected $original;
    protected $originalwidth;
    protected $originalheight;
    protected $basename;
    protected $thumbwidth;
    protected $thumbheight;
    protected $maxSize = 120;
    protected $canProcess = false;
    protected $imageType;
    protected $destination;
    protected $suffix = '_thb';
    protected $messages = [];
    protected $new_thumbnails = [];

    public function __construct($image) {
        if (is_file($image) && is_readable($image)) {
            $details = getimagesize($image);
        } else {
            $details = null;
            $this->messages[] = "Cannot open $image.";
        }
        // if getimagesize() returns an array, it looks like an image
        if (is_array($details)) {
            $this->original = $image;
            $this->originalwidth = $details[0];
            $this->originalheight = $details[1];
            $this->basename = pathinfo($image, PATHINFO_FILENAME);
            // check the MIME type
            $this->checkType($details['mime']);
        } else {
            $this->messages[] = "$image doesn't appear to be an image.";
        }
    }
    
    public function getNewThumbnails() {
        return $this->new_thumbnails;
    }

    public function setDestination($destination) {
        if (is_dir($destination) && is_writable($destination)) {
            // get last character
            $last = substr($destination, -1);
            // add a trailing slash if missing
            if ($last == '/' || $last == '\\') {
                $this->destination = $destination;
            } else {
                $this->destination = $destination . DIRECTORY_SEPARATOR;
            }
        } else {
            $this->messages[] = "Cannot write to $destination.";
        }
    }

    public function setMaxSize($size) {
        if (is_numeric($size)) {
            $this->maxSize = abs($size);
        }
    }

    public function setSuffix($suffix) {
        if (preg_match('/^\w+$/', $suffix)) {
            if (strpos($suffix, '_') !== 0) {
                $this->suffix = '_' . $suffix;
            } else {
                $this->suffix = $suffix;
            }
        } else {
            $this->suffix = '';
        }
    }

    public function create() {
        if ($this->canProcess && $this->originalwidth != 0) {
            $this->calculateSize($this->originalwidth, $this->originalheight);
            $this->createThumbnail();
        } elseif ($this->originalwidth == 0) {
            $this->messages[] = 'Cannot determine size of ' . $this->original;
        }
    }

    public function getMessages() {
        return $this->messages;
    }

    /*public function test() {
        echo 'File: ' . $this->original . '<br>';
        echo 'Original width: ' . $this->originalwidth . '<br>';
        echo 'Original height: ' . $this->originalheight . '<br>';
        echo 'Base name: ' . $this->basename . '<br>';
        echo 'Image type: ' . $this->imageType . '<br>';
        echo 'Destination: ' . $this->destination . '<br>';
        echo 'Max size: ' . $this->maxSize .  '<br>';
        echo 'Suffix: ' . $this->suffix .  '<br>';
        echo 'Thumb width: ' . $this->thumbwidth . '<br>';
        echo 'Thumb height: ' . $this->thumbheight . '<br>';
        if ($this->messages) {
            print_r($this->messages);
        }
    }*/


    protected function checkType($mime) {
        $mimetypes = array('image/jpeg', 'image/png', 'image/gif');
        if (in_array($mime, $mimetypes)) {
            $this->canProcess = true;
            // extract the characters after 'image/'
            $this->imageType = substr($mime, 6);
        }
    }

    protected function calculateSize($width, $height) {
        if ($width <= $this->maxSize && $height <= $this->maxSize) {
            $ratio = 1;
        } elseif ($width > $height) {
            $ratio = $this->maxSize/$width;
        } else {
            $ratio = $this->maxSize/$height;
        }
        $this->thumbwidth = round($width * $ratio);
        $this->thumbheight = round($height * $ratio);
    }

    protected function createImageResource() {
        if ($this->imageType == 'jpeg') {
            return imagecreatefromjpeg($this->original);
        } elseif ($this->imageType == 'png') {
            return imagecreatefrompng($this->original);
        } elseif ($this->imageType == 'gif') {
            return imagecreatefromgif($this->original);
        }
    }

    protected function createThumbnail() {
        $resource = $this->createImageResource();
        $thumb = imagecreatetruecolor($this->thumbwidth, $this->thumbheight);
        imagecopyresampled($thumb, $resource, 0, 0, 0, 0, $this->thumbwidth,
            $this->thumbheight, $this->originalwidth, $this->originalheight);
        $newname = $this->basename . $this->suffix;
        if ($this->imageType == 'jpeg') {
            $newname .= '.jpg';
            $success = imagejpeg($thumb, $this->destination . $newname, 100);
        } elseif ($this->imageType == 'png') {
            $newname .= '.png';
            $success = imagepng($thumb, $this->destination . $newname, 0);
        } elseif ($this->imageType == 'gif') {
            $newname .= '.gif';
            $success = imagegif($thumb, $this->destination . $newname);
        }
        if ($success) {
            $this->messages[] = "$newname created successfully.";
            $this->new_thumbnails[] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->destination . $newname);
        } else {
            $this->messages[] = "Couldn't create a thumbnail for " .
                basename($this->original);
        }
        imagedestroy($resource);
        imagedestroy($thumb);
    }

}
