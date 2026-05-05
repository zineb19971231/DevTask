<?php

namespace App\Models;
use App\Models\Task;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
public function tasks (){
   return $this ->hasMany(Task::class);
}

public function users(){
     return $this ->belongsToMany(User::class);
}





}
