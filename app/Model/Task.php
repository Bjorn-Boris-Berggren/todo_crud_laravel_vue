<?php

namespace App\Model;

/**
 * Task.php 
 * The model that manages the tasks for the database.
 *
 * @author Björn Berggren 
 */

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** 
     * @var $fillable['title'] string 
     * @var $fillable['done'] string 
     * @var $fillable['created_at'] string 
     * */
    protected $fillable = ['title', 'done', 'created_at'];
}
