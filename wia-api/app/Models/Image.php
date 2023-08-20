<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'src',
        'description'
    ];

    /**
     * Generates a hash code from the image url
     * @param string $imageUrl
     * @return string
     */
    public function generateImageId(string $imageUrl): string
    {
        return sha1($imageUrl);
    }
}
