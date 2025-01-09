<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'branch_reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = ['branch_id', 'customer_id', 'rating', 'review', 'created_at'];
}
