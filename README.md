# Meiosis API - PHP SDK

This package will handle integration with the Meiosis API Services

## Installation

```
composer require meiosis-io/amino
```

## Usage

### Initalization

You'll need to initialize a new instance of the Amino class, giving it an API token and Team ID

```php
$amino = new Meiosis\Amino($token, $team);
```

### Content Management Pages

When trying to fetch items out of the content management sections, you'll need the site ID for the site you want to work with. You'll then pass that to the `pages` method if the SDK.

```php
$pagesObject = $amino->pages($siteID);
```

### Return Types

Most of the page methods will either return a single instance of `Meiosis\Models\Page` or one of the following exceptions:

- `Meiosis\Exceptions\ObjectNotFoundException` - The API returned a 404 / Not Found error.
- `Meiosis\Exceptions\InvalidEndpointException` - A malformed request was sent and the endpoint couldn't be guessed. Check your supplied parameters.
- `Meiosis\Exceptions\ObjectNotPopulatedException` - The SDK tried to save changes to an object, but that object was not populated
- `Meiosis\Exceptions\ObjectValidationFailedException` - A 422 error was encountered from the API, meanined that arguments supplied are invalid.
- `Meiosis\Exceptions\UnknownApiException` - A 500 error was encountered talking with the API. You should try your request again.

#### Examples
To get a specific page by ID:

```php
$page = $pagesObject->find('PAGEID');
```

To Load the hierarchy for a given site or page

```php
$hierarchy = $pagesObject->getHierarchy(); // For whole site
$hierarchy = $pagesObject->getHierarchy('PAGEID'); // For specific page
```

To search for a page based on a slug (such as /blog/my-blog-post)

```php
$page = $pagesObject->bySlug('/blog/my-blog-post');
```

If you want to search by attributes on your pages (even those that you've created through page types), use the `byAttributes` method.

```php
$page = $pagesObject->byAttributes([
    'title'       => 'Press Release',
    'attr-custom' => 'Some Custom Value'
]);
```

// Find a Customer
$customer = $amino->customer($identifier);

// Check if customer exists
return $customer->exists();

// Create a Customer
$data = [
    'email'       => 'example@example.com',
    'first'       => 'First Name',
    'attr-custom' => 'Custom Attribute',
];

$customer = $amino->createCustomer($data);

// Track a Customer Interaction
$customer->track('Source', 'Interaction Description');

// Update a customer
$customer->email = 'NewExample@example.com';
$customer->saveChanges();

// Record a transaction
$transactionData = [
    'items' => [
        [
            'item_id' => '12345', // Optional
            'description' => 'Some Items Description', // Optional
            'price' => 15.25, // Required
            'quantity' => 5.5, // Optional, Defaults to 1
        ],[
            'item_id' => '45342', // Optional
            'description' => 'Some Other Description', // Optional
            'price' => 10.05, // Required
            'quantity' => 2, // Optional, Defaults to 1
        ]
    ]
];

// Or without Item Data
$transactionData = [
    'total' => 100.00
];

$amino->recordTransaction($customer, $transactionData);


// Get a CMS Page
$amino
    ->pages($siteToken)
    ->byId($pageId);

// Get a page by slug (Slower than id)
$amino
    ->pages($siteToken)
    ->bySlug($slug);
```