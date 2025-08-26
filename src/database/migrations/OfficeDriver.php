<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $tables,$cols;
    public function up(): void
    {
        $this->setupPlain();
    }
    public static function setupPlain()
    {
        self::$tables=config('Amer.Drivers.tables');
        self::$cols=config('Amer.Drivers.columns');
        $tables=self::$tables;
        $cols=self::$cols;
        Schema::create(self::$tables["cars"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->jsonb($cols['info'])->nullable();
            $table->dates();
        });
        Schema::create(self::$tables["chairmen"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->string($cols['persons_name'])->nullable();
            $table->jsonb($cols['info'])->nullable();
            $table->dates();
        });
        Schema::create(self::$tables["drivers"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->string($cols['persons_name'])->nullable();
            $table->string($cols['userid'])->nullable();
            $table->jsonb($cols['info'])->nullable();
            $table->dates();
        });
        Schema::create(self::$tables["employers"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->string($cols['persons_name'])->nullable();
            $table->string($cols['userid'])->nullable();
            $table->jsonb($cols['info'])->nullable();
            $table->dates();
        });
        Schema::create(self::$tables["chairmenmamorias"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->forignUUid($cols['chairmanid'])->references('id')->on(self::$tables["chairmen"])->nullable(); //done
            $table->jsonb($cols['places'])->nullable();
            $table->jsonb($cols['startend'])->nullable();
            $table->float($cols['amount'])->nullable();
            $table->float($cols['eqameamount'])->nullable();
            $table->string($cols['driver'], 255)->nullable();///////////////
            $table->text($cols['report'])->nullable();////////////////
            $table->text($cols['reson'])->nullable();
            $table->enum($cols['print'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['exit'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['taswia'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['eqama'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->dates();
        });
        Schema::create(self::$tables["employersmamorias"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->foreignUuid('employer_id')->references('id')->on(self::$tables["employers"])->nullable();
            $table->jsonb($cols['places'])->nullable();
            $table->jsonb($cols['startend'])->nullable();
            $table->float($cols['eqameamount'])->nullable();
            $table->string($cols['driver'], 255)->nullable();
            $table->text($cols['report'])->nullable();
            $table->text($cols['reson'])->nullable();
            $table->enum($cols['print'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['exit'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['taswia'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['eqama'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->dates();
        });
        Schema::create(self::$tables["driversmamorias"], function (Blueprint $table) use($tables,$cols) {
            $table->uid();
            $table->foreignUuid($cols['driver'])->references('id')->on(self::$tables["drivers"])->nullable();
            $table->foreignUuid($cols['car'])->references('id')->on(self::$tables["cars"])->nullable();
            $table->jsonb($cols['places'])->nullable();
            $table->jsonb($cols['addad'])->nullable();
            $table->jsonb($cols['startend'])->nullable();
            $table->float($cols['amount'])->nullable();
            $table->jsonb($cols['eqameamount'])->nullable();
            $table->text($cols['reson'])->nullable();
            $table->text($cols['report'])->nullable();
            $table->enum($cols['print'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['exit'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['taswia'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->enum($cols['eqama'], $cols['enumTF'])->default($cols['enumTF'][1]);
            $table->dates();
        });
    }
    public function down(): void
    {
        foreach (self::$tables as $key => $value) {
            Schema::dropIfExists($value);
        }
    }
};
