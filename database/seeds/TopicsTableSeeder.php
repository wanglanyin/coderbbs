<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //所有用户ID [1,2,3]
        $user_ids = \App\Models\User::all()->pluck('id')->toArray();

        // 所有分类 ID 数组，如：[1,2,3,4]
        $category_ids = \App\Models\Category::all()->pluck('id')->toArray();

        $faker = app(Faker\Generator::class);

        $topics = factory(\App\Models\Topic::class)
            ->times(100)
            ->make()
            ->each(function($topic,$index) use ($user_ids, $category_ids, $faker) {
                $topic->user_id = $faker->randomElement($user_ids);
                $topic->category_id = $faker->randomElement($category_ids);
            });

        \App\Models\Topic::insert($topics->toArray());
    }
}
