<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case OPERATOR = 'operator';
    case PEGAWAI = 'pegawai';

    // Opsional: Buat label untuk tampilan di UI/Dropdown

}
