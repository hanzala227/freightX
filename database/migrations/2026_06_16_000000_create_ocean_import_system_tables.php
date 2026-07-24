<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create charge_codes table
        if (!Schema::hasTable('charge_codes')) {
            Schema::create('charge_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->timestamps();
            });

            // Insert standard charge codes
            DB::table('charge_codes')->insert([
                ['code' => 'OFT', 'name' => 'Ocean Freight', 'created_at' => now(), 'updated_at' => now()],
                ['code' => 'THC', 'name' => 'Terminal Handling Charge', 'created_at' => now(), 'updated_at' => now()],
                ['code' => 'DOC', 'name' => 'Documentation Fee', 'created_at' => now(), 'updated_at' => now()],
                ['code' => 'VAT', 'name' => 'Value Added Tax', 'created_at' => now(), 'updated_at' => now()],
                ['code' => 'DIS', 'name' => 'Distribution Fee', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. Alter ocean_imports table to add filing and supporting fields
        Schema::table('ocean_imports', function (Blueprint $table) {
            if (!Schema::hasColumn('ocean_imports', 'ams_no')) {
                $table->string('ams_no')->nullable()->after('is_ecommerce');
            }
            if (!Schema::hasColumn('ocean_imports', 'isf_no')) {
                $table->string('isf_no')->nullable()->after('ams_no');
            }
            if (!Schema::hasColumn('ocean_imports', 'isf_matched_date')) {
                $table->date('isf_matched_date')->nullable()->after('isf_no');
            }
            if (!Schema::hasColumn('ocean_imports', 'is_isf_3rd_party')) {
                $table->boolean('is_isf_3rd_party')->default(false)->after('isf_matched_date');
            }
            if (!Schema::hasColumn('ocean_imports', 'entry_no')) {
                $table->string('entry_no')->nullable()->after('is_isf_3rd_party');
            }
            if (!Schema::hasColumn('ocean_imports', 'entry_doc_sent_date')) {
                $table->date('entry_doc_sent_date')->nullable()->after('entry_no');
            }
            if (!Schema::hasColumn('ocean_imports', 'go_date')) {
                $table->date('go_date')->nullable()->after('entry_doc_sent_date');
            }
            if (!Schema::hasColumn('ocean_imports', 'available_date')) {
                $table->date('available_date')->nullable()->after('go_date');
            }
            if (!Schema::hasColumn('ocean_imports', 'c_released_date')) {
                $table->date('c_released_date')->nullable()->after('available_date');
            }
            if (!Schema::hasColumn('ocean_imports', 'released_by_id')) {
                $table->foreignId('released_by_id')->nullable()->after('c_released_date')->constrained('users');
            }
            if (!Schema::hasColumn('ocean_imports', 'is_ror')) {
                $table->boolean('is_ror')->default(false)->after('released_by_id');
            }
            if (!Schema::hasColumn('ocean_imports', 'is_hold')) {
                $table->boolean('is_hold')->default(false)->after('is_ror');
            }
            if (!Schema::hasColumn('ocean_imports', 'door_delivery_date')) {
                $table->date('door_delivery_date')->nullable()->after('is_hold');
            }
            if (!Schema::hasColumn('ocean_imports', 'trucker_id')) {
                $table->foreignId('trucker_id')->nullable()->after('door_delivery_date')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('ocean_imports', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('trucker_id');
            }
            if (!Schema::hasColumn('ocean_imports', 'sales_type')) {
                $table->string('sales_type')->nullable()->after('expiry_date');
            }
            if (!Schema::hasColumn('ocean_imports', 'incoterm_id')) {
                $table->string('incoterm_id')->nullable()->after('sales_type'); // matches the string format or ID
            }
        });

        // 3. Create ocean_import_charges table
        if (!Schema::hasTable('ocean_import_charges')) {
            Schema::create('ocean_import_charges', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ocean_import_id')->constrained('ocean_imports')->onDelete('cascade');
                $table->foreignId('ocean_import_hbl_id')->nullable()->constrained('ocean_import_hbls')->onDelete('cascade');
                $table->enum('type', ['AR', 'AP', 'DC_NOTE'])->default('AR');
                $table->string('charge_code')->nullable();
                $table->string('charge_name');
                $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners');
                $table->foreignId('vendor_id')->nullable()->constrained('trade_partners');
                $table->enum('pc', ['PREPAID', 'COLLECT'])->default('COLLECT');
                $table->decimal('qty', 15, 3)->default(1);
                $table->string('unit')->default('UNIT');
                $table->foreignId('currency_id')->nullable()->constrained('currencies');
                $table->decimal('rate', 15, 2)->default(0.00);
                $table->decimal('amount', 15, 2)->default(0.00);
                $table->decimal('tax_percent', 5, 2)->default(0.00);
                $table->decimal('tax_amount', 15, 2)->default(0.00);
                $table->decimal('total_amount', 15, 2)->default(0.00);
                $table->decimal('roe', 15, 4)->default(1.0000);
                $table->decimal('vat', 5, 2)->default(0.00);
                $table->boolean('is_invoiced')->default(false);
                $table->string('invoice_no')->nullable();
                $table->date('invoice_date')->nullable();
                $table->text('remark')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 4. Create ocean_import_documents table
        if (!Schema::hasTable('ocean_import_documents')) {
            Schema::create('ocean_import_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ocean_import_id')->constrained('ocean_imports')->onDelete('cascade');
                $table->foreignId('ocean_import_hbl_id')->nullable()->constrained('ocean_import_hbls')->onDelete('cascade');
                $table->string('file_name');
                $table->string('file_path');
                $table->string('file_extension')->nullable();
                $table->bigInteger('file_size')->nullable();
                $table->text('description')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 5. Create ocean_import_memos table
        if (!Schema::hasTable('ocean_import_memos')) {
            Schema::create('ocean_import_memos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ocean_import_id')->constrained('ocean_imports')->onDelete('cascade');
                $table->foreignId('ocean_import_hbl_id')->nullable()->constrained('ocean_import_hbls')->onDelete('cascade');
                $table->string('subject');
                $table->text('content')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 6. Create ocean_import_history table
        if (!Schema::hasTable('ocean_import_history')) {
            Schema::create('ocean_import_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ocean_import_id')->constrained('ocean_imports')->onDelete('cascade');
                $table->string('action');
                $table->text('details')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ocean_import_history');
        Schema::dropIfExists('ocean_import_memos');
        Schema::dropIfExists('ocean_import_documents');
        Schema::dropIfExists('ocean_import_charges');
        
        Schema::table('ocean_imports', function (Blueprint $table) {
            $table->dropForeign(['released_by_id']);
            $table->dropForeign(['trucker_id']);
            $table->dropColumn([
                'ams_no', 'isf_no', 'isf_matched_date', 'is_isf_3rd_party',
                'entry_no', 'entry_doc_sent_date', 'go_date', 'available_date',
                'c_released_date', 'released_by_id', 'is_ror', 'is_hold',
                'door_delivery_date', 'trucker_id', 'expiry_date', 'sales_type',
                'incoterm_id'
            ]);
        });

        Schema::dropIfExists('charge_codes');
    }
};
