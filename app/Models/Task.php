<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $timestamps = false;
    protected $appends = [ 'changed'];
    protected $fillable = [
        'name',
        'description',
        'due',
        'completed'];

    public function getChangedAttribute()
    {
        return false;
    }
}
