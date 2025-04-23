<?php

namespace Database\Seeders;

use App\Models\SubProcess;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserHasSubProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $subProcesses = SubProcess::pluck('id');

        $users = User::pluck('id');

        foreach ($users as $user) {
            foreach ($subProcesses as $subProcess) {
                $data[] = [
                    'user_id' => $user,
                    'sub_process_id' => $subProcess,
                ];
            }
        }

        DB::table('user_has_sub_process')->insert($data);
    }
}
