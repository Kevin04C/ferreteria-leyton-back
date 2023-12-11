<?php

namespace App\services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{

  public static function uploadImage(UploadedFile $file)
  {
    try {
      $result = Cloudinary::upload($file->getRealPath(), [
        'folder' => 'ferreteria-leyton/products'
      ]);

      return [
        'url' => $result->getSecurePath(),
        'public_id' => $result->getPublicId()
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public static function deleteImage($public_id)
  {
    try {
      $result = Cloudinary::destroy($public_id);
      return $result;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function uploadPDF($file)
  {
    try {
      file_put_contents(public_path('pdfs/sale.pdf'), $file);

      $result = Cloudinary::upload(public_path('pdfs/sale.pdf'), [
        'folder' => 'ferreteria-leyton/pdf'
      ]);

      unlink(public_path('pdfs/sale.pdf'));
      return [
        'url' => $result->getSecurePath(),
        'public_id' => $result->getPublicId()
      ];

    } catch (\Throwable $th) {
      throw $th;
    }
  }
}