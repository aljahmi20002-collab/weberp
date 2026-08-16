<?php

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('user_type')->default(UserType::USER)->comment(UserType::SUPERADMIN.'= Super admin ,'.UserType::ADMIN.' = admin , '.UserType::USER.' = User');
            $table->string('google_id')->unique()->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->longText('about')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->bigInteger('avatar')->unsigned()->nullable();
            $table->text('permissions')->default('');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade');
            $table->unsignedBigInteger('is_ban')->default(BanUser::UNBAN)->comment(BanUser::BAN.'= '.__('banuser.'.BanUser::BAN).','.BanUser::UNBAN.'= '.__('banuser.'.BanUser::UNBAN));
            $table->unsignedBigInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE.'= '.__('status.'.Status::ACTIVE).','.Status::INACTIVE.'= '.__('status.'.Status::INACTIVE));
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('email_verified')->default(0)->nullable();
            $table->unsignedTinyInteger('business_owner')->default(0);
            $table->string('verify_token')->nullable();
            $table->string('forgot_token')->nullable();
            $table->timestamps();

            $table->index('business_id');
            $table->index('branch_id');
            $table->index('user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
