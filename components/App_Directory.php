<?php


class App_Directory
{
    /**
     * Удалить директорию
     * @param string $dir - директория
     */
    public function removeDirectory($dir) {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }
}