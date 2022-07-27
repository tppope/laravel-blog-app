<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{

    public function __construct(
        public string $fileName,
        public string $title,
        public string $excerpt,
        public string $date,
        public string $body
    )
    {

    }

    public static function find($post): Post
    {
//        $file = YamlFrontMatter::parse(
//            cache()->remember(
//                "posts.{$post}",
//                60,
//                fn() => file_get_contents(resource_path("posts/$post.html")))
//        );
//
//        return new Post(
//            $file->slug,
//            $file->title,
//            $file->excerpt,
//            $file->date,
//            $file->body()
//        );

        return static::all()->firstWhere('fileName', $post);
    }

    public static function all(): Collection
    {
        return cache()->rememberForever('post.all', function () {
            return collect(File::files(resource_path("posts/")))
                ->map(fn($file) => YamlFrontMatter::parseFile($file))
                ->map(fn($document) => new self(
                    $document->slug,
                    $document->title,
                    $document->excerpt,
                    $document->date,
                    $document->body()
                ))
                ->sortByDesc('date');
        });
    }

    public static function findOrFail($post): Post
    {
        if (!file_exists(resource_path("posts/$post.html"))) {
            throw new ModelNotFoundException();
        }

        return static::find($post);
    }
}
