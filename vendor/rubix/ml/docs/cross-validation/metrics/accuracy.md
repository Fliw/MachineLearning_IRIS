<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/CrossValidation/Metrics/Accuracy.php">[source]</a></span>

# Accuracy
A quick and simple classification and anomaly detection metric defined as the number of true positives over the number of samples in the testing set. Since Accuracy gives equal weight to false positives and false negatives, it is *not* a good metric for datasets with a highly imbalanced distribution of labels.

**Estimator Compatibility:** Classifier, Anomaly Detector

**Output Range:** 0 to 1

## Example
```php
use Rubix\ML\CrossValidation\Metrics\Accuracy;

$metric = new Accuracy();
```