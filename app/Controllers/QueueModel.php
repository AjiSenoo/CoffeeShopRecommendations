<?php

namespace App\Models;

use CodeIgniter\Model;

class QueueModel extends Model
{
    protected $table = 'branches'; // Use your existing 'branches' table
    protected $primaryKey = 'id';
    protected $allowedFields = ['queue_length'];
}