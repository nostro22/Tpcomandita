<?php


class UploadManager{


    private $_DIR_TO_SAVE;
    private $_fileExtension;
    private $_newFileName;
    private $_pathToSaveImage;

    
    public function __construct($dirToSave, $object, $array)
    {
        self::createDirIfNotExists($dirToSave);
        $this->setDirectoryToSave($dirToSave);
        $this->saveFileIntoDir($object, $array);
    }
    
    
    public function setDirectoryToSave($dirToSave){
        $this->_DIR_TO_SAVE = $dirToSave;
    }

   
    public function setFileExtension($fileExtension = 'png'){
        $this->_fileExtension = $fileExtension;
    }

   
    public function setNewFileName($newFileName){
        $this->_newFileName = $newFileName;
    }

    
    public function setPathToSaveImage(){
        $this->_pathToSaveImage = $this->getDirectoryToSave().$this->getNewFileName().'.'.$this->getFileExtension();
    }
    
    
    public function getFileExtension(){
        return $this->_fileExtension;
    }

    
    public function getNewFileName(){
        return $this->_newFileName;
    }

    
    public function getPathToSaveImage(){
        return $this->_pathToSaveImage;
    }

    
    public function getDirectoryToSave(){
        return $this->_DIR_TO_SAVE;
    }

    
    public static function getImageName($obj){
        $fullpath = "Orden_".$obj->id.'.png';
        var_dump($fullpath);
        return $fullpath;
    }

   
    private static function createDirIfNotExists($dirToSave){
        if (!file_exists($dirToSave)) {
            mkdir($dirToSave, 0777, true);
        }
    }

    
    public function saveFileIntoDir($obj, $array):bool{
        $success = false;
        try {                        
            //Aca asigno el nombre de mi imagen
            $this->setNewFileName("Orden_".$obj);            
            $this->setFileExtension();
            $this->setPathToSaveImage();
            if ($this->moveUploadedFile($array['imagen']['tmp_name'])) {
                $success = true;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }finally{
            return $success;
        }
    }

    
    public function moveUploadedFile($tmpFileName){
        return move_uploaded_file($tmpFileName, $this->getPathToSaveImage());
    }

    
    public static function moveImageFromTo($oldDir, $newDir, $fileName){
        self::createDirIfNotExists($newDir);
        return rename($oldDir.$fileName, $newDir.$fileName);
    }
}