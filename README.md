# Meiosis API Wrapper

## Doc Coming soon...
```php

$amino = new Meiosis\Amino($token, $team);

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