<?php

namespace App\Enums;

enum RegistrationStatus: string {
    case DAFTAR = 'DAFTAR';
    case TERVERIFIKASI = 'TERVERIFIKASI';
    case DITOLAK = 'DITOLAK';
}