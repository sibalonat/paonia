<?php
namespace App\Models;

use Conner\Likeable\Likeable;
use Illuminate\Database\Eloquent\Collection;

class CommentCollection extends Collection
{
    // use Likeable;
    public function threaded()
    {
        $comments = parent::groupBy('parent_id');

        if (count($comments)) {

            $comments['root'] = $comments[''];
            unset($comments['']);
        }

        return $comments;
    }
}