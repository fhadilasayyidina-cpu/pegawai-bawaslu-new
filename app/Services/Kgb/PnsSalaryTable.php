<?php

namespace App\Services\Kgb;

class PnsSalaryTable
{
    private const COMPRESSED_SALARY_DATA = 'H4sIAAAAAAAEAE2WSZIeOQiF7/KvHdFiEsg36Av0vj2cwuG7m0Eg16akqkzyfeIB+vX595//P19/fdbnK2wTXevLB32tZBZrjvUhifX2tfkq1ubrs4xjDfHy2Xfjb+OiVRuOzWHKjb+PsFdtPAAiWm5wxeZYfgUjAOk6uYkAzFL/iQCCuNf6/cWFfwvhFKJ4pViJ9SnhGprEdqxPqMD7YYiNUYYHCkl88imQUOHkudHYmEJuIgDJyQ1GACZKQIwAAqv+EwHE7mMRYOvSK/Z7iz1g2mKP1sPx7GKQFgtLbMSCIoxY5CUjlgBgxFIzRQB2GSNWaD+xGzaN2H1KS4pVoxb7Y8T6zxWLa+NpseBPj9jDTyzGp1oskdCI9RzZiGVjHbFyA6fYzaQjVl3hiDVAG7FnAZfYZ2AMI2TUTGT5MQ/NDJuCrgFTBfH4QwCeP+Rc/+aJKa6hUBc7FBbGbopDKE1BC/k0BQE+f1DrCgoiOIXkAYix6oQigBDmdygC+JkMbJve8y2roRxjfCRxfhdqg8pAxZ8HSu/bCWWKOlBnEzYULdkPCuQmIDR5IT4oEpaBcjE4ULIPDtQ22AOlJ7QVVBeH+0LGb7IZG2qzDJTerpFQBvVQQp1VAhPqdNsJTe69PVDexGyg0CqhCUV2dKC8LbxM7XUPIgLoTWhCedXQQHnHwYbqInI3VZlKmUkbyr00RWTX9gl1iAbKrSTTnsJKZ6C88l6m/I09UOzZGShxmw3U5jqVhNJuaRHAtLySUF72kymGVJBQfxWbvmnhBqoJwemfKsKd9qmzsXQP7Z4W4Z7T08LdIzMt3D23p8X7/gXoaeHuuQUWAdTOTAvylgo9Lbj7aEwL9plWqfYAAV5p8wB+DDVUyAOwqOAQfmvCs0oxppvKgJxmOtSEYHs3IVpVXhJ6M5Yh5HOnCqeVblHutBI8QqM3D91Kld0g5B5YSQj4FyGxwBCyVatKwo3loiTUPIhL+P0SureuxdNaoE0YvacJ3WbchByztwmF6OXQCxSHUG87SkLTmutBGM7YTegcdXZJ6B7bQ+iBdQi965wh3HpbSAawkpyEXrkyhD+aEISHsFpjEdLt47ssd5pQYuI34TaaO433q/NceuAMoTsOHyHsmphJSAteDpm2DqHoPfkI4P1EhtC6Y3oAWYjjUgHr28R/U4ZhOmtAl8UNyMcGcN+LmqXh8AG6q3AAz+3bkFVkNcASEIEeoB8XDiDb7S2Yhru9JQKoloUS8OAdCRZMKg8QqeoqASlvGwXYVRieOw0oxH0rdce9DOqGvpW636ozBKBTl10C0P2mOoAod5NF5B1kAP3OAgPo/doG0OC8KjwK41GBvskGoPvVBpClEp2AOx8rwClCt9wA+iV6MugxuAHPnThZQ/waKcO9KCeg1/O0GbdbGSMB5Tb3BNRb6QnoPYsb0O1G1oBuN5wM+sHLy6AATRH6DNNpM37f3tyAU4OKdU0KQDciNuDZ0Bl0v0nXoNuNHyDxbZ1ZQsYPcKNMH3W7VawEPLdlYZZQVxqm3fZY1IfOrc4A9CzwACrvl8Fz77oB6MOJpQF/NqC31m4y/hu7ybjh6nqdNSS1zhI6d/qttBtMDbrdbk+N1w3fKKwJcgHdbmfaqE/S12S8DevLoN+rn0WzeTTgsXJyAPolsS5HCei3mRiFv/8AeHLczhoOAAA=';

