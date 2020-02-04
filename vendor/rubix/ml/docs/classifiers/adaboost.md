<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Classifiers/AdaBoost.php">[source]</a></span>

# AdaBoost
Short for *Adaptive Boosting*, this ensemble classifier can improve the performance of an otherwise *weak* classifier by focusing more attention on samples that are harder to classify. It builds an additive model where, at each stage, a new learner is trained and given an influence score inversely proportional to the loss it incurs at that epoch.

> **Note:** The default base learners is a Classification Tree with a max depth of 1 i.e a *Decision Stump*.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Probabilistic](../probabilistic.md), [Verbose](../verbose.md), [Persistable](../persistable.md)

**Data Type Compatibility:** Depends on base learner

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | base | ClassificationTree | Learner | The base *weak* classifier to be boosted. |
| 2 | rate | 1.0 | float | The learning rate of the ensemble i.e. the *shrinkage* applied to each step. |
| 3 | ratio | 0.8 | float | The ratio of samples to subsample from the training set to train each *weak* learner. |
| 4 | estimators | 100 | int | The maximum number of *weak* learners to train in the ensemble. |
| 5 | min change | 1e-4 | float | The minimum change in the training loss necessary to continue training. |

## Additional Methods
Return the calculated weight values of the samples in the last training set:
```php
public weights() : array
```

Return the influence scores for each boosted classifier:
```php
public influences() : array
```

Return the training loss at each epoch:
```php
public steps() : array
```

## Example
```php
use Rubix\ML\Classifiers\AdaBoost;
use Rubix\ML\Classifiers\ExtraTreeClassifier;

$estimator = new AdaBoost(new ExtraTreeClassifier(3), 0.1, 0.5, 200, 1e-3);
```

### References
 >- Y. Freund et al. (1996). A Decision-theoretic Generalization of On-line Learning and an Application to Boosting.
 >- J. Zhu et al. (2006). Multi-class AdaBoost.