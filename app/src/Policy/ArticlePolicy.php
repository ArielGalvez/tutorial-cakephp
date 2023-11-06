<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;

/**
 * Article policy
 *
 * @method bool isAuthor(\Authorization\IdentityInterface $user, \App\Model\Entity\Article $article)
 */
class ArticlePolicy
{
    /**
     * Check if $user can add Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article The article to be checked
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Article $article)
    {
        // All logged in users can create articles.
        return true;
    }

    /**
     * Check if $user can edit Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article The article to be checked
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Article $article)
    {
        // logged in users can edit their own articles.
        return $this->isAuthor($user, $article);
    }

    /**
     * Check if $user can delete Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article The article to be checked
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Article $article)
    {
        // logged in users can delete their own articles.
        return $this->isAuthor($user, $article);
    }

    /**
     * Checks if the current user is the author of the article.
     *
     * This method compares the user's ID of the article with the ID of the current user
     * to determine if the user is the author of the article.
     *
     * @param \Authorization\IdentityInterface $user The current user.
     * @param \App\Model\Entity\Article $article The article to be checked.
     * @return bool True if the user is the author of the article, false otherwise.
     */
    protected function isAuthor(IdentityInterface $user, Article $article)
    {
        return $article->user_id === $user->getIdentifier();
    }
}
