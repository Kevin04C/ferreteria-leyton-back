<?php

namespace App\Exceptions;

use Exception;

class ErrorResponseHtpp extends Exception
{
  protected $statusCode;
  protected $messageArray;

  public function __construct(int $statusCode = 500, array $messageArray = [])
  {
    // parent::__construct(implode("\n", $messageArray));
    $this->statusCode = $statusCode;
    $this->messageArray = $messageArray;
  }

  public function getMessageArray()
  {
    return $this->messageArray;
  }

  public function render($request)
  {
    $errorsArray = count($this->getMessageArray()) <= 0 ? ['Error en la solicitud'] : $this->getMessageArray();

    return response()->json([
      'type' => 'error',
      'messages' => $errorsArray,
      'data' => []
    ], $this->statusCode);
  }
}