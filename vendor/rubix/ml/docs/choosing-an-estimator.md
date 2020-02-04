# Choosing an Estimator
Estimators make up the core of the Rubix ML library and include classifiers, regressors, clusterers, anomaly detectors, and meta-estimators organized into their own namespaces. They are responsible for making predictions and are usually trained with data. Some meta-estimators such as [Pipeline](pipeline.md) and [Grid Search](grid-search.md) are *polymorphic* i.e. they bear the type of the base estimator they wrap. Most estimators allow tuning by adjusting their hyper-parameters. To instantiate a new estimator, pass the desired values of the hyper-parameters to the estimator's constructor like in the example below.

**Example**

```php
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Kernels\Distance\Minkowski;

$estimator = new KNearestNeighbors(10, false, new Minkowski(2.0));
```

It is important to note that not all estimators are created equal and choosing the right estimator for your project is important for achieving the best results. In the following sections, we'll break down the estimators available to you in Rubix ML and point out some of their advantages and disadvantages.

## Classifiers
Classifiers can often be graded on their ability to form decision boundaries between areas that define the classes. Simple linear classifiers such as Logistic Regression can only handle classes that are *linearly separable*. On the other hand, highly flexible models such as the Multilayer Perceptron can theoretically handle any decision boundary. The tradeoff for increased flexibility is reduced interpretability, increased computational complexity, and greater susceptibility to [overfitting](cross-validation.md#overfitting).

| Classifier | Flexibility | Proba | Online | Advantages | Disadvantages |
|---|---|---|---|---|---|
| [AdaBoost](classifiers/adaboost.md) | High | ● | | Boosts most classifiers, Learns influences and sample weights | Sensitive to noise, Susceptible to overfitting |
| [Classification Tree](classifiers/classification-tree.md) | Moderate | ● | | Interpretable model, automatic feature selection | High variance, Susceptible to overfitting |
| [Extra Tree Classifier](classifiers/extra-tree-classifier.md) | Moderate | ● | | Faster training, Lower variance | Similar to Classification Tree |
| [Gaussian Naive Bayes](classifiers/gaussian-nb.md) | Moderate | ● | ● | Requires little data, Highly scalable | Strong Gaussian and feature independence assumption, Sensitive to noise |
| [K-d Neighbors](classifiers/k-d-neighbors.md) | Moderate | ● | | Faster inference | Not compatible with certain distance kernels |
| [K Nearest Neighbors](classifiers/k-nearest-neighbors) | Moderate | ● | ● | Intuitable model, Zero-cost training | Slower inference, Suffers from the curse of dimensionality |
| [Logistic Regression](classifiers/logistic-regression.md) | Low | ● | ● | Interpretable model, Highly Scalable | Prone to underfitting, Only handles 2 classes |
| [Multilayer Perceptron](classifiers/multilayer-perceptron.md) | High | ● | ● | Handles very high dimensional data, Universal function approximator | High computation and memory cost, Black box |
| [Naive Bayes](classifiers/naive-bayes.md) | Moderate | ● | ● | Requires little data, Highly scalable | Strong feature independence assumption |
| [Radius Neighbors](classifiers/radius-neighbors.md) | Moderate | ● | | Robust to outliers, Quasi-anomaly detector | Not guaranteed to return a prediction |
| [Random Forest](classifiers/random-forest.md) | High | ● | | Stable, Computes reliable feature importances | High computation and memory cost |
| [Softmax Classifier](classifiers/softmax-classifier.md) | Low | ● | ● | Highly Scalable | Prone to underfitting |
| [SVC](classifiers/svc.md) | High | | | Handles high dimensional data | Difficult to tune, Not suitable for large datasets |

## Regressors
In terms of regression, flexibility is expressed as the ability of a model to fit a regression line to potentially complex non-linear data. Linear models such as Ridge tend to [underfit](cross-validation.md#underfitting) data that is non-linear while more flexible models such as Gradient Boost are prone to overfit the training data if not tuned properly. In general, it's best to choose the simplest regressor that doesn't underfit your dataset.

| Regressor | Flexibility | Online | Verbose | Advantages | Disadvantages |
|---|---|---|---|---|---|
| [Adaline](regressors/adaline.md) | Low | ● | ● | Interpretable model, Highly Scalable | Prone to underfitting |
| [Extra Tree Regressor](regressors/extra-tree-regressor.md) | Moderate | | | Faster training, Lower variance | Similar to Regression Tree |
| [Gradient Boost](regressors/gradient-boost.md) | High | | ● | High precision, Computes reliable feature importances | Prone to overfitting, High computation and memory cost |
| [K-d Neighbors Regressor](regressors/k-d-neighbors-regressor.md) | Moderate | | | Faster inference | Not compatible with certain distance kernels |
| [KNN Regressor](regressors/knn-regresor.md) | Moderate | ● | | Intuitable model, Zero-cost training | Slower inference, Suffers from the curse of dimensionality |
| [MLP Regressor](regressors/mlp-regressor.md) | High | ● | ● | Handles very high dimensional data, Universal function approximator | High computation and memory cost, Black box |
| [Radius Neighbors Regressor](regressors/radius-neighbors-regressor.md) | Moderate | | | Robust to outliers, Quasi-anomaly detector | Not guaranteed to return a prediction |
| [Regression Tree](regressors/regression-tree.md) | Moderate | | | Interpretable model, automatic feature selection | High variance, Susceptible to overfitting |
| [Ridge](regressors/ridge.md) | Low | | | Interpretable model | Prone to underfitting |
| [SVR](regressors/svr.md) | High | | | Handles high dimensional data | Difficult to tune, Not suitable for large datasets |

## Clusterers
Clusterers can be rated by their ability to represent an outer hull surrounding the samples in the cluster. Simple centroid-based models such as K Means establish a uniform hypersphere around the clusters. More flexible clusterers such as Gaussian Mixture can better conform to the outer shape of the cluster by allowing the surface of the hull to be irregular and *bumpy*. The tradeoff for flexibility typically results in more model parameters and with it increased computational complexity.

| Clusterer | Flexibility | Proba | Online | Advantages | Disadvantages |
|---|---|---|---|---|---|
| [DBSCAN](clusterers/dbscan.md) | High | | | Finds arbitrarily-shaped clusters, Quasi-anomaly detector | Cannot be trained, Slower inference |
| [Fuzzy C Means](clusterers/fuzzy-c-means.md) | Low | ● | | Fast training and inference, Soft clustering | Solution highly depends on initialization, Not suitable for large datasets |
| [Gaussian Mixture](clusterers/gaussian-mixture.md) | Moderate | ● | | Captures non-spherical clusters | Higher memory cost |
| [K Means](clusterers/k-means.md) | Low | ● | ● | Fast training and inference, Highly scalable | Has local minima |
| [Mean Shift](clusterers/mean-shift.md) | Moderate | ● | | Handles non-convex clusters, No local minima | Slower training |

## Anomaly Detectors
Anomaly detectors can be thought of as belonging to one of two groups. There are the anomaly detectors that consider the entire training data when making a prediction, and there are those that only consider a *local region* of the training set. Local anomaly detectors are typically more accurate but come with higher computational complexity. Global anomaly detectors are more suited for real-time applications but may produce a higher number of false positives and/or negatives.

| Anomaly Detector | Scope | Ranking | Online | Advantages | Disadvantages |
|---|---|---|---|---|---|
| [Gaussian MLE](anomaly-detectors/gaussian-mle.md) | Global | ● | ● | Fast training and inference, Highly scalable | Strong Gaussian and feature independence assumption, Sensitive to noise |
| [Isolation Forest](anomaly-detectors/isolation-forest.md) | Local | ● | | Fast training, Handles high dimensional data | Slower Inference |
| [Local Outlier Factor](anomaly-detectors/local-outlier-factor.md) | Local | ● | | Intuitable model, Finds anomalies within clusters | Suffers from the curse of dimensionality |
| [Loda](anomaly-detectors/loda.md) | Global | ● | ● | Highly scalable | High memory cost |
| [One Class SVM](anomaly-detectors/one-class-svm.md) | Global | | | Handles high dimensional data | Difficult to tune, Not suitable for large datasets |
| [Robust Z-Score](anomaly-detectors/robust-z-score.md) | Global | ● | | Requires little data, Robust to outliers | Has problem with skewed datasets  |

## Meta-estimators
Meta-estimators enhance other estimators with their own added functionality. They include ensembles, model selectors, and other model enhancers that wrap a compatible base estimator.

| Meta-estimator | Usage | Parallel | Verbose | Compatibility |
|---|---|---|---|---|
| [Bootstrap Aggregator](bootstrap-aggregator.md) | Ensemble | ● | | Classifiers, Regressors, Anomaly Detectors |
| [Committee Machine](committee-machine.md) | Ensemble | ● | ● | Classifiers, Regressors, Anomaly Detectors |
| [Grid Search](grid-search.md) | Model Selection | ● | ● | Any |
| [Persistent Model](persistent-model.md) | Model Persistence | | | Any persistable estimator |
| [Pipeline](pipeline.md) | Preprocessing | | ● | Any |

In the example below, we'll use the Bootstrap Aggregator meta-estimator to wrap a Regression Tree.

**Example**

```php
use Rubix\ML\BootstrapAggregator;
use Rubix\ML\Regressors\RegressionTree;

$estimator = new BootstrapAggregator(new RegressionTree(4), 1000);
```

## No Free Lunch Theorem
At some point you may ask yourself "Why do we need so many different learning algorithms? Can't we just use one that works all the time?" The answer to those questions can be understood by the *No Free Lunch* (NFL) theorem. The No Free Lunch theorem states that, when averaged over *all* possible problems, no learner performs any better than the next. Another way of saying that is certain learners perform better in some tasks and worse in others. This is explained by the fact that all learning algorithms have *some* prior knowledge inherent in them whether it be via the selection of certain hyper-parameters or the design of the algorithm itself. Another consequence of No Free Lunch is that there exists no single estimator that performs better for all problems.
