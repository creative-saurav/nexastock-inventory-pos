<?php

if (!function_exists('get_phrase')) {

    function get_phrase(string $text): string
    {
        return $text;
    }
}

if (!function_exists('unique_slug')) {

    function unique_slug(string $model, string $name, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($name);

        $slug = $baseSlug;

        $counter = 1;

        while (
            $model::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {

            $slug = $baseSlug . '-' . $counter;

            $counter++;
        }

        return $slug;
    }
}

if (!function_exists('generate_document_no')) {

    /**
     * Generate a gap-free, daily-reset document number, e.g. PUR-20261005-0001.
     *
     * The counter row is locked until the outer transaction commits, so
     * concurrent requests can't get the same number, and a rollback also
     * rolls back the counter (no skipped numbers).
     */
    function generate_document_no(string $prefix, int $padLength = 4): string
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($prefix, $padLength) {

            $key = $prefix . '-' . now()->format('Ymd');

            \Illuminate\Support\Facades\DB::table('document_sequences')->insertOrIgnore([
                'key' => $key,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sequence = \Illuminate\Support\Facades\DB::table('document_sequences')
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            $next = $sequence->last_number + 1;

            \Illuminate\Support\Facades\DB::table('document_sequences')
                ->where('key', $key)
                ->update([
                    'last_number' => $next,
                    'updated_at' => now(),
                ]);

            return $key . '-' . str_pad($next, $padLength, '0', STR_PAD_LEFT);
        });
    }
}

if (!function_exists('amount_in_words')) {

    /**
     * Convert an amount to words using the Bangladeshi numbering system,
     * e.g. 125050.50 => "One Lakh Twenty Five Thousand Fifty Taka and Fifty Poisha Only".
     */
    function amount_in_words(float $amount): string
    {
        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen',
        ];

        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $twoDigits = function (int $number) use ($ones, $tens): string {
            if ($number < 20) {
                return $ones[$number];
            }

            return trim($tens[intdiv($number, 10)] . ' ' . $ones[$number % 10]);
        };

        $convert = function (int $number) use (&$convert, $ones, $twoDigits): string {
            if ($number === 0) {
                return '';
            }

            $units = [
                10000000 => 'Crore',
                100000   => 'Lakh',
                1000     => 'Thousand',
                100      => 'Hundred',
            ];

            foreach ($units as $value => $name) {
                if ($number >= $value) {
                    $head = intdiv($number, $value);
                    $headWords = $value === 10000000 ? $convert($head) : ($value === 100 ? $ones[$head] : $twoDigits($head));

                    return trim($headWords . ' ' . $name . ' ' . $convert($number % $value));
                }
            }

            return $twoDigits($number);
        };

        $amount = round($amount, 2);
        $taka = (int) floor($amount);
        $poisha = (int) round(($amount - $taka) * 100);

        $words = ($taka > 0 ? $convert($taka) : 'Zero') . ' Taka';

        if ($poisha > 0) {
            $words .= ' and ' . $twoDigits($poisha) . ' Poisha';
        }

        return $words . ' Only';
    }
}

if (!function_exists('setting')) {

    /**
     * Read a shop setting saved from the Settings page.
     * Falls back to config/shop.php only when the setting was never saved,
     * so a field cleared on purpose stays empty.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            $settings = \App\Models\Setting::allCached();
        } catch (\Throwable) {
            // settings table not migrated yet
            $settings = [];
        }

        if (! array_key_exists($key, $settings) || $settings[$key] === null) {
            return $default ?? config('shop.' . $key);
        }

        return $settings[$key];
    }
}
