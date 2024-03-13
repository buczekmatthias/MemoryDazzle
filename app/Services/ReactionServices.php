<?php

namespace App\Services;

use App\Models\Post;

class ReactionServices
{
    public static function addReaction(Post $post, array $data): void
    {
        if (!$post->reactions()->with('user')->where([['user_id', auth()->user()->id], ['reaction_name', $data['reaction_name']]])->exists()) {
            $reaction = $post->reactions()->create($data);
            $reaction->user()->associate(auth()->user())->save();
        }
    }

    public static function getPostReactions(Post $post): array
    {
        $reactions = $post->getPostReactions();
        $reactionGroups = $post->getPostReactionGroups();

        foreach ($reactionGroups as &$group) {
            foreach ($reactions[$group['reaction_name']] as $r) {
                $group['users'][] = array_slice($r['user'], 1);
            }
        }

        return $reactionGroups;
    }
}
