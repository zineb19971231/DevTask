<?php

namespace App\Models;
use App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Projet extends Model
{
     use SoftDeletes;

 protected $fillable = [
        'titre',
        'description',
        'deadline',
        'est_archive'
    ];
    
     protected $casts = [
       
        'est_archive' => 'boolean'
    ];


public function tasks (){
   return $this ->hasMany(Task::class);
}

   public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

  protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst($value)
        );
    }

    public function isLead(User $user): bool
    {
        return $this->members()
                    ->wherePivot('user_id', $user->id)
                    ->wherePivot('role', 'lead')
                    ->exists();
    }

}
