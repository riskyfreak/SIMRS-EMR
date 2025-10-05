CREATE TABLE pasien (
  no_rekam_medis VARCHAR(20) PRIMARY KEY,
  nik VARCHAR(16) UNIQUE,
  nama_pasien VARCHAR(100),
  tempat_lahir VARCHAR(50),
  tanggal_lahir DATE,
  jenis_kelamin ENUM('L', 'P'),
  golongan_darah ENUM('A', 'B', 'AB', 'O'),
  agama VARCHAR(20),
  status_perkawinan VARCHAR(15),
  pendidikan VARCHAR(30),
  pekerjaan VARCHAR(50),
  alamat TEXT,
  kd_kelurahan VARCHAR(10),
  kd_kecamatan VARCHAR(10),
  kd_kabupaten VARCHAR(10),
  kd_provinsi VARCHAR(10),
  no_telepon VARCHAR(15),
  no_kartu VARCHAR(30),
  nama_ibu VARCHAR(100),
  suku_bangsa INT,
  bahasa INT,
  nama_penanggung_jawab VARCHAR(100),
  hubungan_dengan_pasien VARCHAR(30),
  alamat_penanggung_jawab TEXT,
  kd_kelurahan_penanggung_jawab VARCHAR(10),
  kd_kecamatan_penanggung_jawab VARCHAR(10),
  kd_kabupaten_penanggung_jawab VARCHAR(10),
  kd_provinsi_penanggung_jawab VARCHAR(10),
  no_telepon_penanggung_jawab VARCHAR(15),
  email VARCHAR(100),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (suku_bangsa) REFERENCES suku_bangsa(id),
  FOREIGN KEY (bahasa) REFERENCES bahasa(id),
  FOREIGN KEY (kd_kelurahan) REFERENCES kelurahan(kd_kelurahan),
  FOREIGN KEY (kd_kecamatan) REFERENCES kecamatan(kd_kecamatan),
  FOREIGN KEY (kd_kabupaten) REFERENCES kabupaten(kd_kabupaten),
  FOREIGN KEY (kd_provinsi) REFERENCES provinsi(kd_provinsi),
  FOREIGN KEY (kd_kelurahan_penanggung_jawab) REFERENCES kelurahan(kd_kelurahan),
  FOREIGN KEY (kd_kecamatan_penanggung_jawab) REFERENCES kecamatan(kd_kecamatan),
  FOREIGN KEY (kd_kabupaten_penanggung_jawab) REFERENCES kabupaten(kd_kabupaten), 
  FOREIGN KEY (kd_provinsi_penanggung_jawab) REFERENCES provinsi(kd_provinsi)
);

CREATE TABLE provinsi (
  kd_provinsi VARCHAR(10) PRIMARY KEY,
  nama_provinsi VARCHAR(50)
);
CREATE TABLE kabupaten (
  kd_kabupaten VARCHAR(10) PRIMARY KEY,
  nama_kabupaten VARCHAR(50),
  kd_provinsi VARCHAR(10),
  FOREIGN KEY (kd_provinsi) REFERENCES provinsi(kd_provinsi)
);
CREATE TABLE kecamatan (
  kd_kecamatan VARCHAR(10) PRIMARY KEY,
  nama_kecamatan VARCHAR(50),
  kd_kabupaten VARCHAR(10),
  FOREIGN KEY (kd_kabupaten) REFERENCES kabupaten(kd_kabupaten)
);
CREATE TABLE kelurahan (
  kd_kelurahan VARCHAR(10) PRIMARY KEY,
  nama_kelurahan VARCHAR(50),
  kd_kecamatan VARCHAR(10),
  FOREIGN KEY (kd_kecamatan) REFERENCES kecamatan(kd_kecamatan)
);
CREATE TABLE suku_bangsa (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama_suku VARCHAR(50)
);
CREATE TABLE bahasa (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama_bahasa VARCHAR(50)
);

CREATE TABLE pegawai (
  nip VARCHAR(16) PRIMARY KEY,
  nama_pegawai VARCHAR(100),
  tempat_lahir VARCHAR(50),
  tanggal_lahir DATE,
  jenis_kelamin ENUM('L', 'P'),
  agama VARCHAR(20),
  status_perkawinan VARCHAR(15),
  pendidikan VARCHAR(30),
  jabatan VARCHAR(50),
  jenjang_jabatan VARCHAR(30),
  kelompok_jabatan VARCHAR(30),
  unit_kerja VARCHAR(50),
  bagian VARCHAR(50),
  resiko_kerja VARCHAR(30),
  tingkat_emergensi VARCHAR(30),
  status_wajib_pajak VARCHAR(30),
  status_pegawai VARCHAR(30),
  npwp VARCHAR(20),
  alamat TEXT,
  kd_kelurahan VARCHAR(10),
  kd_kecamatan VARCHAR(10),
  kd_kabupaten VARCHAR(10),
  kd_provinsi VARCHAR(10),
  tanggal_masuk DATE,
  waktu_kerja CHAR(20),
  no_telepon VARCHAR(15),
  email VARCHAR(100),
  foto VARCHAR(255),
  status_aktif BOOLEAN,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (kd_kelurahan) REFERENCES kelurahan(kd_kelurahan),
  FOREIGN KEY (kd_kecamatan) REFERENCES kecamatan(kd_kecamatan),
  FOREIGN KEY (kd_kabupaten) REFERENCES kabupaten(kd_kabupaten),
  FOREIGN KEY (kd_provinsi) REFERENCES provinsi(kd_provinsi)
);

