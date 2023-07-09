<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'due',
        'completed'];

    public $errors, $changed;
    protected $appends = [ 'changed', 'errors'];
    public function getChangedAttribute()
    {
        return $this->changed ?? false;
    }
    public function getErrorsAttribute()
    {
        return $this->errors ?? null;
    }
}
