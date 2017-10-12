# Meiosis API - PHP SDK

This package will handle integration with the Meiosis API Services

- [Installation](#installation)
- [Common Concepts](#common-concepts)
    - [CRMObject Methods](#crmobject-methods)
    - [BaseModel Methods](#basemodel-methods)
- [Common Exceptions](#common-exceptions)
- [Initalization](#initalization)
- [Customers](#customers)
- [Organizations](#organizations)
- [Transactions](#transactions)
- [Content Management - Sites](#content-management---sites)
- [Content Management - Pages](#content-management---pages)
- [Content Management - Page Types](#content-management---page-types)
- [Content Management - Page Attributes](#content-management---page-attributes)
- [Practical Examples](#practical-examples)

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
    ->search([
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

### CRMObject Methods

The following methods are available on any object that extends the `Meiosis\Endpoints\CRMObject` class, such as the objects returned by the methods on the `Meiosis\Amino` class.

#### `->find($identifier)`

Given an identifier, the find method will return exactly one object that extends `Meiosis\Models\BaseModel`

#### `->search($searchArray)`

Given an array of search parameters in the format `['key' => 'value']`, the search method will return an array of objects that extend `Meiosis\Models\BaseModel`

#### `->blueprint()`

The blueprint method will return a new instance of the appropriate implementation of  `Meiosis\Models\BaseModel`. For example, a new empty customer would look like:

```php
$amino = new Meiosis\Amino($token, $team);
$newCustomer = $amino->customers()->blueprint();
```

#### `->save($object)`

Passing the save method an implementation of the corresponding `Meiosis\Models\BaseModel` will create a new object if it does not yet exist, or will update an existing object. A shortcut to this method exists on each `BaseModel` object, via it's own internal `->save()` method. For example, the following two blocks achieve the same thing:

```php
$amino = new Meiosis\Amino($token, $team);
$customer = $amino->customers()->find($customerID);
// Save the customer
$amino->customers->save($customer);
```

```php
$amino = new Meiosis\Amino($token, $team);
$customer = $amino->customers()->find($customerID);
// Save the customer
$customer->save();
```

#### `->delete($identifier)`

Given an identifier, deletes the record from the system.

#### `->payload($data)`

Build the payload needed for the APIClient. This method is mostly used internally, but is publicly available.

### BaseModel Methods

The following methods are available on any object that extends the `Meiosis\Models\BaseModel` class, such as the objects returned by the classes in `Meiosis\Endpoints`

#### `::getNativeFields()`

The static `getNativeFields()` method will return the `$native` array on each `BaseModel`. This is used when building new methods, or when you want to combine native attributes with custom attributes.

#### `->populate($data)`

Populates the object instance with data from the `$data` array.

#### `->extract()`

Converts the object into an array, extracting the underlying `$data` array

#### `->save()`

Creates or updates the object and repopulated it with any new fields (id, timestamps, etc)

#### `->refresh()`

Reloads the object, fetching fresh data from the api

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

To work with customers, call the `customers()` method on the Amino class. Models returned are instances of the `Meiosis\Models\Customer` class.

```php
$amino = new Meiosis\Amino($token, $team);
$customers = $amino->customers();
```

### Additional Methods

Aside from the methos mentioned on [CRMObject Methods](#crmobject-methods), the following methods also exist:

#### `->trackInteraction($customer, $source, $description, $priority)`

Given a `Meiosis\Models\Customer` object (`$customer`), this method will record an interaction from the source `$source`, with the description `$description`, with a default `$priority` of 5.

## Organizations

To work with orgnaizations, call the `organizations()` method on the Amino class. Models returned are instances of the `Meiosis\Models\Organization` class.

```php
$amino = new Meiosis\Amino($token, $team);
$organizations = $amino->organizations();
```

## Transactions

To work with transactions, call the `transactions()` method on the Amino class. Models returned are instances of the `Meiosis\Models\Transaction` class.

```php
$amino = new Meiosis\Amino($token, $team);
$transactions = $amino->transactions();
```

### Special Cases

#### `->search($searchArray)`

Transactions are not searchable. This method will throw a `Meiosis\Exceptions\InvalidEndpointException` Exception.

#### `->save()`

While new transactions can be saved, existing transactions can not. Transactions can not be updated once recorded, but can be removed or voided.

## Content Management - Sites

To work with sites, call the `sites()` method on the Amino class. Models returned are instances of the `Meiosis\Models\Site` class.

```php
$amino = new Meiosis\Amino($token, $team);
$sites = $amino->sites();
```

## Content Management - Pages

To work with pages, call the `pages()` method on the Amino class, passing in a site token / ID. Models returned are instances of the `Meiosis\Models\Page` class.

```php
$amino = new Meiosis\Amino($token, $team);
$siteToken = '12345-12345-123-12345';
$pages = $amino->pages($siteToken);
```

### Additional Methods

Aside from the methos mentioned on CRMObject Methods, the following methods also exist:

#### `->getHierarchy($pageID)`

The `getHierarchy` method accepts an optional `$pageID` parameter. Without a `$pageID`, it will return an array of simple `Meiosis\Models\Page` objects, with their children, starting at the root level of the page hierarchy. If passing a `$pageID`, the returned array will have the matching page as the only root page.

#### `->bySlug($slug)`

Given a `$slug`, the `bySlug` method will return an array of pages that match the given slug. The slug should be as specific as possible, including parents as well. For example, a page with the slug `my-page` that is a child of a `about-us` page could be found with: `->bySlug('about-us/my-page');`

#### `->setSiteToken($token)`

The `setSiteToken` method can be used to change which site the class is using.

## Content Management - Page Types

To work with Page Types, call the `pageTypes()` method on the Amino class, passing in a site token / ID. Models returned are instances of the `Meiosis\Models\PageType` class.

```php
$amino = new Meiosis\Amino($token, $team);
$siteToken = '12345-12345-123-12345';
$pageTypes = $amino->pageTypes($siteToken);
```

### Additional Methods

Aside from the methos mentioned on CRMObject Methods, the following methods also exist:

#### `->setSiteToken($token)`

The `setSiteToken` method can be used to change which site the class is using.

## Content Management - Page Attributes

To work with Page Attributes, call the `pageAttributes()` method on the Amino class, passing in a page type ID. Models returned are instances of the `Meiosis\Models\PageAttribute` class.

```php
$amino = new Meiosis\Amino($token, $team);
$pageType = '12345-12345-123-12345';
$pageAttributes = $amino->pageAttributes($pageType);
```

### Additional Methods

Aside from the methos mentioned on CRMObject Methods, the following methods also exist:

#### `->all()`

Returns an array of all `PageAttributes` for the page type.

## Practical Examples

### Create a new Customer and track an interaction

```php
$amino = new Meiosis\Amino($token, $team);

$customer = $amino->customers()->blueprint();

$customer->first = "John";
$customer->last  = "Doe";
$customer->email = "jdoe@example.com";
$customer->save();

$amino->customers()->trackInteraction($customer, 'My Site', 'Filled out a form');
```

### Record a transaction

```php
$amino = new Meiosis\Amino($token, $team);
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

### Get pages based on custom attributes

Assuming that a custom page attribute has been created with the key `attr_category`, we will find all pages in the category 'news':

```php
$amino = new Meiosis\Amino($token, $team);

$siteID = '12345-123-123-12345';
$pages = $amino->pages($siteID)->search([
    'attr_category' => 'news'
]);
```