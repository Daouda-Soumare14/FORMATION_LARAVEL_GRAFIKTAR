<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'image',
    ];

    public function category() {
        return $this->belongsTo(Category::class); // belongsTo permet de dire que ce model appartient a une category
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function imageUrl() : string
    {
        return Storage::url($this->image);
        // return asset('storage/' . $this->image);

    }
}
