<?php

if (! function_exists('terbilang')) {
    function terbilang($number)
    {
        $ones = [
            '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan',
            'Sepuluh', 'Sebelas', 'Dua Belas', 'Tiga Belas', 'Empat Belas', 'Lima Belas',
            'Enam Belas', 'Tujuh Belas', 'Delapan Belas', 'Sembilan Belas',
        ];

        if ($number < 20) {
            return $ones[$number];
        }

        if ($number < 100) {
            if ($number < 30) {
                return 'Dua Puluh '.$ones[$number % 10];
            }
            if ($number < 40) {
                return 'Tiga Puluh '.$ones[$number % 10];
            }
            if ($number < 50) {
                return 'Empat Puluh '.$ones[$number % 10];
            }
            if ($number < 60) {
                return 'Lima Puluh '.$ones[$number % 10];
            }
            if ($number < 70) {
                return 'Enam Puluh '.$ones[$number % 10];
            }
            if ($number < 80) {
                return 'Tujuh Puluh '.$ones[$number % 10];
            }
            if ($number < 90) {
                return 'Delapan Puluh '.$ones[$number % 10];
            }
            if ($number < 100) {
                return 'Sembilan Puluh '.$ones[$number % 10];
            }
        }

        if ($number < 200) {
            return 'Seratus '.terbilang($number - 100);
        }

        if ($number < 1000) {
            return $ones[$number / 100].' Ratus '.terbilang($number % 100);
        }

        if ($number < 2000) {
            return 'Seribu '.terbilang($number - 1000);
        }

        if ($number < 1000000) {
            return terbilang($number / 1000).' Ribu '.terbilang($number % 1000);
        }

        if ($number < 1000000000) {
            return terbilang($number / 1000000).' Juta '.terbilang($number % 1000000);
        }

        return terbilang($number / 1000000000).' Milyar '.terbilang($number % 1000000000);
    }
}
