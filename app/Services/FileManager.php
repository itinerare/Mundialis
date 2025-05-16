<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileManager extends Service {
    /*
    |--------------------------------------------------------------------------
    | File Manager
    |--------------------------------------------------------------------------
    |
    | Handles uploading and manipulation of files.
    |
    */

    /**
     * Creates a directory.
     *
     * @param string $dir
     *
     * @return bool
     */
    public function createDirectory($dir) {
        if (Storage::directoryExists($dir)) {
            $this->setError('error', 'Folder already exists.');
        } else {
            // Create the directory.
            if (!Storage::createDirectory($dir)) {
                $this->setError('error', 'Failed to create folder.');

                return false;
            }
        }

        return true;
    }

    /**
     * Deletes a directory if it exists and doesn't contain files.
     *
     * @param string $dir
     *
     * @return bool
     */
    public function deleteDirectory($dir) {
        if (!Storage::directoryExists($dir)) {
            $this->setError('error', 'Directory does not exist.');

            return false;
        }
        if (count(Storage::allFiles($dir))) {
            $this->setError('error', 'Cannot delete a folder that contains files.');

            return false;
        }
        Storage::deleteDirectory($dir);

        return true;
    }

    /**
     * Renames a directory.
     *
     * @param string $dir
     * @param string $oldName
     * @param string $newName
     *
     * @return bool
     */
    public function renameDirectory($dir, $oldName, $newName) {
        if (!Storage::directoryExists($dir.'/'.$oldName)) {
            $this->setError('error', 'Directory does not exist.');

            return false;
        }

        if (count(Storage::allFiles($dir.'/'.$oldName))) {
            $this->setError('error', 'Cannot delete a folder that contains files.');

            return false;
        }
        Storage::move($dir.'/'.$oldName, $dir.'/'.$newName);

        return true;
    }

    /**
     * Uploads a file.
     *
     * @param array  $file
     * @param string $dir
     * @param string $name
     * @param bool   $isFileManager
     *
     * @return bool
     */
    public function uploadFile($file, $dir, $name, $isFileManager = true) {
        $directory = $isFileManager ? '/files'.($dir ? '/'.$dir : '') : '/images';
        if (!Storage::directoryExists($directory)) {
            $this->setError('error', 'Folder does not exist.');
        }
        Storage::putFileAs($directory, $file, $name);

        return true;
    }

    /**
     * Uploads a custom CSS file.
     *
     * @param array $file
     *
     * @return bool
     */
    public function uploadCss($file) {
        Storage::put('/css/custom.css', $file);

        return true;
    }

    /**
     * Deletes a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function deleteFile($path) {
        if (!Storage::fileExists($path)) {
            $this->setError('error', 'File does not exist.');

            return false;
        }
        Storage::deleteFile($path);

        return true;
    }

    /**
     * Moves a file.
     *
     * @param string $oldDir
     * @param string $newDir
     * @param string $name
     *
     * @return bool
     */
    public function moveFile($oldDir, $newDir, $name) {
        if (!Storage::fileExists($oldDir.'/'.$name)) {
            $this->setError('error', 'File does not exist.');

            return false;
        } elseif (!Storage::directoryExists($newDir)) {
            $this->setError('error', 'Destination does not exist.');

            return false;
        }
        Storage::move($oldDir.'/'.$name, $newDir.'/'.$name);

        return true;
    }

    /**
     * Renames a file.
     *
     * @param string $dir
     * @param string $oldName
     * @param string $newName
     *
     * @return bool
     */
    public function renameFile($dir, $oldName, $newName) {
        if (!Storage::fileExists($dir.'/'.$oldName)) {
            $this->setError('error', 'File does not exist.');

            return false;
        }
        Storage::move($dir.'/'.$oldName, $dir.'/'.$newName);

        return true;
    }
}