CREATE TABLE user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  pegawai_nip VARCHAR(16),
  last_login TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (pegawai_nip) REFERENCES pegawai(nip)
);

CREATE TABLE registrasi_pasien (
  no_registrasi VARCHAR(20) PRIMARY KEY,
  no_rekam_medis VARCHAR(20),
  tanggal_registrasi TIMESTAMP,
  jam_registrasi TIME,
  nama_penjamin VARCHAR(100),
  hubungan_dengan_pasien VARCHAR(30),
  alamat_penjamin TEXT,
  kd_kelurahan_penjamin VARCHAR(10),
  kd_kecamatan_penjamin VARCHAR(10),
  kd_kabupaten_penjamin VARCHAR(10),
  kd_provinsi_penjamin VARCHAR(10),
  no_telepon_penjamin VARCHAR(15),
  kd_poli VARCHAR(50),
  kd_dokter VARCHAR(100),
  status_rawat ENUM('Belum', 'Sudah', 'Batal', 'Dirujuk', 'Dirawat', 'Meninggal', 'Pulang Paksa'),
  status_daftar ENUM('Baru', 'Lama'),
  status_kunjungan ENUM('Kunjungan Pertama', 'Kunjungan Ulang'),
  status_lanjut ENUM('Rawat Jalan', 'Rawat Inap', 'IGD'),
  cara_bayar ENUM('Umum', 'Asuransi', 'BPJS', 'Perusahaan', 'Lain-lain'),
  kd_penjamin VARCHAR(10),
  kd_penjamin_asal VARCHAR(10), // Optional, jika pasien bpjs tetapi COB dengan asuransi lain. Ada juga pasien Perusahaan yang di tanggung Asuransi
  no_kartu VARCHAR(30),
  umur_saat_pelayanan VARCHAR(20),
  petugas_input VARCHAR(16),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (no_rekam_medis) REFERENCES pasien(no_rekam_medis),
  FOREIGN KEY (kd_poli) REFERENCES poliklinik(kd_poli),
  FOREIGN KEY (kd_dokter) REFERENCES dokter(nip),
  FOREIGN KEY (kd_penjamin) REFERENCES penjamin_pasien(kd_penjamin),
  FOREIGN KEY (kd_penjamin_asal) REFERENCES penjamin_pasien(kd_penjamin),
  FOREIGN KEY (kd_kelurahan_penjamin) REFERENCES kelurahan(kd_kelurahan),
  FOREIGN KEY (kd_kecamatan_penjamin) REFERENCES kecamatan(kd_kecamatan),
  FOREIGN KEY (kd_kabupaten_penjamin) REFERENCES kabupaten(kd_kabupaten),
  FOREIGN KEY (kd_provinsi_penjamin) REFERENCES provinsi(kd_provinsi),
  FOREIGN KEY (petugas_input) REFERENCES pegawai(nip)
);

CREATE TABLE poliklinik (
  kd_poli VARCHAR(50) PRIMARY KEY,
  nama_poli VARCHAR(100),
  deskripsi TEXT,
  status_aktif BOOLEAN
);

CREATE TABLE dokter (
  nip VARCHAR(16) PRIMARY KEY,
  nama_dokter VARCHAR(100),
  kd_spesialis VARCHAR(50),
  lulusan_pendidikan VARCHAR(100),
  no_ijin_praktek VARCHAR(50),
  no_str VARCHAR(50),
  status_aktif BOOLEAN,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (kd_spesialis) REFERENCES spesialis(kd_spesialis)
);

CREATE TABLE spesialis (
  kd_spesialis VARCHAR(50) PRIMARY KEY,
  nama_spesialis VARCHAR(100),
  deskripsi TEXT
);

CREATE TABLE penjamin_pasien (
  kd_penjamin VARCHAR(10) PRIMARY KEY,
  nama_penjamin VARCHAR(100),
  alamat TEXT,
  no_telepon VARCHAR(15),
  email VARCHAR(100),
  jenis_penjamin ENUM('Umum', 'Asuransi', 'BPJS', 'Perusahaan', 'Lain-lain'),
  status_aktif BOOLEAN
);