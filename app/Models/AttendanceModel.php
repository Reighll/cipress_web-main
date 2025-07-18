<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendance';
    protected $primaryKey       = 'attendance_id';
    protected $allowedFields    = ['staff_id', 'clock_in', 'clock_out'];
}