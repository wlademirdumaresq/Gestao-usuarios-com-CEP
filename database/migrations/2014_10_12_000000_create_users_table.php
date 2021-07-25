<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date');
            $table->string('tel', 11);
            $table->string('picture')->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('CPF', 11)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('district');
            $table->string('city');
            $table->string('state');
            $table->string('complement')->nullable();
            $table->string('CEP', 8);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Insert some stuff
        DB::table('users')->insert(
            array(
                'name' => 'Administrador',
                'birth_date' => '1998-09-21',
                'tel' => '84111111111',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'picture' => 'picture-none.png',
                'CPF' => '11111111111',
                'user_id' => null,
                'password' => Hash::make('admin')
            )
        );
        // Insert some stuff
        DB::table('address')->insert(
            array(
                'address' => 'Administrador',
                'district' => 'Administrador',
                'city' => 'Administrador',
                'state' => 'Administrador',
                'complement' => 'Administrador',
                'CEP' => '11111111',
                'user_id' => 1
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->destroy(1);
        Schema::dropIfExists('address');
        Schema::dropIfExists('users');
    }
}
