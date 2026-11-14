<?php

namespace App\Models;

use JiJiHoHoCoCo\IchiORM\Database\Model;

class Todo extends Model
{
    public $id;
    public $title;
    public $description;
    public $is_completed;
    public $created_at;
    public $updated_at;
}
