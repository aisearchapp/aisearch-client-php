<?php
declare(strict_types=1);

namespace AisearchClient\controllers;

use AisearchClient\Controller;
use AisearchClient\controllers\SearchRecentQuery\DeleteAction;

/**
 * Class SearchRecentQueryController
 *
 * This controller manages operations related to recent search queries.
 * It allows setting the search parameters (user ID and query) and provides a method
 * to delete a recent search query via the DeleteAction.
 */
class SearchRecentQueryController extends Controller
{
    /**
     * The user identifier associated with the recent search query.
     *
     * @var string
     */
    public string $user_id;

    /**
     * The search query string that was performed.
     *
     * @var string
     */
    public string $query;

    /**
     * Gets the user ID.
     *
     * @return string The user ID.
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * Sets the user ID.
     *
     * @param string $user_id The user ID.
     */
    public function setUserId(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * Gets the search query.
     *
     * @return string The search query.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Sets the search query.
     *
     * @param string $query The search query.
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    /**
     * Deletes a recent search query.
     *
     * This method instantiates a DeleteAction, sets its required parameters (user_id and query),
     * and then executes the deletion.
     *
     * @return bool Returns true if the deletion was successful, otherwise false.
     */
    public function delete(): bool
    {
        $action = new DeleteAction($this);
        $action->user_id = $this->user_id;
        $action->query = $this->query;
        return $action->delete();
    }
}
