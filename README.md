# ASSA ABLOY Americas AEH Marketing API

## Test Helpers

There are a few test helpers that have been created to make testing more streamlined and easier.

### Storing Data for Testing

There is a trait called `StoreResource` that can be used to store data for testing. This is useful when you want to
store data returned from an API call.

The trait function `storeResource()` is attached to the base test case and can be used as follows:

```php
$response = $this->get('foo/bar');
$this->storeResource('file_name', $response->json());
```

This will store the response in the tests/resources directory, preserving the same directory structure as the test file.

To retrieve the stored data, you can use the `getResource()` function like this:

```php
$this->getResource('file_name');
```

In addition to the above two functions, there is also a DTO transfer object that can be used to store data. This is
useful when you want to store data returned from an event. The DTO transfer object
is `App\Domains\DevSupport\Events\DevDTOTransfer`. To use the object in your test case, you can fake events and simply
dispatch the event with the returned data, and then store the data like this:

```php

// Dispatch the event
DevDTOTransfer::dispatch($foo);

// Test Case:
Event::fake();
Event::assertDispatched(DevDTOTransfer::class, function ($event): void {
	$this->storeResource('foo_bar', $event->payload);
});
```