    /** Tabel gaji dari file KGB PNS.xlsx, disimpan agar tidak bergantung pada ekstensi PHP zip. */
    private const SALARY_DATA = 'eyJJL2EiOnsiMCI6MTY4NTcwMCwiMiI6MTczODgwMCwiNCI6MTc5MzUwMCwiNiI6MTg1MDAwMCwiOCI6MTkwODQwMCwiMTAiOjE5Njg0MDAsIjEyIjoyMDMwNDAwLCIxNCI6MjA5NDMwMCwiMTYiOjIxNjAzMDAsIjE4IjoyMjI4MzAwLCIyMCI6MjI5ODUwMCwiMjIiOjIzNzA5MDAsIjI0IjoyNDQ1NTAwLCIyNiI6MjUyMjYwMH0sIkkvYiI6eyIzIjoxODQwODAwLCI1IjoxODk4ODAwLCI3IjoxOTU4NjAwLCI5IjoyMDIwMzAwLCIxMSI6MjA4MzkwMCwiMTMiOjIxNDk2MDAsIjE1IjoyMjE3MzAwLCIxNyI6MjI4NzEwMCwiMTkiOjIzNTkxMDAsIjIxIjoyNDMzNDAwLCIyMyI6MjUxMDEwMCwiMjUiOjI1ODkxMDAsIjI3IjoyNjcwNzAwfSwiSS9jIjp7IjMiOjE5MTg3MDAsIjUiOjE5NzkxMDAsIjciOjIwNDE1MDAsIjkiOjIxMDU4MDAsIjExIjoyMTcyMTAwLCIxMyI6MjI0MDUwMCwiMTUiOjIzMTExMDAsIjE3IjoyMzgzOTAwLCIxOSI6MjQ1ODkwMCwiMjEiOjI1MzY0MDAsIjIzIjoyNjE2MzAwLCIyNSI6MjY5ODcwMCwiMjciOjI3ODM3MDB9LCJJL2QiOnsiMyI6MTk5OTkwMCwiNSI6MjA2MjkwMCwiNyI6MjEyNzgwMCwiOSI6MjE5NDgwMCwiMTEiOjIyNjQwMDAsIjEzIjoyMzM1MzAwLCIxNSI6MjQwODgwMCwiMTciOjI0ODQ3MDAsIjE5IjoyNTYyOTAwLCIyMSI6MjY0MzcwMCwiMjMiOjI3MjY5MDAsIjI1IjoyODEyODAwLCIyNyI6MjkwMTQwMH0sIklJL2EiOnsiMCI6MjE4NDAwMCwiMSI6MjIxODQwMCwiMyI6MjI4ODIwMCwiNSI6MjM2MDMwMCwiNyI6MjQzNDYwMCwiOSI6MjUxMTMwMCwiMTEiOjI1OTA0MDAsIjEzIjoyNjcyMDAwLCIxNSI6Mjc1NjIwMCwiMTciOjI4NDMwMDAsIjE5IjoyOTMyNTAwLCIyMSI6MzAyNDkwMCwiMjMiOjMxMjAxMDAsIjI1IjozMjE4NDAwLCIyNyI6MzMxOTgwMCwiMjkiOjM0MjQzMDAsIjMxIjozNTMyMjAwLCIzMyI6MzY0MzQwMH0sIklJL2IiOnsiMyI6MjM4NTAwMCwiNSI6MjQ2MDEwMCwiNyI6MjUzNzAwMCwiOSI6MjYxNzUwMCwiMTEiOjI3MDAwMDAsIjEzIjoyNzg1MDAwLCIxNSI6Mjg3MjcwMCwiMTciOjI5NjMyMDAsIjE5IjozMDU2NTAwLCIyMSI6MzE1MjgwMCwiMjMiOjMyNTIxMDAsIjI1IjozMzU0NTAwLCIyNyI6MzQ2MDIwMCwiMjkiOjM1NjkyMDAsIjMxIjozNjgxNjAwLCIzMyI6Mzc5NzUwMH0sIklJL2MiOnsiMyI6MjQ4NTkwMCwiNSI6MjU2NDIwMCwiNyI6MjY0NTAwMCwiOSI6MjcyODMwMCwiMTEiOjI4MTQyMDAsIjEzIjoyOTAyODAwLCIxNSI6Mjk5NDMwMCwiMTciOjMwODg2MDAsIjE5IjozMTg1ODAwLCIyMSI6MzI4NjIwMCwiMjMiOjMzODk3MDAsIjI1IjozNDk2NDAwLCIyNyI6MzYwNjUwMCwiMjkiOjM3MjAxMDAsIjMxIjozODM3MzAwLCIzMyI6Mzk1ODIwMH0sIklJL2QiOnsiMyI6MjU5MTEwMCwiNSI6MjY3MjcwMCwiNyI6Mjc1NjgwMCwiOSI6Mjg0MzcwMCwiMTEiOjI5MzMyMDAsIjEzIjoyMDI1NjAwLCIxNSI6MzEyMDkwMCwiMTciOjMyMTkyMDAsIjE5IjozMzIwNjAwLCIyMSI6MzQyNTIwMCwiMjMiOjM1MzMxMDAsIjI1IjozNjQ0MzAwLCIyNyI6Mzc1OTEwMCwiMjkiOjM4Nzc1MDAsIjMxIjozOTk5NjAwLCIzMyI6NDEyNTYwMH0sIklJSS9hIjp7IjAiOjI3ODU3MDAsIjIiOjI4NzM1MDAsIjQiOjI5NjQwMDAsIjYiOjMwNTczMDAsIjgiOjMxNTM2MDAsIjEwIjozMjUyOTAwLCIxMiI6MzM1NTQwMCwiMTQiOjM0NjExMDAsIjE2IjozNTcwMTAwLCIxOCI6MzY4MjUwMCwiMjAiOjM3ODk1MDAsIjIyIjozOTE4MTAwLCIyNCI6NDA0MTUwMCwiMjYiOjQxNjg4MDAsIjI4Ijo0MzAwMTAwLCIzMCI6NDQzNTUwMCwiMzIiOjQ1NzUyMDB9LCJJSUkvYiI6eyIwIjoyOTAzNjAwLCIyIjoyOTk1MDAwLCI0IjozMDg5MzAwLCI2IjozMTg2NjAwLCI4IjozMjg3MDAwLCIxMCI6MzM5MDUwMCwiMTIiOjM0OTczMDAsIjE0IjozNjA3NTAwLCIxNiI6MzcyMTEwMCwiMTgiOjM4MzgzMDAsIjIwIjozOTU5MjAwLCIyMiI6NDA4MzkwMCwiMjQiOjQxMjE1MDAsIjI2Ijo0MzQ1MTAwLCIyOCI6NDQ4MjAwMCwiMzAiOjQ2MjMyMDAsIjMyIjo0NzY4ODAwfSwiSUlJL2MiOnsiMCI6MzAyNjQwMCwiMiI6MzEyMTcwMCwiNCI6MzIyMDAwMCwiNiI6MzMyMTQwMCwiOCI6MzQyNjAwLCIxMCI6MzUzMzkwMCwiMTIiOjM2NDUyMDAsIjE0IjozNzYwMTAwLCIxNiI6Mzg3ODUwMCwiMTgiOjQwMDA2MDAsIjIwIjo0MTI2NjAwLCIyMiI6NDI1NjYwMCwiMjQiOjQzOTA3MDAsIjI2Ijo0NTI4OTAwLCIyOCI6NDY3MTYwMCwiMzAiOjQxODE3MDAsIjMyIjo0OTcwNTAwfSwiSUlJL2QiOnsiMCI6MzE1NDQwMCwiMiI6MzI1MzcwMCwiNCI6MzM1NjIwMCwiNiI6MzQ2MTkwMCwiOCI6MzU3MTAwMCwiMTAiOjM2ODM0MDAsIjEyIjozNzk5NDAwLCIxNCI6MzkxOTEwMCwiMTYiOjQwNDI1MDAsIjE4Ijo0MTY5OTAwLCIyMCI6NDMwMTIwMCwiMjIiOjQ0MzY3MDAsIjI0Ijo0NTc2NDAwLCIyNiI6NDcyMDUwMCwiMjgiOjQ4NjkyMDAsIjMwIjo1MDIyNTAwLCIzMiI6NTE4MDcwMH0sIklWL2EiOnsiMCI6MzI4NzgwMCwiMiI6MzM5MTQwMCwiNCI6MzQ5ODIwMCwiNiI6MzYwODQwMCwiOCI6MzcyMjAwLCIxMCI6MzgzOTIwMCwiMTIiOjM5NjAyMDAsIjE0Ijo0MDg0OTAwLCIxNiI6NDIxMzUwMCwiMTgiOjQzNDYyMDAsIjIwIjo0NDgzMTAwLCIyMiI6NDYyNDMwMCwiMjQiOjQ3NzAwMDAsIjI2Ijo0OTIwMjAwLCIyOCI6NTA3NTIwMCwiMzAiOjUyMzUwMDAsIjMyIjo1Mzk5OTAwfSwiSVYvYiI6eyIwIjozNDI2OTAwLCIyIjozNTM0ODAwLCI0IjozNjQ2MjAwLCI2IjozNzYxMDAwLCI4IjozODc5NTAwLCIxMCI6NDAwMTYwMCwiMTIiOjQxMjc3MDAsIjE0Ijo0MjU3NzAwLCIxNiI6NDM5MTgwMCwiMTgiOjQ1MzAxMDAsIjIwIjo0NjcyODAwLCIyMiI6NDgxOTkwMCwiMjQiOjQ5NzE3MDAsIjI2Ijo1MTI4MzAwLCIyOCI6NTI4OTgwMCwiMzAiOjU0NTY0MDAsIjMyIjo1NjI4MzAwfSwiSVYvYyI6eyIwIjozNTcxOTAwLCIyIjozNjg0NDAwLCI0IjozODAwNDAwLCI2IjozOTIwMTAwLCI4Ijo0MDQzNjAwLCIxMCI6NDE3MDkwMCwiMTIiOjQzMDIzMDAsIjE0Ijo0NDM3ODAwLCIxNiI6NDU3NzUwMCwiMTgiOjQ3MjE3MDAsIjIwIjo0ODcwNDAwLCIyMiI6NTAyMzgwMCwiMjQiOjUxODIwMDAsIjI2Ijo1MzQ1MjAwLCIyOCI6NTUxMzYwMCwiMzAiOjU2ODcyMDAsIjMyIjo1ODY2NDAwfSwiSVYvZCI6eyIwIjozNzIzMDAwLCIyIjozODQwMjAwLCI0IjozOTYxMjAwLCI2Ijo0MDg1OTAwLCI4Ijo0MjE0NjAwLCIxMCI6NDM0NzMwMCwiMTIiOjQ0ODQzMDAsIjE0Ijo0NjI1NTAwLCIxNiI6NDc3MTIwMCwiMTgiOjQ5MjE0MDAsIjIwIjo1MDc2NDAwLCIyMiI6NTIzNjMwMCwiMjQiOjU0MDEyMDAsIjI2Ijo1NTcxNDAwLCIyOCI6NTc0NjgwMCwiMzAiOjU5Mjc4MDAsIjMyIjo2MTE0NTAwfSwiSVYvZSI6eyIwIjozODgwNDAwLCIyIjo0MDAyNzAwLCI0Ijo0MTI4NzAwLCI2Ijo0MjU4NzAwLCI4Ijo0MzkyOTAwLCIxMCI6NDUzMTIwMCwiMTIiOjQ2NzM5MDAsIjE0Ijo0ODIxMTAwLCIxNiI6NDk3MzAwMCwiMTgiOjUxMjk2MDAsIjIwIjo1MjkxMjAwLCIyMiI6NjQ1NzgwMCwiMjQiOjU2Mjk3MDAsIjI2Ijo1ODA3MDAwLCIyOCI6NTk4OTkwMCwiMzAiOjYxNzg2MDAsIjMyIjo2MzczMjAwfQ==';

