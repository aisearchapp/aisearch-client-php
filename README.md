# Aisearch SDK Integration Guide

## Introduction

The **Aisearch SDK** is a PHP library that provides a simple interface to integrate Aisearch’s AI-powered search and recommendation services into your website or application. It allows developers to perform product searches with advanced features (like filtering, sorting, and pagination), retrieve personalized recommendations, and manage user search history. By using the SDK, you can enhance your e-commerce or web application with intelligent search capabilities and AI-driven product discovery without dealing directly with low-level API calls.

In this guide, we will cover how to install and set up the Aisearch SDK, initialize it with your credentials, and use its various components. You’ll learn how to fetch site-specific settings, execute search queries (with examples of filtering, pagination, and sorting), utilize recommendation and “discover” features for personalized suggestions, manage recent search histories, and follow best practices for error handling. Code snippets are provided throughout to illustrate how to implement each feature.

## Installation

Integrating the Aisearch SDK into your project is straightforward. You can install it via Composer or manually include the SDK in your codebase:

- **Via Composer**: If the SDK is available on Packagist, you can add it to your project by running the command (replace `<version>` with the appropriate version if needed):
  ```bash
  composer require aisearchapp/aisearch-client-php
  ```  
  After installation, make sure to include Composer’s autoloader in your PHP script:
  ```php
  require 'vendor/autoload.php';
  ```
  This will allow you to use the Aisearch classes directly via namespaces.

- **Manual Download**: Download the Aisearch SDK (as a ZIP or via the source repository) and include it in your project. For example, if you have extracted the SDK into a directory `aisearch-client-php`, you can include the main file and utilize its classes:
  ```php
  require_once 'path/to/aisearch-client-php/src/Aisearch.php';  // Adjust the path as needed

  ```
  Make sure all SDK files are accessible (maintain the directory structure as provided). This approach requires that you manage the loading of SDK classes (either via manual `require_once` calls or configuring an autoloader following PSR-4 for the `AisearchClient` namespace).

**Requirements**: The Aisearch SDK uses cURL for making HTTP requests. Ensure that the PHP cURL extension is enabled on your server. The SDK is designed for PHP 7+ (uses strict typing and namespaces), so use a compatible PHP version.

## Initialization

Before using any Aisearch features, you need to initialize the SDK with your **Site ID** and **Client Token** (API key). These credentials are provided by Aisearch for your account and uniquely identify your website/application and authorize API requests.

Here's how to initialize the Aisearch client in your code:

```php
use AisearchClient\Aisearch;

// Your Aisearch credentials (replace with actual values)
$siteId = SITE_ID;
$clientToken = "YOUR_CLIENT_TOKEN";

// Initialize the Aisearch SDK client
$sdk = new Aisearch($siteId, $clientToken);
```

When creating the `Aisearch` object, pass your site ID (as an integer) and client token (as a string). This object will serve as the entry point for all subsequent SDK operations. **Keep your client token secure** – do not expose it on the client side (browser or mobile app). Use it only in server-side code.

Once initialized, the `$sdk` instance provides access to various controllers:
- `$sdk->search()` – for search queries and related operations.
- `$sdk->recommendation()` – for product recommendations and discovery features.
- `$sdk->settings()` – to retrieve configuration/settings from Aisearch for your site.
- `$sdk->searchRecentQuery()` – to manage (e.g., delete) recent search queries of a user.

We will explore each of these in the following sections.

## Fetching Settings

Aisearch allows you to fetch site-specific settings or configuration via the SDK. This might include details like supported languages, available filters/attributes for your data, currency settings, and any custom call-to-action (CTA) configurations. Fetching the settings is useful to ensure your application’s search UI aligns with the configuration defined in Aisearch (for example, knowing which filters to display).

To retrieve the settings, use the `settings()` controller from the SDK. For example:

```php
// Fetch site settings via Aisearch SDK
$settingsAction = $sdk->settings()->get();
if ($settingsAction->response->isSuccess()) {
    $settingsModel = $settingsAction->model;
    // You can now use the settings data, for example:
    $isActive = $settingsModel->status;              // bool indicating if Aisearch is active
    $defaultLang = $settingsModel->language_id;      // default language ID or code
    $supportedCurrencies = $settingsModel->currencies; // array of currency settings
    // ... and other available settings such as $settingsModel->cta, $settingsModel->subscription, etc.
} else {
    // Handle error
    error_log("Failed to fetch settings: " . $settingsAction->response->error);
}
```

