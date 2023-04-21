<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $with = ['children'];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    public function getChildrenAttribute()
    {
        return $this->children()->with('children');
    }
}
