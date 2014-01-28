<?php
function folderToZip($folder,&$zipFile)
{
    $dirIter = new RecursiveDirectoryIterator($folder);
    $iter = new RecursiveIteratorIterator($dirIter);
    
    foreach($iter as $element) {
        /* @var $element SplFileInfo */
        $dir = str_replace($folder, '', $element->getPath()) . '/';
        if ($element->isDir()) {
            // Ordner erstellen (damit werden auch leere Ordner hinzugefügt
            $zipFile->addEmptyDir($dir);
        } elseif ($element->isFile()) {
            $file         = $element->getPath() .
                            '/' . $element->getFilename();
            $fileInArchiv = $dir . $element->getFilename();
            // Datei dem Archiv hinzufügen
            $zipFile->addFile($file, $fileInArchiv);
        }
    }
}
?>
