# Meiosis API Wrapper

```php

$amino = new Meiosis\Amino($token, $team);

$customer = $amino->endpoint('Customer');

// Fetch available Fields
$fields = $customer->fields();
```