<span style="float:right;"><a href="https://github.com/RubixML/RubixML/blob/master/src/Datasets/Unlabeled.php">[source]</a></span>

# Unlabeled
Unlabeled datasets are used to train unsupervised learners and for feeding unknown samples into an estimator to make predictions. As their name implies, they do not require a corresponding label for each sample.

## Parameters
| # | Param | Default | Type | Description |
|---|---|---|---|---|
| 1 | samples | | array | A 2-dimensional array consisting of rows of samples and columns with feature values. |
| 2 | validate | true | bool | Should we validate the input? |

## Additional Methods
This dataset does not have any additional methods.

## Example

```php
use Rubix\ML\Datasets\Unlabeled;

$samples = [
    [0.1, 20, 'furry'],
    [2.0, -5, 'rough'],
    [0.001, -10, 'rough'],
];

$dataset = new Unlabeled($samples);
```