<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Clusterers/KMeans.php">[source]</a></span>

# K Means
A fast online centroid-based hard clustering algorithm capable of grouping linearly separable data points given some prior knowledge of the target number of clusters (defined by *k*). K Means is trained using adaptive Mini Batch Gradient Descent and minimizes the inertia cost function. Inertia is defined as the average sum of distances between each sample and its nearest cluster centroid.

**Interfaces:** [Estimator](../estimator.md), [Learner](../learner.md), [Online](../online.md), [Probabilistic](../probabilistic.md), [Persistable](../persistable.md), [Verbose](../verbose.md)

**Data Type Compatibility:** Continuous

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | k | | int | The number of target clusters. |
| 2 | batch size | 100 | int | The size of each mini batch in samples. |
| 3 | epochs | 300 | int | The maximum number of training rounds to execute. |
| 4 | min change | 10.0 | float | The minimum change in the inertia for training to continue. |
| 5 | window | 10 | int | The number of epochs without improvement in the validation score to wait before considering an early stop. |
| 6 | kernel | Euclidean | Distance | The distance kernel used to compute the distance between sample points. |
| 7 | seeder | PlusPlus | Seeder | The seeder used to initialize the cluster centroids. |

## Additional Methods
Return the *k* computed centroids of the training set:
```php
public centroids() : array
```

Return the number of training samples that each centroid is responsible for:
```php
public sizes() : array
```

Return the value of the loss function at each epoch from the last round of training:
```php
public steps() : array
```

## Example
```php
use Rubix\ML\Clusterers\KMeans;
use Rubix\ML\Kernels\Distance\Euclidean;
use Rubix\ML\Clusterers\Seeders\PlusPlus;

$estimator = new KMeans(3, 100, 300, 10.0, 10, new Euclidean(), new PlusPlus());
```

### References
>- D. Sculley. (2010). Web-Scale K-Means Clustering.