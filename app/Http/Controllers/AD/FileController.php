<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Check File by path . If it exist  return file
     * @param {string} $path
     *
     * @return File|Boolean
     */
    public function checkFile($path)
    {
        $pathStorage = Storage::exists($path);
        if ($pathStorage) {
            $pathFile = Storage::get($path);
            $mimeType = Storage::mimeType($path);
            return Response::make($pathFile, 200)->header(
                'Content-Type',
                $mimeType
            );
        } else {
            return false;
        }
    }

    /**
     * get file
     * @param Request $request
     *
     * return JSON [{message,path}]
     */
    public function getFile($folder = null, $path = null, $name = null)
    {
        if ($folder == null || $path == null) {
            return response()->json(
                [
                    'message' => 'File does not exist',
                ],
                400
            );
        } else {
            if ($name == null) {
                $pathFile = $this->checkFile($folder . '/' . $path);
            } else {
                $pathFile = $this->checkFile(
                    $folder . '/' . $path . '/' . $name
                );
            }
            if ($pathFile) {
                return $pathFile;
            } else {
                return response()->json(
                    [
                        'message' => 'File does not exist',
                    ],
                    400
                );
            }
        }
    }
}
