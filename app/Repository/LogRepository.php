<?php

namespace App\Repository;

use App\Repository\BaseRepo;
use Illuminate\Support\Facades\Log;

class LogRepository extends BaseRepo
{


  public function getModel()
  {
    return Log::class;
  }


  
}
