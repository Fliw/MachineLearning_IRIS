
# 🌸 Klasifikator Jenis Bunga IRIS 🌸  

## 📖 Deskripsi Proyek  

Proyek ini menggunakan dataset IRIS untuk mengklasifikasikan jenis bunga berdasarkan panjang dan lebar kelopak serta mahkota bunga. Algoritma yang digunakan adalah **K-Nearest Neighbors (KNN)**, salah satu algoritma pembelajaran mesin yang sederhana dan efektif.  

### Dataset IRIS  

Dataset ini mencakup 150 sampel, terbagi dalam tiga jenis bunga:  
- **Iris-Setosa**  
- **Iris-Versicolor**  
- **Iris-Virginica**  

Setiap sampel memiliki empat fitur utama:  
1. Panjang kelopak (sepal length)  
2. Lebar kelopak (sepal width)  
3. Panjang mahkota bunga (petal length)  
4. Lebar mahkota bunga (petal width)  

Dataset ini telah banyak digunakan dalam pembelajaran mesin untuk eksperimen klasifikasi sederhana.  

---

## 🚀 Fitur Utama  

- **Pengklasifikasian Jenis Bunga**: Menggunakan data panjang dan lebar untuk memprediksi jenis bunga.  
- **Implementasi Algoritma KNN**: Model berbasis tetangga terdekat yang mudah dipahami dan diterapkan.  
- **Akurasi Tinggi**: Proses pelatihan dan prediksi dilakukan secara efisien dengan hasil yang andal.  

---

## 📋 Spesifikasi Proyek  

- **Tingkat Kesulitan**: Mudah  
- **Waktu Pelatihan**: < 1 Detik  
- **Penggunaan Memori**: < 1 GiB  

---

## 🛠️ Persyaratan  

1. **PHP**  
   - Versi 7.2 atau lebih tinggi.  
2. **Composer**  
   - Digunakan untuk instalasi library.  
3. **Library Pembelajaran Mesin**  
   - PHP-ML untuk algoritma KNN.  

---

## ⚙️ Instalasi  

### 1. Kloning Repositori  

Gunakan perintah berikut untuk menyalin proyek secara lokal:  

```bash  
git clone https://github.com/Fliw/MachineLearning_IRIS  
cd MachineLearning_IRIS  
```  

### 2. Instal Dependensi  

Jalankan perintah berikut untuk mengunduh library yang diperlukan:  

```bash  
composer install  
```  

---

## 🧪 Pengujian  

1. Jalankan file `train.php` dengan perintah berikut:  

   ```bash  
   php train.php  
   ```  

2. Hasil prediksi akan ditampilkan di terminal bersama tingkat akurasi model.  

---

## 📚 Referensi  

1. [Rubix ML - Dataset IRIS](https://www.rubixml.com)  
2. [PHP-ML Documentation - K-Nearest Neighbors](https://php-ml.readthedocs.io/en/latest/machine-learning/classification/k-nearest-neighbors/)  

---

## 🏆 Lisensi  

Proyek ini dilisensikan di bawah [MIT License](LICENSE).  
