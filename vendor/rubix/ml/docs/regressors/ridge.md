<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Regressors/Ridge.php">[source]</a></span>

# Ridge
L2 penalized ordinary least squares linear regression (OLS) solved using the closed-form equation. The addition of regularization, controlled by the *alpha* parameter, makes Ridge less prone to overfitting than non-regularized linear regression.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Persistable](../persistable.md)

**Data Type Compatibility:** Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | alpha | 1.0 | float | The L2 regularization penalty amount to be added to the weight coefficients. |

## Additional Methods
Return the weights of the model:
```php
public weights() : array|null
```

Return the bias parameter:
```php
public bias() : float|null
```

## Example
```php
use Rubix\ML\Regressors\Ridge;

$estimator = new Ridge(2.0);
```