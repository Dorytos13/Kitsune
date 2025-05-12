<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    protected $fillable = ['text', 'chapter_id', 'next_chapter_id'];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the next chapter for the current choice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nextChapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'next_chapter_id');
    }
}
