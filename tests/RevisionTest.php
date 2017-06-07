<?php

namespace Ruth\Revisions\Tests;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Ruth\Revisions\Revisionable;
use PHPUnit\Framework\TestCase;

class RevisionTest extends TestCase
{
	public function setUp()
	{
		$capsule = new Manager;
        $capsule->addConnection(array(
            'driver'  => 'sqlite',
            'database'  => ':memory:',
        ));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Manager::schema()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Manager::schema()->create(config('revision.table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->nullableMorphs('revisionable');
            $table->string('before', 5000)->nullable();
            $table->string('after', 5000)->nullable();
            $table->timestamps();
        });

    	Model::setEventDispatcher(new \Illuminate\Events\Dispatcher);
	}

	public function product()
	{
		return Product::create([
			'title' => 'Bag',
			'description' => 'This bag is blue',
    	]);
	}

    public function test_there_is_no_revisions_after_creation()
    {
    	$product = $this->product();

    	// No data has been created.
    	$this->assertEquals(0, $product->revisions()->count());

    	$product->update(['title' => 'Blue Bag']);

    	// A new update has been created.
		$this->assertEquals(1, $product->revisions()->count());

		$product->update(['title' => 'Blue Bag']);

		// There was a new updated but with no change.
		$this->assertEquals(1, $product->revisions()->count());
    }
}

class Product extends Model
{
	use Revisionable;

	protected $guarded = [];
}
