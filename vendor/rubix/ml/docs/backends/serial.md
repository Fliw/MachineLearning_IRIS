<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Backends/Serial.php">[source]</a></span>

### Serial
The Serial backend executes tasks sequentially inside of a single process. The advantage of the Serial backend is that it has zero overhead, thus it may be faster than a parallel backend for small datasets.

> **Note:** The Serial backend is the default for most objects that are capable of parallel processing.

## Parameters
This backend does not have any additional parameters.

## Additional Methods
This backend does not have any additional methods.

## Example
```php
use Rubix\ML\Backends\Serial;

$backend = new Serial();
```