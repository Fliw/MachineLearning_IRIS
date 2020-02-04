<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Classifiers/KDNeighbors.php">[source]</a></span>

# K-d Neighbors
A fast K Nearest Neighbors algorithm that uses a binary search tree (BST) to divide the training set into *neighborhoods*. K-d Neighbors then does a binary search to locate the nearest neighborhood of an unknown sample and prunes all neighborhoods whose bounding box is further than the *k*'th nearest neighbor found so far. The main advantage of K-d Neighbors over brute force [KNN](k-nearest-neighbors.md) is that it is much more efficient, however it cannot be partially trained.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Probabilistic](../probabilistic.md), [Persistable](../persistable.md)

**Data Type Compatibility:** Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | k | 5 | int | The number of nearest neighbors to consider when making a prediction. |
| 2 | weighted | true | bool | Should we use the inverse distances as confidence scores when making predictions? |
| 3 | tree | KDTree | Spatial | The spatial tree used to run nearest neighbor searches. |

## Additional Methods
Return the base spatial tree instance:
```php
public tree() : Spatial
```

## Example
```php
use Rubix\ML\Classifiers\KDNeighbors;
use Rubix\ML\Graph\Trees\BallTree;
use Rubix\ML\Kernels\Distance\Minkowski;

$estimator = new KDNeighbors(3, false, new BallTree(40, new Minkowski()));
```