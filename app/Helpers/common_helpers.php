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
