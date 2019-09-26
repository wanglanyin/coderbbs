<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = app(Faker\Generator::class);
        $url = config('app.qiniu_url');
        $avatars = [
            $url .'4d7658c867b7557d5bec7befbb7b53dd.jpeg',
            $url .'4e3841eadad8ac01bde6b3fc2b1808fa.jpeg',
            $url .'407f4e3d321bc51b24ee89ed14d96007.jpeg',
            $url .'bb987e7abf12059ea63fe2b298791e04.jpeg',
            $url .'fb18af1a60c3dcda409610f3a8bdffb6.jpeg',
            $url .'cb6e75b3e4b07ce160c03d6cfb5cde52.jpeg',
        ];
        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index)
            use ($faker, $avatars)
            {
                // 从头像数组中随机取出一个并赋值
                $user->avatar = $faker->randomElement($avatars);
            });

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Ly_ii';
        $user->email = 'wlany@qq.com';
        //$user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();

    }
}
