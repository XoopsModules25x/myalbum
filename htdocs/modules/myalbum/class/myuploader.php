<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <https://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, https://xoops.org/, http://jp.xoops.org/ //
// Project: XOOPS Project                                                    //
// ------------------------------------------------------------------------- //

// myuploader.php,v 1.12+
// Security & Bug fixed version of class XoopsMediaUploader by GIJOE

/*!
Example

  include_once __DIR__ . '/myuploader.php';
  $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
  $maxfilesize = 50000;
  $maxfilewidth = 120;
  $maxfileheight = 120;
  $uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight, $allowed_exts);
  if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
    if (!$uploader->upload()) {
       echo $uploader->getErrors();
    } else {
       echo '<h4>File uploaded successfully!</h4>'
       echo 'Saved as: ' . $uploader->getSavedFileName() . '<br>';
       echo 'Full path: ' . $uploader->getSavedDestination();
    }
  } else {
    echo $uploader->getErrors();
  }

*/

/**
 * Class MyXoopsMediaUploader
 */
class MyXoopsMediaUploader
{
    public $mediaName;
    public $mediaType;
    public $mediaSize;
    public $mediaTmpName;
    public $mediaError;

    public $uploadDir = '';

    public $allowedMimeTypes  = array();
    public $allowedExtensions = array();

    public $maxFileSize = 0;
    public $maxWidth;
    public $maxHeight;

    public $targetFileName;

    public $prefix;

    public $errors = array();

    public $savedDestination;

    public $savedFileName;

    /**
     * Constructor
     *
     * @param string $uploadDir
     * @param array  $allowedMimeTypes
     * @param int    $maxFileSize
     * @param int    $maxWidth
     * @param int    $maxHeight
     * @param array  $allowedExtensions
     *
     * @internal param int $cmodvalue
     */
    public function __construct(
        $uploadDir,
        $allowedMimeTypes,
        $maxFileSize,
        $maxWidth = null,
        $maxHeight = null,
        $allowedExtensions = null
    ) {
        if (is_array($allowedMimeTypes)) {
            $this->allowedMimeTypes =& $allowedMimeTypes;
        }
        $this->uploadDir   = $uploadDir;
        $this->maxFileSize = (int)$maxFileSize;
        if (isset($maxWidth)) {
            $this->maxWidth = (int)$maxWidth;
        }
        if (isset($maxHeight)) {
            $this->maxHeight = (int)$maxHeight;
        }
        if (isset($allowedExtensions) && is_array($allowedExtensions)) {
            $this->allowedExtensions =& $allowedExtensions;
        }
    }

    /**
     * Fetch the uploaded file
     *
     * @param string $media_name Name of the file field
     * @param int    $index      Index of the file (if more than one uploaded under that name)
     *
     * @return bool
     **/
    public function fetchMedia($media_name, $index = null)
    {
        if (!isset($_FILES[$media_name])) {
            $this->setErrors('File not found');

            return false;
        } elseif (is_array($_FILES[$media_name]['name']) && isset($index)) {
            $index              = (int)$index;
            $this->mediaName    = $_FILES[$media_name]['name'][$index];
            $this->mediaType    = $_FILES[$media_name]['type'][$index];
            $this->mediaSize    = $_FILES[$media_name]['size'][$index];
            $this->mediaTmpName = $_FILES[$media_name]['tmp_name'][$index];
            $this->mediaError   = !empty($_FILES[$media_name]['error'][$index]) ? $_FILES[$media_name]['errir'][$index] : 0;
        } else {
            $media_name         =& $_FILES[$media_name];
            $this->mediaName    = $media_name['name'];
            $this->mediaType    = $media_name['type'];
            $this->mediaSize    = $media_name['size'];
            $this->mediaTmpName = $media_name['tmp_name'];
            $this->mediaError   = !empty($media_name['error']) ? $media_name['error'] : 0;
        }
        $this->errors = array();
        if ((int)$this->mediaSize < 0) {
            $this->setErrors('Invalid File Size');

            return false;
        }
        if ($this->mediaName == '') {
            $this->setErrors('Filename Is Empty');

            return false;
        }
        if ($this->mediaTmpName === 'none' || !is_uploaded_file($this->mediaTmpName) || $this->mediaSize == 0) {
            $this->setErrors('No file uploaded');

            return false;
        }
        if ($this->mediaError > 0) {
            $this->setErrors('Error occurred: Error #' . $this->mediaError);

            return false;
        }

        return true;
    }

    /**
     * Set the target filename
     *
     * @param string $value
     **/
    public function setTargetFileName($value)
    {
        $this->targetFileName = (string)trim($value);
    }

    /**
     * Set the prefix
     *
     * @param string $value
     **/
    public function setPrefix($value)
    {
        $this->prefix = (string)trim($value);
    }

    /**
     * Get the uploaded filename
     *
     * @return string
     **/
    public function getMediaName()
    {
        return $this->mediaName;
    }

    /**
     * Get the type of the uploaded file
     *
     * @return string
     **/
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Get the size of the uploaded file
     *
     * @return int
     **/
    public function getMediaSize()
    {
        return $this->mediaSize;
    }

