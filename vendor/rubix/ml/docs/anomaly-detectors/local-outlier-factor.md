<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/AnomalyDetectors/LocalOutlierFactor.php">[source]</a></span>

# Local Outlier Factor
Local Outlier Factor (LOF) measures the local deviation of density of an unknown sample with respect to its *k* nearest neighbors from the training set. As such, LOF only considers the local region (or *neighborhood*) of an unknown sample which enables it to detect anomalies within individual clusters of data.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Ranking](../ranking.md), [Persistable](../persistable.md)

**Data Type Compatibility:** Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | k | 20 | int | The k nearest neighbors that form a local region. |
| 2 | contamination | null | float | The proportion of outliers that are presumed to be present in the training set. |
| 3 | tree | KDTree | Spatial | The spatial tree used to run nearest neighbor searches. |

## Additional Methods
Return the base spatial tree instance:
```php
public tree() : Spatial
```

## Example
```php
use Rubix\ML\AnomalyDetectors\LocalOutlierFactor;
use Rubix\ML\Graph\Trees\BallTree;
use Rubix\ML\Kernels\Distance\Euclidean;

$estimator = new LocalOutlierFactor(20, 0.1, new BallTree(30, new Euclidean));
```

### References
>- M. M. Breunig et al. (2000). LOF: Identifying Density-Based Local Outliers.