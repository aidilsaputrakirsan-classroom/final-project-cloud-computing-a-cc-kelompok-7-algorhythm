{{-- resources/views/buku/index.blade.php --}}

{{-- Beritahu Blade untuk pakai layout utama --}}
@extends('layouts.app')

{{-- Set judul halaman (opsional) --}}
@section('title', 'Data Buku')

{{-- Ini adalah konten yang akan disuntikkan ke @yield('content') --}}
@section('content')
<div class="content-card">
    <div class="card-header">
        <h2>Data Buku</h2>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Data Buku
        </button>
    </div>

    <div class="table-controls">
        <div class="entries-select">
            Show 
            <select>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select> 
            entries per page
        </div>
        <div class="search-bar">
            Search: <input type="text" placeholder="">
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Cover Buku</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Rak</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" style="text-align: center;">No entries found</td>
            </tr>
            
            {{-- Nanti Anda bisa loop data dari controller di sini --}}
            {{-- @foreach ($books as $book)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" width="50"></td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->category->name }}</td>
                <td>{{ $book->rack->name }}</td>
                <td>{{ $book->stock }}</td>
                <td>...aksi</td>
            </tr>
            @endforeach --}}
        </tbody>
    </table>
</div>
@endsection