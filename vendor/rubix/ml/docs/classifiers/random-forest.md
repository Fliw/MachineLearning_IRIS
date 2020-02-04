<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Classifiers/RandomForest.php">[source]</a></span>

# Random Forest
An ensemble classifier that trains Decision Trees ([Classification Trees](classification-tree.md) or [Extra Trees](extra-tree-classifier.md)) on random subsets (*bootstrap* set) of the training data. Predictions are based on the probability scores returned from each tree in the forest, averaged and weighted equally.

> **Note:** The default base tree learner is a fully grown Classification Tree.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Probabilistic](../probabilistic.md), [Parallel](../parallel.md), [Persistable](../persistable.md)

**Data Type Compatibility:** Categorical, Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | base | ClassificationTree | Learner | The base tree learner. |
| 2 | estimators | 100 | int | The number of learners to train in the ensemble. |
| 3 | ratio | 0.2 | float | The ratio of random samples (between 0 and 1.5) to train each base learner on. |

## Additional Methods
Return the normalized feature importances i.e. the proportion that each feature contributes to the overall model, indexed by feature column:
```php
public featureImportances() : array
```

## Example
```php
use Rubix\ML\Classifiers\RandomForest;
use Rubix\ML\Classifiers\ClassificationTree;

$estimator = new RandomForest(new ClassificationTree(10), 300, 0.1);

// Train the learner

$importances = $estimator->featureImportances();

var_dump($importances);
```

```sh
array(3) {
  [0]=> float(0.39250395133811)
  [1]=> float(0.1555633977313)
  [2]=> float(0.45193265093059)
}
```

### References
>- L. Breiman. (2001). Random Forests.
>- L. Breiman et al. (2005). Extremely Randomized Trees.