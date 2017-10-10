# Meiosis API - PHP SDK

This package will handle integration with the Meiosis API Services

- [Installation](#installation)
- [Common Concepts](#common-concepts)
- [Common Exceptions](#common-exceptions)
- [Initalization](#initalization)
- [Customers](#customers)
- [Organizations](#organizations)
- [Transactions](#transactions)
- [Content Management - Sites](#content-management---sites)
- [Content Management - Pages](#content-management---pages)
- [Content Management - Page Types](#content-management---page-types)
- [Content Management - Page Attributes](#content-management---page-attributes)



## Installation

```
composer require meiosis-io/amino
```

## Common Concepts

The `Meiosis\Amino` class methods will all return instances that extend `Meiosis\CRMObject`. These objects interact with their respective API endpoints, and should return items that extend `Meiosis\Models\BaseModel`. For example, to fetch a list of Pages out of the content management system that have a name that contains the text "Press Release", our code might look like this:

```php
$amino = new Meiosis\Amino($token, $team);
$pages = $amino
    ->pages($siteId)
    ->byAttributes([
        'name' => 'Press Release'
    ]);
```

In this case, `$pages` is now an array whose items are instances of the `Meiosis\Models\Page` object. Any attributes on these objects are directly available:

```php
// Show a list of the our page names:
foreach ($pages as $page) {
    echo $page->name;
}
```

## Common Exceptions
- `Meiosis\Exceptions\ObjectNotFoundException` - The API returned a 404 / Not Found error.
- `Meiosis\Exceptions\InvalidEndpointException` - A malformed request was sent and the endpoint couldn't be guessed. Check your supplied parameters.
- `Meiosis\Exceptions\ObjectNotPopulatedException` - The SDK tried to save changes to an object, but that object was not populated
- `Meiosis\Exceptions\ObjectValidationFailedException` - A 422 error was encountered from the API, meanined that arguments supplied are invalid.
- `Meiosis\Exceptions\UseOtherMethodException` - The API endpoint does not support the method you are trying to use (for example, updating an existing transaction).
- `Meiosis\Exceptions\UnknownApiException` - A 500 error was encountered talking with the API. You should try your request again.

## Initalization

You'll need to initialize a new instance of the Amino class, giving it an API token and Team ID

```php
$amino = new Meiosis\Amino($token, $team);
```

If you want to ensure that your server is able to reach the api properly, you can call the remote test function:

```php
$amino = new Meiosis\Amino($token, $team);
return $amino->remoteTest();
```

## Customers

To work with customers, call the `customers()` method on the Amino class.

```php
$amino = new Meiosis\Amino($token, $team);
$customers = $amino->customers();
```

## Organizations

To work with orgnaizations, call the `organizations()` method on the Amino class.

```php
$amino = new Meiosis\Amino($token, $team);
$organizations = $amino->organizations();
```

## Transactions

To work with transactions, call the `transactions()` method on the Amino class.

```php
$amino = new Meiosis\Amino($token, $team);
$transactions = $amino->transactions();
```

## Content Management - Sites

To work with sites, call the `sites()` method on the Amino class.

```php
$amino = new Meiosis\Amino($token, $team);
$sites = $amino->sites();
```

## Content Management - Pages

To work with pages, call the `pages()` method on the Amino class, passing in a site token / ID.

```php
$amino = new Meiosis\Amino($token, $team);
$siteToken = '12345-12345-123-12345';
$pages = $amino->pages($siteToken);
```

## Content Management - Page Types

To work with Page Types, call the `pageTypes()` method on the Amino class.

```php
$amino = new Meiosis\Amino($token, $team);
$pageTypes = $amino->pageTypes();
```

## Content Management - Page Attributes

To work with Page Attributes, call the `pageAttributes()` method on the Amino class, passing in a page type ID.

```php
$amino = new Meiosis\Amino($token, $team);
$pageType = '12345-12345-123-12345';
$pageAttributes = $amino->pageAttributes($pageType);
```





























## Content Management Pages

When trying to fetch items out of the content management sections, you'll need the site ID for the site you want to work with. You'll then pass that to the `pages` method if the SDK.

```php
// Returns an instance of Meiosis\Endpoints\CMSPage
$pagesObject = $amino->pages($siteID);
```

### Return Types

Methods within the `CMSPage` class will return either single instances or arrays of instances of the `Meiosis\Models\Page` class. They may also throw any of the common exceptions.

### Examples
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

## Customers

### Return Types

The customers end points will return instances of the `Meiosis\Endpoints\Customer` class

### Examples

Find / check if a customer already exists, where `$identifier` is either the customer's ID or email address

```php
// Find a Customer
$customer = $amino->customer($identifier);

// Check if customer exists
return $customer->exists();
```

Create a new customer

```php
// Create a Customer
$data = [
    'email'       => 'example@example.com',
    'first'       => 'First Name',
    'attr-custom' => 'Custom Attribute',
];

$customer = $amino->createCustomer($data);
```

Track an Interaction with a customer

```php
// Track a Customer Interaction
$customer->track('Source', 'Interaction Description');
```

Update an existing customer record

```php
// Update a customer
$customer->email = 'NewExample@example.com';
$customer->saveChanges();
```

## Transaction

### Return Types

The transaction end points will return instances of the `Meiosis\Endpoints\Transaction` class

### Examples

```php
$customer = $amino->customers()->find('someemail@example.com');

// Create a transaction Object
$transaction = $amino->transactions()->blueprint();

// Alternatively, you can instantate a new transaction directly.
// You'll need to pass it an instance of the CRMTransaction endpoint
// in order to use it's ->save() method.
// $transaction = new Meiosis\Models\Transaction([], $amino->transactions());

// Attach the customer
$transaction->customer = $customer;

// Create an item
$item = new Meiosis\Models\TransactionItem();
$item->price = 5.00;
$item->quantity = 2.25;

// Add the item to the transaction
$transaction->addItem($item);

// Save the transaction
$transaction->save();
```