    /**
     * Get the temporary name that the uploaded file was stored under
     *
     * @return string
     **/
    public function getMediaTmpName()
    {
        return $this->mediaTmpName;
    }

    /**
     * Get the saved filename
     *
     * @return string
     **/
    public function getSavedFileName()
    {
        return $this->savedFileName;
    }

    /**
     * Get the destination the file is saved to
     *
     * @return string
     **/
    public function getSavedDestination()
    {
        return $this->savedDestination;
    }

    /**
     * Check the file and copy it to the destination
     *
     * @param int $chmod
     *
     * @return bool
     */
    public function upload($chmod = 0644)
    {
        if ($this->uploadDir == '') {
            $this->setErrors('Upload directory not set');

            return false;
        }
        if (!is_dir($this->uploadDir)) {
            $this->setErrors('Failed opening directory: ' . $this->uploadDir);

            return false;
        }
        if (!is_writable($this->uploadDir)) {
            $this->setErrors('Failed opening directory with write permission: ' . $this->uploadDir);

            return false;
        }
        if (!$this->checkMimeType()) {
            $this->setErrors('MIME type not allowed: ' . $this->mediaType);

            return false;
        }
        if (!$this->checkExtension()) {
            $this->setErrors('Extension not allowed');

            return false;
        }
        if (!$this->checkMaxFileSize()) {
            $this->setErrors('File size too large: ' . $this->mediaSize);
        }
        if (!$this->checkMaxWidth()) {
            $this->setErrors(sprintf('File width must be smaller than %u', $this->maxWidth));
        }
        if (!$this->checkMaxHeight()) {
            $this->setErrors(sprintf('File height must be smaller than %u', $this->maxHeight));
        }
        if (count($this->errors) > 0) {
            return false;
        }
        if (!$this->copyFile($chmod)) {
            $this->setErrors('Failed uploading file: ' . $this->mediaName);

            return false;
        }

        return true;
    }

    /**
     * Copy the file to its destination
     *
     * @param $chmod
     *
     * @return bool
     */
    public function copyFile($chmod)
    {
        $matched = array();
        if (!preg_match("/\.([a-zA-Z0-9]+)$/", $this->mediaName, $matched)) {
            return false;
        }
        if (isset($this->targetFileName)) {
            $this->savedFileName = $this->targetFileName;
        } elseif (isset($this->prefix)) {
            $this->savedFileName = uniqid($this->prefix) . '.' . strtolower($matched[1]);
        } else {
            $this->savedFileName = strtolower($this->mediaName);
        }
        $this->savedDestination = $this->uploadDir . '/' . $this->savedFileName;
        if (!move_uploaded_file($this->mediaTmpName, $this->savedDestination)) {
            return false;
        }
        @chmod($this->savedDestination, $chmod);

        return true;
    }

    /**
     * Is the file the right size?
     *
     * @return bool
     **/
    public function checkMaxFileSize()
    {
        if ($this->mediaSize > $this->maxFileSize) {
            return false;
        }

        return true;
    }

    /**
     * Is the picture the right width?
     *
     * @return bool
     **/
    public function checkMaxWidth()
    {
        if (!isset($this->maxWidth)) {
            return true;
        }
        if (false !== $dimension = getimagesize($this->mediaTmpName)) {
            if ($dimension[0] > $this->maxWidth) {
                return false;
            }
        } else {
            trigger_error(sprintf('Failed fetching image size of %s, skipping max width check..', $this->mediaTmpName), E_USER_WARNING);
        }

        return true;
    }

    /**
     * Is the picture the right height?
     *
     * @return bool
     **/
    public function checkMaxHeight()
    {
        if (!isset($this->maxHeight)) {
            return true;
        }
        if (false !== $dimension = getimagesize($this->mediaTmpName)) {
            if ($dimension[1] > $this->maxHeight) {
                return false;
            }
        } else {
            trigger_error(sprintf('Failed fetching image size of %s, skipping max height check..', $this->mediaTmpName), E_USER_WARNING);
        }

        return true;
    }

    /**
     * Is the file the right Mime type
     *
     * (is there a right type of mime? ;-)
     *
     * @return bool
     **/
    public function checkMimeType()
    {
        if (count($this->allowedMimeTypes) > 0 && !in_array($this->mediaType, $this->allowedMimeTypes)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Is the file the right extension
     *
     * @return bool
     **/
    public function checkExtension()
    {
        $ext = substr(strrchr($this->mediaName, '.'), 1);
        if (!empty($this->allowedExtensions) && !in_array(strtolower($ext), $this->allowedExtensions)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Add an error
     *
     * @param string $error
     **/
    public function setErrors($error)
    {
        $this->errors[] = trim($error);
    }

    /**
     * Get generated errors
     *
     * @param bool $ashtml Format using HTML?
     *
     * @return array|string Array of array messages OR HTML string
     */
    public function &getErrors($ashtml = true)
    {
        if (!$ashtml) {
            return $this->errors;
        } else {
            $ret = '';
            if (count($this->errors) > 0) {
                $ret = '<h4>Errors Returned While Uploading</h4>';
                foreach ($this->errors as $error) {
                    $ret .= $error . '<br>';
                }
            }

            return $ret;
        }
    }
}