**Explanation**: We call `$sdk->settings()->get()`. Here:
- `$sdk->settings()` returns an action that is prepared to call the **Settings API** (to get all settings).
- Calling `->get()` on that action initiates the API request and retrieves the data.
- On success, `$settingsAction->model` holds the settings data in a structured object (instance of `SettingsModel`). We then access various properties like `status`, `language_id`, etc., as needed.
- Always check `$settingsAction->response->isSuccess()` to ensure the call succeeded before using the model data. In case of failure, you can inspect `$settingsAction->response->error` for an error message or take appropriate action.

Using the fetched settings, you can configure your application’s UI or logic. For instance, you might load available filter categories or ensure your search interface uses the correct language and currency settings from Aisearch.

## Performing Search Queries

The core feature of Aisearch SDK is executing search queries against your indexed data. Through the SDK, you can perform searches with keywords, paginate through results, and refine results using filters and sorting. This section will walk through a basic search and then explore how to use pagination and filtering/sorting.

### Basic Search Example

To perform a basic search, use the `$sdk->search()->query()` method to create a new search query action. Then specify the search parameters (at minimum, a query string), and execute the search. For example:

```php
// 1. Create a search query action
$searchQuery = $sdk->search()->query();

// 2. Set search parameters
$searchQuery->setQuery("dress");           // the search keyword, e.g., "dress"
$searchQuery->setUserId("user-123");        // an identifier for the user performing the search
$searchQuery->setLimit(30);                // number of results per page (default is 30 if not set)
$searchQuery->setSort(AisearchClient\controllers\Search\QueryAction::SORT_DEFAULT);
// ^ sets sorting (here SORT_DEFAULT means no specific sort, i.e., default relevance order)
$searchQuery->setPage(1);                  // which page of results to fetch (starting from 1)

// 3. Execute the search (fetch the first page of results)
$resultsAction = $searchQuery->first();
if ($resultsAction->response->isSuccess()) {
    $resultModel = $resultsAction->model;  
    echo "Total products found: " . $resultModel->count . "\n";
    // Loop through products in results
    foreach ($resultModel->products as $product) {
        echo $product->name . " - " . $product->price . "\n";
    }
} else {
    echo "Search failed: " . $resultsAction->response->error;
}
```

**What’s happening here?**
1. We obtain a `QueryAction` object by calling `$sdk->search()->query()`.
2. We configure the query:
    - `setQuery("dress")` defines the search term (in this case, “dress”).
    - `setUserId("user-123")` sets an arbitrary user ID. This is important for personalization and analytics – use a unique ID for each user (or session) so Aisearch can tailor recommendations and track search history per user. (It can be a username, user number, session ID, etc.)
    - `setLimit(30)` sets the maximum number of results to retrieve per page. If you expect many results, you can adjust this (e.g., 10, 20, 50, 100, etc., depending on your UI needs).
    - `setSort(QueryAction::SORT_DEFAULT)` uses a predefined constant for sorting. In this case, `SORT_DEFAULT` (an empty string) means the default sort order defined by Aisearch (usually relevance or popularity). The SDK provides other sort constants such as `SORT_PRICE_ASC`, `SORT_PRICE_DESC`, `SORT_NAME_ASC`, `SORT_NAME_DESC`, `SORT_CREATED_AT_ASC`, `SORT_CREATED_AT_DESC` which you can use to sort results by price, name, or creation date (ascending or descending). We’ll see an example of using these later.
    - `setPage(1)` is explicitly setting the page number to 1 (the first page). This is optional if you plan to just call `first()` which defaults to page 1, but it’s shown here for completeness.

3. We execute the search by calling `$searchQuery->first()`. The `first()` method (inherited from the base Action class) will internally make the API request for the first page of results. It returns the same action object, now populated with a `response` and `model`. We store this in `$resultsAction` for clarity.

