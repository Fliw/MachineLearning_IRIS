<?php
//Autoload Dependensi 
include __DIR__ . '/vendor/autoload.php';
//Menggunakan Dataset Yang Sudah Mempunyai Label
use Rubix\ML\Datasets\Labeled;
//Ekstrak Data Dari Format NDJSON
use Rubix\ML\Extractors\NDJSON;
//Tipe Algoritma Yang Akan Dipakai Untuk Learning
use Rubix\ML\Classifiers\KNearestNeighbors;
//Menggunakan Library Keakuratan Dalam Bentuk Metrik
use Rubix\ML\CrossValidation\Metrics\Accuracy;
//Load Dataset
echo 'Loading Datasets Ke Memori RAM ...' . PHP_EOL;
//Select Dataset
$training = Labeled::fromIterator(new NDJSON('dataset.ndjson'));
//Random Lebar Dan Panjang Bunga
$testing = $training->randomize()->take(10);
//Memanggil Learner yang akan dipakai dengan parameter 3
$estimator = new KNearestNeighbors(3);
echo 'Berlatih...' . PHP_EOL;
//memulai berlatih berdasarkan dataset
$estimator->train($training);
echo 'Membuat Prediksi...' . PHP_EOL;
//membuat prediksi
$predictions = $estimator->predict($testing);
echo 'Contoh Prediksi:' . PHP_EOL;
//contoh prediksi, dalam format array
print_r(array_slice($predictions, 0, 3));
//mengakuratkan
$metric = new Accuracy();
//menilai hasil dari prediksi dengan label yang di random tadi
$score = $metric->score($predictions, $testing->labels());
//output hasil keakuratan
echo "Keakuratan : $score" . PHP_EOL;
?>