    /** @var array<string, array<int, int>>|null */
    private ?array $salaries = null;

    /**
     * @return array<int, array{id: string, name: string}>
     */
    public function golonganOptions(): array
    {
        return array_map(fn (string $golongan) => [
            'id' => $golongan,
            'name' => $golongan,
        ], array_keys($this->salaries()));
    }

    /**
     * @return array<int, array{id: string, name: string}>
     */
    public function masaKerjaOptions(?string $golongan): array
    {
        $salaryByMasaKerja = $this->salaries()[self::normalizeGolongan($golongan)] ?? [];

        $tahunList = array_keys($salaryByMasaKerja);
        sort($tahunList);

        return array_map(fn (int $tahun) => [
            'id' => $this->formatMasaKerja($tahun),
            'name' => $this->formatMasaKerja($tahun),
        ], $tahunList);
    }

    public function salary(?string $golongan, string|int|null $masaKerja): ?int
    {
        if (! preg_match('/^\s*(\d+)/', (string) $masaKerja, $matches)) {
            return null;
        }

        $tahun = (int) $matches[1];

        return $this->salaries()[self::normalizeGolongan($golongan)][$tahun] ?? null;
    }

    public function formatMasaKerja(int $tahun): string
    {
        return "{$tahun} Tahun 0 Bulan";
    }

    private function salaries(): array
    {
        if ($this->salaries !== null) {
            return $this->salaries;
        }

        $this->salaries = json_decode(gzdecode(base64_decode(self::COMPRESSED_SALARY_DATA)), true, 512, JSON_THROW_ON_ERROR);

        return $this->salaries;
    }

    private static function normalizeGolongan(?string $golongan): string
    {
        $golongan = strtoupper(trim((string) $golongan));

        if (preg_match('/\b(IV|III|II|I)\s*[\/.-]?\s*([A-E])\b/', $golongan, $matches)) {
            return $matches[1].'/'.strtolower($matches[2]);
        }

        return '';
    }
}
