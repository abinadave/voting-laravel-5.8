<?php

use Illuminate\Database\Seeder;
use App\User;
use Hash as Hash;
class UserPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = User::where('province_id', 6)->pluck('id')->all();
        foreach ($ids as $id) {
            $user = User::findOrFail($id);
            $hashed_password = Hash::make($user->username);
            DB::table('users')
            ->where('id', $id)
            ->where('province_id', 6)
            ->update(['password' => $hashed_password]);

            // DB::table('added_roles')->insert([
            //     'user_id' => $id,
            //     'role_id' => 3,
            //     'granted_by' => 3,
            // ]);
        }
    }
}
