<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Classifiers/RadiusNeighbors.php">[source]</a></span>

# Radius Neighbors
Radius Neighbors is a spatial tree-based classifier that takes the weighted vote of each neighbor within a cluster of a fixed user-defined radius. Since the radius of the search can be constrained, Radius Neighbors is more robust to outliers than [K Nearest Neighbors](k-nearest-neighbors.md). In addition, Radius Neighbors acts as a quasi-anomaly detector by flagging samples that have 0 neighbors within the search radius.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Probabilistic](../probabilistic.md), [Persistable](../persistable.md)

**Data Type Compatibility:** Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | radius | 1.0 | float | The radius within which points are considered neighbors. |
| 2 | weighted | true | bool | Should we use the inverse distances as confidence scores when making predictions? |
| 3 | anomaly class | '?' | string | The class label for any samples that have no neighbors within the specified radius. |
| 4 | tree | BallTree | Spatial | The spatial tree used to run range searches. |

## Additional Methods
Return the base spatial tree instance:
```php
public tree() : Spatial
```

## Example
```php
use Rubix\ML\Classifiers\RadiusNeighbors;
use Rubix\ML\Graph\Trees\KDTree;
use Rubix\ML\Kernels\Distance\Manhattan;

$estimator = new RadiusNeighbors(50.0, false, '?', new KDTree(100, new Manhattan()));
```