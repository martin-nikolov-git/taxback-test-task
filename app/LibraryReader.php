<?php


namespace App;

use App\Database\IBooksRepository;
use App\General\EnvReader;

/**
 * Class which reads the provided library folder for books.
 */
class LibraryReader
{
    
    /**
     * @var string The library root folder 
     */
    private $rootFolder = null;

    /**
     * @var App\Database\IBooksRepository A repository for adding/updating books
     */
    private $bookRepository = null;

    /**
     * @var array List of valid extensions
     */
    private $validExtensions = ['xml'];

    /**
     * @var string The timezone used when getting the current time
     */
    private $timezone = null;

    public function __construct(string $rootFolder, IBooksRepository $connection)
    {
        $this->rootFolder = $rootFolder;
        $this->bookRepository = $connection;

        $timezone = EnvReader::get('APP_DEFAULT_TIMEZONE');
        $this->timezone = new \DateTimeZone($timezone);
    }

    public function readFolder()
    {
        $directory = new \RecursiveDirectoryIterator($this->rootFolder);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach($iterator as $file) {
            $this->handleFile($file);
        }
    }

    private function handleFile(\SplFileInfo $fileInfo)
    {
        if(in_array($fileInfo->getFilename(), ['.','..'])) {
            return;
        }

        echo "\nHandling: {$fileInfo->getPathname()}: ";
        if(!in_array($fileInfo->getExtension(), $this->validExtensions)) {
            //The \e[0;31 and other flags are used to set color for outputs
            echo "\e[0;31mInvalid Extension {$fileInfo->getExtension()}!\e[0m";
            return;
        }

        $xmlFile = simplexml_load_file($fileInfo->getPathname());
        $this->handleXML($xmlFile);
    }

    private function handleXML(\SimpleXMLElement $XMLElement)
    {
        if($XMLElement->getName() !== 'books') {
            echo "\e[0;31m Element has no books root element!\e[0m";
            return;
        }


        foreach($XMLElement->children() as $book) {
            if($book->getName() !== 'book') {
                echo "\e[0;31m Can't handle \"{$book->getName()}\" element.\e[0m";
                continue;
            }

            $author = (string) $book->author;
            $name = (string) $book->name;

            if(empty($author) || empty($name)) {
                echo "\n\e[0;31m Book has invalid empty/missing values.\e[0m";
                continue;
            }

            echo "\nBook \"$name\" by \"$author\" ";

            $result = $this->bookRepository->exists($name, $author);

            try {
                if($result instanceof Book) {
                    $result->updated_at = new \DateTime(null, $this->timezone);
                    $this->bookRepository->update($result);
                    $time_of_update = $result->updated_at->format('Y-m-d H:i:s.u');
                    echo "\e[1;33m Updating with time \"{$time_of_update}\".\e[0m";
                    continue;
                }
                $this->bookRepository->insert($name, $author);
                echo "\e[0;32m Adding new book.\e[0m";
            } catch(\Exception $exception) {
                echo "\e[0;31m An error occurred while adding the book:\e[0m";
                echo "\n" . $exception->getMessage();
            }
        }
    }
}