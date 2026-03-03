<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 
        'price', 'stock_quantity', 'is_active' , 'image'];

    // علاقة المنتج بالقسم
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    protected $appends = ['image_url'];


    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}