<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Transformers/MissingDataImputer.php">[source]</a></span>

# Missing Data Imputer
Missing Data Imputer replaces missing continuous (denoted by `NaN`) or categorical values (denoted by special placeholder category) with a guess based on user-defined [Strategy](../other/strategies/api.md). 

**Interfaces:** [Transformer](api.md#transformers), [Stateful](api.md#stateful)

**Data Type Compatibility:** Categorical and Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | continuous strategy | Mean | Continuous | The guessing strategy to employ for continuous feature columns. |
| 2 | categorical strategy | K Most Frequent | Categorical | The guessing strategy to employ for categorical feature columns. |
| 3 | placeholder | '?' | string | The categorical placeholder variable denoting the category that contains missing values. |

## Additional Methods
This transformer does not have any additional methods.

## Example
```php
use Rubix\ML\Transformers\MissingDataImputer;
use Rubix\ML\Other\Strategies\Percentile;
use Rubix\ML\Other\Strategies\Prior;

$transformer = new MissingDataImputer(new Percentile(0.55), new Prior(), '?');
```