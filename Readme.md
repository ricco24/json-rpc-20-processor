JSON RPC 2.0 request processor
============

Easy JSON RPC 2.0 processor. No build in response senders, only process and return result object.

Usage
------------

```
use MyHandlers\AddHandler;
use Kelemen\JsonRpc20\Processor;
use Kelemen\JsonRpc20\Processor\Transform\FractalTransform;

$raw = file_get_contents('php://input');
$handler = new AddHandler();

$processor = new Processor();

// Add new handler for method "add"
$processor->registerHandler('add', [$handler, 'add']);

// Response is Response object or array of Response object for batch request
$response = $processor->process($raw);

// Transform result to array or json
$fractalTransform = new FractalTransform();
return $fractalTransform->toArray($response);
```