4. We check `$resultsAction->response->isSuccess()` to ensure the API returned a 200 OK. If successful, `$resultsAction->model` (an instance of `SearchQueryModel`) contains the search results and related data:
    - `$resultModel->count` – the total number of items found for the query across all pages.
    - `$resultModel->products` – an array of products (each item is a `ProductModel` with properties like `name`, `price`, `id`, etc.) representing the current page of results.
    - `$resultModel->page` – a `PageModel` object with pagination info (e.g., which page you’re on, and if there's a next page).
    - `$resultModel->attribute_parents` and `$resultModel->attributes` – arrays containing available filter facets for the results (e.g., categories, brands, or other attributes and their values). These can be used to build dynamic filters on your frontend (more on this in the filtering section).

5. In the example, we output the total count and iterate through the `products` list, printing each product’s name and price. In a real application, you would use this data to render product results in HTML.

If the search fails (e.g., invalid credentials or network issue), `isSuccess()` will be false and `$resultsAction->response->error` will contain a message. Always handle this case (log the error or show a friendly message to the user as appropriate).

### Using Pagination (Fetching Next Page)

When a search query returns more results than one page can show, you can fetch subsequent pages using the `next()` function. The SDK simplifies this by tracking the current page internally and providing `next()` to navigate pages.

Continuing from the previous search example, suppose the total results count was higher than 30 (our page limit). We can retrieve the next page as follows:

```php
// Assuming $resultsAction from previous example holds page 1 results
if ($resultsAction->response->isSuccess()) {
    // ... (handled page 1 as above)
    // Check if a next page exists:
    $nextPageAction = $searchQuery->next();
    if ($nextPageAction !== null && $nextPageAction->response->isSuccess()) {
        $nextPageModel = $nextPageAction->model;
        echo "Page 2 products:\n";
        foreach ($nextPageModel->products as $product) {
            echo "- " . $product->name . "\n";
        }
    }
}
```

**How it works**:  
After calling `first()`, the `$searchQuery` object retains the context of the query (including the page it just fetched). Calling `$searchQuery->next()` will check if there is a next page (`next()` uses the information in the response’s page model to find the next page number). If a next page exists, it updates the query’s page number and performs another API request (similar to calling first, but for page 2). The result is returned as a new action (or the same object updated) which we capture in `$nextPageAction`. We then verify success and access `$nextPageAction->model->products` for page 2 results.

If there are no more pages, `$searchQuery->next()` will return `null` (so in the code above, always check for `!== null` before proceeding). You can loop through pages until `next()` returns null if you want to sequentially get all results:

```php
// Fetch all pages in a loop (caution: could be many results)
$searchAction = $searchQuery->first();
while ($searchAction->response->isSuccess() && ($next = $searchQuery->next()) !== null) {
    $searchAction = $next;
    // Process $searchAction->model->products for this page...
}
```

This approach will iterate through all pages of results. In practice, you’ll likely fetch a few pages as needed (for example, if implementing an “infinite scroll” or “Load more” button on the frontend).

### Applying Filters and Sorting

The Aisearch SDK supports refining search queries by adding filters (such as category, attributes, price range) and specifying sort order for the results. This enables you to build a rich faceted search experience.

**Attribute/Category Filters**:  
Use `addFilterAttribute($parentId, $childId)` to apply a filter. In Aisearch, filters are represented by attribute pairs – a **parent attribute** (e.g., a filter category like "Color" or "Brand", identified by an ID) and a **child attribute** (the specific value like "Red" or "Nike", also identified by an ID). The IDs for attributes and their values can be obtained from the search results or settings. For example, search results (`SearchQueryModel`) contain `attribute_parents` and `attributes` arrays that list available filters and their IDs in the current context.

You can add multiple filters. Each `addFilterAttribute(parent, child)` narrows the results to items that have that attribute value. For example:

```php
// Continuing from the search query setup above...
// Suppose parent ID 5 corresponds to "Category" and child ID 12 corresponds to "Electronics"
$searchQuery->addFilterAttribute(5, 12);  // Filter results to Category=Electronics

// Suppose parent ID 10 corresponds to "Color" and child ID 22 is "Red"
$searchQuery->addFilterAttribute(10, 22); // Additionally filter results to Color=Red

// You can chain multiple filters; results will match ALL applied filters.
```

**Price Filter**:  
For numeric price filtering, use `setFilterMinPrice($min)` and `setFilterMaxPrice($max)` to define a price range. For example:

```php
$searchQuery->setFilterMinPrice(100.00);  // set minimum price to 100 (in your currency units)
$searchQuery->setFilterMaxPrice(500.00);  // set maximum price to 500
```

The above would restrict results to items priced between 100 and 500 inclusive.

**Sorting**:  
As mentioned earlier, you can sort search results using the `setSort()` method with one of the predefined sort constants. The available sorting options include:
- `QueryAction::SORT_DEFAULT` – Default order (as defined by Aisearch, often relevance).
- `QueryAction::SORT_PRICE_ASC` – Price low to high.
- `QueryAction::SORT_PRICE_DESC` – Price high to low.
- `QueryAction::SORT_NAME_ASC` – Name A to Z.
- `QueryAction::SORT_NAME_DESC` – Name Z to A.
- `QueryAction::SORT_CREATED_AT_ASC` – Oldest items first (by creation date).
- `QueryAction::SORT_CREATED_AT_DESC` – Newest items first.

For example, to sort by price ascending:
```php
$searchQuery->setSort(AisearchClient\controllers\Search\QueryAction::SORT_PRICE_ASC);
```
*(Make sure to import or fully qualify the `QueryAction` class to access these constants.)*

**Executing the filtered/sorted query**:  
After setting filters and sort, call `first()` or `get()` to execute the query. For instance:

```php
// After adding filters and sort to $searchQuery as above:
$filteredResultsAction = $searchQuery->first();
if ($filteredResultsAction->response->isSuccess()) {
    $filteredModel = $filteredResultsAction->model;
    echo "Filtered results count: " . $filteredModel->count . "\n";
    // Process products as needed...
}
```

**Removing/Clearing Filters**:  
If you need to remove filters (for example, if a user unchecks a filter), the SDK provides methods:
- `removeFilterAttribute($parentId, $childId)` – removes a specific filter value.
- `removeFilterAllAttribute($parentId = null)` – removes all filters under a specific parent attribute. If you call this with a parent ID, it clears all selections for that filter category. If you call it with no arguments, it clears **all filters** currently applied to the query.

Example:
```php
$searchQuery->removeFilterAttribute(10, 22); // remove the filter Color=Red (parent 10, child 22)
$searchQuery->removeAllFilterAttributes(5);   // remove all filters under Category (parent 5)
$searchQuery->removeAllFilterAttributes();    // clear *all* filters applied to the query
```
After removing filters, you could call `$searchQuery->first()` again (or `get()`) to refresh results based on the updated filter set.

Using these filter and sort functions, you can dynamically adjust the search results based on user input (such as selecting checkboxes for filters or choosing a sort order from a dropdown). Just ensure that you apply the filters to your `QueryAction` **before** executing the query (or re-execute if filters change).

## Recommendations & Discover Features

Beyond basic search, Aisearch provides recommendation features that help users discover products without directly searching by a keyword. The SDK exposes these through the `recommendation()` controller. You can fetch personalized or context-based recommendations for a user, and also use a **“discover” mode** which leverages AI to suggest items (for example, on a homepage or a discovery section of your app).

### Fetching Recommendations for Users

To get product recommendations, use `$sdk->recommendation()` which returns a `RecommendationController`. This controller offers methods such as `carousel()` (and possibly others) to retrieve recommended products. A common use-case is to fetch a set of recommended products for a user – for instance, “Recommended for you” carousels or related product suggestions on a page.

**Basic example (Recommendation Carousel)**:
```php
// Get a recommendation action (carousel recommendations)
$recAction = $sdk->recommendation()->carousel();

// (Optional) Set context for recommendations:
$recAction->setUserId("user-123");       // identify the user to personalize results
$recAction->setCategoryId(5);           // (optional) limit recommendations to a category (ID 5 as context)
#$recAction->setBrandId(10);            // (optional) limit to a specific brand (ID 10) if desired
// Note: You can use Category/Brand context if you want recommendations related to a certain category or brand 
// (for example, on a category page or brand page). You can also omit these to get more general recommendations.

// Execute the recommendation query
$recResultsAction = $recAction->get();
if ($recResultsAction->response->isSuccess()) {
    $recModel = $recResultsAction->model;  // This is RecommendationCarouselModel
    // For example, iterate through recommended products:
    foreach ($recModel->products as $product) {
        echo "Recommended: {$product->name} - {$product->price}\n";
    }
} else {
    error_log("Recommendation fetch failed: " . $recResultsAction->response->error);
}
```

In this snippet:
- We call `$sdk->recommendation()->carousel()` to prepare a recommendation request. “Carousel” here implies a set of items that you might show in a slider or section – it’s a general recommended products endpoint.
- We set a `userId` (like with search, to personalize recommendations to that user’s behavior).
- Optionally, we set `CategoryId` or `BrandId`. This can be useful if the recommendations should be context-specific. For instance, if the user is viewing electronics, you might want the recommended items also within electronics category. If you specify these, the API will consider them as filters for recommendation (only recommend items from that category/brand). If you leave them out, recommendations could be more broad or based purely on user’s overall interest.
- We then call `first()` to retrieve the recommendations. The result (`RecommendationCarouselModel`) will include an array of `products` recommended. We loop through them and print the name and price as an example.

The structure of the recommendation model is similar to search results (it contains `products`, and may also include `attributes` or other info if relevant). Typically, you will use this data to display a “Recommended Products” section.

You can call `setUserId` with the same user ID you use for search, so that Aisearch can correlate user behavior. If you have segmentation data for users or contextual segments (like "interested in sports"), the SDK also allows `setSegments()` and `setNegativeSegments()` on the recommendation actions to fine-tune what kind of recommendations to fetch. (Segments are advanced usage: you can tag a user or context with certain segment keywords, and negative segments to exclude certain categories of recommendations.)

### Using Discover Mode for AI-Driven Suggestions

**Discover** mode is a special feature aimed at helping users find products even when they haven’t specified a query. It leverages AI to suggest items based on user behavior or overall trends. This is ideal for a homepage “Discover” section or when a user wants to browse rather than search for something specific.

To use discover mode, call `$sdk->recommendation()->discover()`. For example:

```php
// AI Discover mode recommendations
$discoverAction = $sdk->recommendation()->discover();
$discoverAction->setUserId("user-123");  // set the user for personalization
$discoverAction->setLimit(20);          // how many suggestions to retrieve (default might be 30 if not set)

// (Optional: you could also set segments or negative segments if using them)
// $discoverAction->setSegments("new-user");  // example segment
// $discoverAction->setNegativeSegments("out-of-stock"); // example exclusion segment

// Execute discover query
$discoverResults = $discoverAction->first();
if ($discoverResults->response->isSuccess()) {
    $discoverModel = $discoverResults->model;  // RecommendationDiscoverModel
    echo "Discover suggestions count: " . $discoverModel->count . "\n";
    foreach ($discoverModel->products as $product) {
        echo "Suggestion: {$product->name}\n";
    }
}
```

Explanation:
- `$sdk->recommendation()->discover()` returns a `DiscoverAction` where you can set parameters. We set the `UserId` (to tailor suggestions to the user).
- We also set a `limit` of 20, assuming we want to show 20 suggested items. If not set, the API may return a default number (commonly 30). Adjust `setLimit` based on how many items you want to display.
- (Optional) As with carousel, you could use `setSegments` to provide any context or user segment that might influence the AI suggestions, and `setNegativeSegments` to avoid certain types of suggestions. For example, you might tag new users differently or exclude categories a user dislikes. These are advanced options and require understanding of how your Aisearch account is configured for segmentation.
- We call `first()` to retrieve the suggestions. On success, we get a `RecommendationDiscoverModel` in `$discoverModel`. We then show the count and iterate through the `products` array to list the suggestions.

The “discover” results are essentially products that the AI thinks the user might be interested in. This could be based on trending items, the user’s past browsing or purchasing history, similar users’ interests, etc., depending on Aisearch’s algorithm. It’s a powerful feature to keep users engaged with personalized content even when they haven’t actively searched for something.

You can use **Discover** on a landing page or a dedicated discovery section. It’s also useful as a fallback if a user’s search query is empty – e.g., showing a set of interesting products when the search box is empty or the user hasn’t decided what to look for.

## Managing User Search History

Aisearch can keep track of recent searches performed by users (when you provide a `user_id` on queries). This can enable features like showing a user’s recent search terms for quick access. In some cases, you might want to clear or remove items from this search history — for privacy reasons or if the user chooses to delete their history.

The SDK provides the `searchRecentQuery()` controller to manage recent search queries. Currently, a primary operation available is deleting a recent search entry.

### Deleting Recent Searches

To delete a search query from a user’s history, you can do the following:

```php
// Initialize the searchRecentQuery action
$historyAction = $sdk->searchRecentQuery();
$historyAction->setUserId("user-123");       // the user whose history we want to modify
$historyAction->setQuery("dress");          // the search term to remove from history

// Perform the deletion
$success = $historyAction->delete();
if ($success) {
    echo "The query 'dress' was removed from user-123's recent searches.";
} else {
    echo "Failed to delete recent search: " . $historyAction->response->error;
}
```

In this snippet:
- We get the history controller via `$sdk->searchRecentQuery()`.
- We set the `userId` to specify whose history to affect, and `setQuery("dress")` to specify which search term to remove. Typically you’d get this search term from input (for example, if a user clicks a “X” next to a past search in the UI).
- We call `$historyAction->delete()`. This will make an API call to delete that query from the user’s history on the Aisearch side. It returns a boolean indicating success (`true` if the deletion was successful). The SDK’s `delete()` method internally uses an HTTP DELETE request to the API.
- If successful, you can update your application state (e.g., remove that term from the list shown to the user). If not, handle the error (perhaps log it).

**Deleting all history**: If you want to clear **all recent searches** for a user, you can call `delete()` without specifying a particular query. For example:
```php
$historyAction = $sdk->searchRecentQuery();
$historyAction->setUserId("user-123");
// No query set, meaning we intend to clear all history for that user
$historyCleared = $historyAction->delete();
```
If supported by the API, this would remove all saved recent queries for the given user. (Ensure to confirm this behavior in the Aisearch API documentation. If the API requires a query to be specified, you might need to loop through known queries to delete them individually. But typically, a DELETE on the recent queries endpoint without specifying a term might clear the history.)

**Use case**: This functionality can be tied to a “Clear search history” button or to automatically remove a particular term if, say, it’s considered sensitive. It helps maintain user privacy and control over their data.

## Error Handling & Best Practices

When integrating the Aisearch SDK, robust error handling and adhering to best practices will ensure a smooth user experience and easier maintenance. Here are some guidelines and tips:

- **Check API Response Status**: Always verify if a request succeeded before using the results. As shown in previous examples, use `$action->response->isSuccess()` which returns `true` if the HTTP status code was 200 (OK). If it returns `false`, examine `$action->response->error` for the error message or reason. For instance:
  ```php
  $result = $searchQuery->first();
  if (!$result->response->isSuccess()) {
      // Log the error and handle it gracefully
      error_log("Aisearch API error (code {$result->response->code}): " . $result->response->error);
      // You might show a user-friendly message or fallback to a different action
      return;
  }
  // ... proceed to use $result->model if success
  ```

- **Network and Exception Handling**: The SDK uses cURL internally. If there are network issues or the API is unreachable, the `$response->error` will contain details. It’s good practice to wrap critical calls in try-catch blocks if you expect other exceptions, although the SDK methods themselves typically return error info in the Response object rather than throwing exceptions. For example, invalid usage (like missing required parameters) likely results in an API error response rather than a PHP exception. Still, you may catch generic exceptions around your integration code to prevent any unexpected failures from breaking your application.

- **Use Meaningful User IDs**: When calling `setUserId()`, use a consistent and unique identifier for a user. This could be an internal user ID, an email (hashed if privacy is a concern), or a session ID for anonymous users. Using user IDs enables Aisearch to provide personalized recommendations (e.g., recently viewed items, related interests) and keeps the analytics data separated per user. If a user is not logged in, you might use a session or cookie ID to track their searches and later merge with their account if they log in.

- **Leverage Facets from Results**: After a search query, the returned model often contains `attributes` and `attributes.children` which list available filters (facets) and their counts (how many results each filter yields). Use this to dynamically populate filter options in your UI, and to show result counts next to filters if desired. This ensures your filters are relevant to the query and improves user experience.

- **Pagination Best Practices**: When implementing pagination or “load more” features with `next()`, be mindful of performance. You may not want to let a user page through hundreds of pages of results – consider a sensible limit or a strategy to refine the search if too many results are found. Also, if you present a “Load more” button, disable it or hide it when `next()` returns null (meaning no more pages).

- **Caching**: Depending on your traffic, it might be beneficial to cache certain API responses. For example, if your homepage shows a “Trending products” carousel powered by Aisearch (perhaps via discover or a recommendation call), that result might not need to be fetched on every page load if the data updates infrequently. You could cache it for a few minutes. However, be careful with personalized results caching – those should usually be per user and updated based on their activity.

- **Cleanup and Resource Management**: The SDK will open HTTP connections via cURL. It’s generally efficient and closes connections after use. Just ensure you don’t hold onto large responses unnecessarily. For instance, after processing results, if you no longer need the full `$action->model`, you can unset it or let it go out of scope for garbage collection to free memory, especially in long-running scripts.

- **Follow API Limits**: If Aisearch’s service has rate limits (check the service documentation), avoid excessive calls in a tight loop. For example, retrieving an entire catalog by iterating through all pages might hit rate limits or be slow. Use filters to narrow results or implement backoff as needed. In practice, typical usage (one query per user action) will be fine.

By following these best practices, you’ll create a robust integration that handles errors gracefully and provides a seamless search experience to users. The SDK is designed to abstract many complexities, but it’s up to your application to use it wisely and securely.

## Complete Sample Use Cases

To tie everything together, let's explore how you might integrate the Aisearch SDK in real-world scenarios. We will consider two common use cases: using Aisearch in an e-commerce site (product search page and recommendations), and integrating Aisearch with a web application using an API endpoint approach.

### Example 1: E-commerce Search Page Integration

Imagine you are building the search page for an online store. You want to use Aisearch to handle the search logic. When the user enters a query and possibly selects some filters or sort options, your backend will use the SDK to fetch results and then render them.

**Scenario**: The user accesses `/search.php?q=dress&category=12&sort=price_asc`. This means they searched for "dress", filtered by category id 12, and chose to sort by price ascending.

Your `search.php` (or relevant controller code) could be:

```php
<?php
require 'Aisearch.php';
use AisearchClient\Aisearch;
use AisearchClient\controllers\Search\QueryAction;

// Initialize Aisearch SDK
$sdk = new Aisearch(<YOUR_SITE_ID>, '<YOUR_CLIENT_TOKEN>');

// Get query parameters from request (with basic sanitization)
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$categoryFilter = isset($_GET['category']) ? intval($_GET['category']) : null;
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : '';

// Prepare search action
$search = $sdk->search()->query();
$search->setUserId($currentUserId ?? session_id());   // Use logged-in user ID or session as userId
if ($searchTerm !== '') {
    $search->setQuery($searchTerm);
}
$search->setLimit(20);  // show 20 results per page

// Apply category filter if provided
if ($categoryFilter) {
    // Assume categoryFilter corresponds to parent attribute ID 5 for "Category"
    $search->addFilterAttribute(5, $categoryFilter);
}
// Apply sorting if provided
if ($sortOption === 'price_asc') {
    $search->setSort(QueryAction::SORT_PRICE_ASC);
} elseif ($sortOption === 'price_desc') {
    $search->setSort(QueryAction::SORT_PRICE_DESC);
} elseif ($sortOption === 'name_asc') {
    $search->setSort(QueryAction::SORT_NAME_ASC);
} elseif ($sortOption === 'name_desc') {
    $search->setSort(QueryAction::SORT_NAME_DESC);
} else {
    $search->setSort(QueryAction::SORT_DEFAULT);
}

// Execute search (first page)
$searchResult = $search->first();
if ($searchResult->response->isSuccess()) {
    $model = $searchResult->model;
    // Use $model->products to display results
    foreach ($model->products as $prod) {
        // (render product card: for example)
        echo "<div class='product'>";
        echo "<h2>" . htmlspecialchars($prod->name) . "</h2>";
        echo "<p>Price: " . $prod->price . "</p>";
        echo "</div>";
    }
    // Also, you can use $model->attribute_parents and $model->attributes 
    // to display available filters (e.g., show checkboxes for colors, brands, etc., with counts).
    // Use $model->count for total results count, and $model->page for pagination info.

    // If implementing pagination links:
    if ($model->page->next > 0) {
        echo "<a href=\"search.php?q={$searchTerm}&category={$categoryFilter}&sort={$sortOption}&page=" . $model->page->next . "\">Next Page</a>";
    }
} else {
    // Handle error (e.g., show a message)
    echo "<p class='error'>Sorry, something went wrong with the search.</p>";
    error_log("Search API error: " . $searchResult->response->error);
}
?>
```

A few notes on this integration:
- We fetched user input from `$_GET` and used it to configure the search query. This includes the search term, an example filter (category), and sort choice.
- The user ID is set using either a logged-in user’s ID or a session ID. This ensures continuity in recommendations.
- After executing the search, the code would typically be embedded in a page that loops through `$model->products` and formats them into HTML (as sketched above).
- We also check for a next page and provide a link (or could be a button) to get the next page. In a real application, you might handle this via another request or AJAX call that uses `$search->next()`.
- The available filters (`$model->attribute_parents` and `$model->attributes`) contain data like filter names and possible values with counts. You can use these to generate the sidebar of filters. For example, if “Color” is an attribute parent with ID 10, and it has children representing "Red", "Blue", etc., you can list those with checkboxes. When the user selects a filter and submits the form, your code can capture it (similar to how category was handled) and call `addFilterAttribute` accordingly.
- By using the SDK, you avoid manually constructing API calls or parsing JSON – you get PHP objects to work with directly.

**Recommendations on Product Page**: Additionally, on a product detail page, you might want to show related products. Using the SDK, you could do something like:
```php
$rec = $sdk->recommendation()->carousel();
$rec->setUserId($currentUserId);
$rec->setCategoryId($currentProductCategoryId); // recommend within same category
$recResult = $rec->get();
if ($recResult->response->isSuccess()) {
    foreach ($recResult->model->products as $recProd) {
        // display recommended product (e.g., "You may also like")
    }
}
```
This would fetch recommendations possibly related to the current product’s category. You could also set brand or other context if desired. This way, your product page has a “Similar items” section powered by Aisearch.

### Example 2: Integrating via a Web Application API Endpoint

In modern web applications, you might separate the frontend (JavaScript single-page app or a mobile app) from the backend. In that case, you can use the Aisearch SDK on the server to create API endpoints that your frontend calls via AJAX/HTTP.

**Scenario**: You have an endpoint `/api/search` that expects a JSON request with the search term and filters, and returns search results as JSON. This backend script uses Aisearch SDK to get data and then encodes it to JSON for the client.

For instance, a simplified `api/search.php` might look like:

```php
<?php
header('Content-Type: application/json');
require 'Aisearch.php';
use AisearchClient\Aisearch;
use AisearchClient\controllers\Search\QueryAction;

// Read JSON input from client
$input = json_decode(file_get_contents('php://input'), true);
$queryTerm = $input['query'] ?? '';
$userId = $input['userId'] ?? (session_id());
$filters = $input['filters'] ?? [];   // e.g., ['category'=>12, 'color'=>22]
$sort = $input['sort'] ?? '';

// Initialize SDK
$sdk = new Aisearch(<SITE_ID>, '<TOKEN>');
$search = $sdk->search()->query();
$search->setUserId($userId);
if ($queryTerm !== '') {
    $search->setQuery($queryTerm);
}
$search->setLimit(10);

// Apply filters from input
if (!empty($filters)) {
    // This assumes filters array contains parentId => childId mappings
    foreach ($filters as $parentId => $childId) {
        $search->addFilterAttribute((int)$parentId, (int)$childId);
    }
}
// Apply sort from input
switch ($sort) {
    case 'price_asc':  $search->setSort(QueryAction::SORT_PRICE_ASC); break;
    case 'price_desc': $search->setSort(QueryAction::SORT_PRICE_DESC); break;
    case 'name_asc':   $search->setSort(QueryAction::SORT_NAME_ASC); break;
    case 'name_desc':  $search->setSort(QueryAction::SORT_NAME_DESC); break;
    default:           $search->setSort(QueryAction::SORT_DEFAULT);
}

// Execute search
$result = $search->first();
if (!$result->response->isSuccess()) {
    http_response_code(500);
    echo json_encode(["error" => "Search failed", "details" => $result->response->error]);
    exit;
}

// Prepare response data
$model = $result->model;
$responseData = [
    "total" => $model->count,
    "products" => [],
    "filters" => []
];
// Map product models to simpler arrays for JSON output
foreach ($model->products as $prod) {
    $responseData["products"][] = [
        "id" => $prod->id,
        "name" => $prod->name,
        "price" => $prod->price,
        // ... include other fields as needed, e.g., image URL, description
    ];
}
// Include available filters (facets) in response
foreach ($model->attribute_parents as $parent) {
    $parentId = $parent->id;
    $filterGroup = [
        "id" => $parentId,
        "name" => $parent->name,
        "values" => []
    ];
    // find corresponding attribute children for this parent
    foreach ($model->attributes as $child) {
        if ($child->parent_id === $parentId) {
            $filterGroup["values"][] = [
                "id" => $child->id,
                "name" => $child->name,
                "count" => $child->count  // how many results have this attribute
            ];
        }
    }
    $responseData["filters"][] = $filterGroup;
}

// Output JSON
echo json_encode($responseData);
?>
```

What this does:
- It reads a JSON request from the client containing `query`, `userId`, `filters`, and `sort`.
- Uses the Aisearch SDK to perform the search, similarly to our earlier example.
- If successful, it builds a structured array `$responseData` containing total results count, a simplified list of products, and filter options (with their result counts).
- Outputs this as JSON.

The frontend (could be a React/Vue/Angular app, or even plain JavaScript) would call this endpoint, then receive JSON and render the results accordingly (e.g., create HTML for each product, update the filter checkboxes with counts, etc.).

This separation ensures the heavy lifting is done by the SDK on the server, and the client just receives ready-to-use data. It also keeps the client token hidden on the server.

**Recommendation endpoint**: Similarly, you could create an endpoint `/api/recommendations` that returns recommended products. For example, if you want a “recommended for user” API:
```php
// Inside api/recommendations.php
$rec = $sdk->recommendation()->carousel();
$rec->setUserId($userId);
// Optionally accept context from client, e.g., category or brand, and set CategoryId/BrandId.
$recResult = $rec->get();
if ($recResult->response->isSuccess()) {
    // encode $recResult->model->products to JSON and output
}
```
The client could call this to get recommendations (maybe on page load for a dashboard).

**Recent search deletion endpoint**: You might have an endpoint `/api/clear-search` that takes a userId (and maybe a query) to clear history using `$sdk->searchRecentQuery()->delete()`, which you’d implement similarly – reading input and using the SDK method, then returning a success/failure response.

By integrating Aisearch SDK in your API, you maintain a clean separation and maximize reusability (mobile apps can use the same API, etc.).

---

These examples show how flexible the Aisearch SDK can be in different integration styles. Whether you render pages server-side or provide a RESTful API for a client-side app, the SDK simplifies the process of searching and recommending products.

## Conclusion

The Aisearch SDK provides a comprehensive set of tools to implement intelligent search and recommendations in your application. By following this documentation, developers should be able to:

- Initialize the SDK with proper credentials.
- Fetch site settings to configure their application.
- Perform search queries with ease, handling pagination, sorting, and filtering to refine results.
- Utilize recommendation features to increase user engagement, including personalized suggestions and a discovery feed.
- Manage user search histories by removing entries when needed.
- Implement best practices for error handling, security, and performance.

With Aisearch integrated, your users will enjoy a richer search experience – finding relevant products faster and discovering new items through AI-driven recommendations. Use the code snippets and patterns provided as a starting point, and adapt them to your specific application’s needs. Happy coding with Aisearch!