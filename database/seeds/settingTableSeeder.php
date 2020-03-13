<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class settingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
      $records = array([
        'setting_key' => "session_key_admin",
        'setting_value' => "iamtigeradmin"
      ],
      [
        'setting_key' => "session_key_front",
        'setting_value' => "iamtigerfront"
      ],
      [
        'setting_key' => "app_logo",
        'setting_value' => "#"
      ],
      [
        'setting_key' => "passwordencrypt",
        'setting_value' => "df455fghhjdd45ythhsdd4fgffgf7gfgfh45rgfgsd34sdsffgf4"
      ],
      [
        'setting_key' => "app_name",
        'setting_value' => "Take and Make"
      ],
      [
        'setting_key' => "app_owner",
        'setting_value' => "Take and Make"
      ],
      [
        'setting_key' => "enctryptkey",
        'setting_value' => "dsrt54tryhsde456ydfgdfde"
      ],
      [
        'setting_key' => "support_email",
        'setting_value' => "support@gmail.com"
      ],
      [
        'setting_key' => "support_contact",
        'setting_value' => "+1-541-754-3010"
      ],
      [
        'setting_key' => "smtp_host",
        'setting_value' => "ssl://smtp.googlemail.com"
      ],
      [
        'setting_key' => "smtp_port",
        'setting_value' => "465"
      ],
      [
        'setting_key' => "smtp_user",
        'setting_value' => "paragkadiya1294@gmail.com"
      ],
      [
        'setting_key' => "smtp_password",
        'setting_value' => "#"
      ],
      [
        'setting_key' => "copyrights",
        'setting_value' => ""
      ],
      [
        'setting_key' => "tandc",
        'setting_value' => ""
      ],
      [
        'setting_key' => "pandp",
        'setting_value' => ""
      ]);

      foreach($records as $row) {
        DB::table('setting')->insert($row);
      }

    }
}
