<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("transactions", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            $table->foreignId("table_id")->constrained("meja");
            $table->dateTime("transaction_time");
            $table->string("customer");
            $table->integer("qty");
            $table->integer("total");
            $table->integer("pay");
            $table->integer("return_amount");
            $table->timestamps();
        });

        Schema::create("transaction_items", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("transaction_id")
                ->constrained("transactions")
                ->onDelete("cascade");
            // $table->foreignId('menu_id')->constrained('menu');
            $table
                ->foreignId("menu_id")
                ->constrained("menu")
                ->onDelete("cascade");
            $table->integer("quantity");
            $table->integer("price");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("transaction_items");
        Schema::dropIfExists("transactions");
    }
};

// use illuminate\database\migrations\migration;
// use illuminate\database\schema\blueprint;
// use illuminate\support\facades\schema;

// return new class extends migration {
//     /**
//      * run the migrations.
//      */

//     public function up(): void
//     {
//         schema::create('transactions', function (blueprint $table) {
//             $table->id();
//             $table->foreignid('user_id')->constrained('users');
//             $table->foreignid('table_id')->constrained('meja');
//             $table->datetime('transaction_time');
//             $table->string('customer');
//             $table->integer('qty');
//             $table->integer('pay');
//             $table->integer('return_amount');
//             $table->timestamps();
//         });
//     }

//     /**
//      * reverse the migrations.
//      */
//     public function down(): void
//     {
//         schema::dropifexists('transactions');
//     }
// };
