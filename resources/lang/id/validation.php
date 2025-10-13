<?php

return [
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'integer' => ':attribute harus berupa angka.',
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'string' => ':attribute maksimal :max karakter.',
    ],
    'date' => ':attribute harus berupa tanggal yang valid.',
    'after_or_equal' => ':attribute harus setelah atau sama dengan :date.',
    'in' => ':attribute tidak valid.',
    'unique' => ':attribute sudah digunakan.',
    'exists' => ':attribute tidak ditemukan.',
    'email' => ':attribute harus berupa email yang valid.',
    'confirmed' => 'Konfirmasi :attribute tidak sama.',
    'file' => 'Berkas harus valid.',
    'mimes' => 'Berkas harus bertipe: :values.',
    'array' => ':attribute harus berupa daftar.',

    'attributes' => [
        'code' => 'kode',
        'name' => 'nama',
        'category' => 'kategori',
        'description' => 'deskripsi',
        'quantity_total' => 'jumlah stok',
        'status' => 'status',
        'asset_id' => 'aset',
        'borrower_name' => 'nama peminjam',
        'borrower_contact' => 'kontak peminjam',
        'unit' => 'unit kerja',
        'quantity' => 'jumlah',
        'loan_date' => 'tanggal pinjam',
        'return_date_planned' => 'rencana tanggal kembali',
        'return_date_actual' => 'tanggal kembali',
        'notes' => 'catatan',
        'file' => 'file',
    ],
];

