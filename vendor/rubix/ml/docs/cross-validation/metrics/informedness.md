<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/CrossValidation/Metrics/Informedness.php">[source]</a></span>

# Informedness
Informedness is a measure of the probability that an estimator will make an informed decision. Its value ranges from -1 through 1 and has a value of 0 when the test yields no useful information.

**Estimator Compatibility:** Classifier, Anomaly Detector

**Output Range:** -1 to 1

## Example
```php
use Rubix\ML\CrossValidation\Metrics\Informedness;

$metric = new Informedness();
```

### References
>- W. J. Youden. (1950). Index for Rating Diagnostic Tests.