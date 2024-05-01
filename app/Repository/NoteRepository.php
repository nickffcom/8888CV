<?php

namespace App\Repository;

use App\Models\Note;
use App\Repository\BaseRepo;
use Illuminate\Support\Facades\Log;

class NoteRepository extends BaseRepo
{

  public function getModel()
  {
    return Note::class;
  }
}
