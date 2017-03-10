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
```