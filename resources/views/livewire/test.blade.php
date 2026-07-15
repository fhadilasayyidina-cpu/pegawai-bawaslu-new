<?php

use function Livewire\Volt\{state};

// --- LOGIKA PHP (VOLT) ---
state([
    'search' => '', 
    'operators' => [
        ['nama' => 'Budi Sudarsono', 'daerah' => 'Jakarta Pusat', 'status' => 'Aktif'],
        ['nama' => 'Siti Aminah', 'daerah' => 'Surabaya', 'status' => 'Non-Aktif'],
        ['nama' => 'Andi Wijaya', 'daerah' => 'Makassar', 'status' => 'Aktif'],
        ['nama' => 'Riza Fachri', 'daerah' => 'Medan', 'status' => 'Aktif'],
    ]
]);

// Fungsi simpel untuk filter data di tampilan (opsional)
$filteredOperators = fn() => empty($search) 
    ? $operators 
    : array_filter($operators, fn($op) => str_contains(strtolower($op['nama']), strtolower($search)));

?>

<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Testing Dashboard Bawaslu - Flowbite') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div id="alert-1" class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <div class="ms-3 text-sm font-medium">
                    Status: Kamu sedang mencari operator: <span class="font-bold underline">{{ $search ?: 'Semua' }}</span>
                </div>
            </div>

            <div class="mb-6">
                <label for="search" class="block mb-2 text-sm font-medium text-gray-900">Cari Nama Operator</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input wire:model.live="search" type="search" id="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Budi...">
                </div>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama Operator</th>
                            <th scope="col" class="px-6 py-3">Daerah Kerja</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredOperators() as $op)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap italic">
                                {{ $op['nama'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $op['daerah'] }}
                            </td>
                            <td class="px-6 py-4">
                                @if($op['status'] == 'Aktif')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full border border-green-400">Aktif</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full border border-red-400">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="font-medium text-blue-600 hover:underline">Edit</button>
                            </td>
                        </tr>
                        @empty
                        <tr class="bg-white border-b">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Data operator tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                    + Tambah Operator Baru
                </button>
            </div>

        </div>
    </div>
</x-layouts.app>