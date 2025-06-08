<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BookCategoriesRepositoryInterface;
use App\Models\BookCategories;

class BookCategoriesRepository extends BaseRepository implements BookCategoriesRepositoryInterface
{

    public function __construct()
    {
        $this->model = app(BookCategories::class);
    }

    /**
     * @param int $bookId
     * @param array $categoryIds
     * @return bool|null
     */
    public function deleteBookCategoriesByBookId(int $bookId, array $categoryIds = []): bool|null
    {
        return $this->model->query()->whereNotIn("category_id", $categoryIds)
            ->where('book_id', $bookId)->delete();
    }

    /**
     *
     *
     * @param int $bookId
     * @param array $categoryIds
     * @return void
     */
    public function createBookCategoriesByBookId(int $bookId, array $categoryIds = []): void
    {
        collect($categoryIds)->chunk(100)->each(function ($categories) use ($bookId) {
            foreach ($categories as $categoryId) {
                $this->model->updateOrCreate([
                    'book_id' => $bookId,
                    'category_id' => $categoryId
                ], [
                    'book_id' => $bookId,
                    'category_id' => $categoryId
                ]);
            }
        });
    }
}
