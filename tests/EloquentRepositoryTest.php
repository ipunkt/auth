<?php
use Ipunkt\Simpleauth\Repositories\EloquentRepository;

/**
 * Created by PhpStorm.
 * User: sven
 * Date: 23.05.14
 * Time: 16:24
 */

class EloquentRepositoryTest extends \Orchestra\Testbench\TestCase {
    /**
     * @var EloquentRepository
     */
    private $repository;

    /**
     * @param \Ipunkt\Simpleauth\Repositories\EloquentRepository $repository
     */
    public function setUp() {
        parent::setUp();
        $this->repository = new EloquentRepository();
        $artisan = $this->app->make('artisan');
        $artisan->call('migrate', array(
            '--database' => 'testbench',
            '--path' => '../src/migrations',
        ));
    }

    /** @test */
    public function can_create_user() {
        $user = $this->repository->create();
        $this->assertInstanceOf( 'Ipunkt\Simpleauth\models\UserInterface' , $user);
    }

    /** @test */
    public function can_save_user_to_db() {
        $user = $this->repository->create();
        $email = 'test@keks.de';
        $user->setEmail($email);
        $this->repository->save($user);

        $user_from_db = $this->repository->findOrFail(1);
        $this->assertEquals($email, $user_from_db->getEmail());
    }

    protected function getEnvironmentSetUp($app)
    {
        // reset base path to point to our package's src directory
        $app['path.base'] = __DIR__ . '/../src';

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
    }
    protected function getPackageProviders()
    {
        return array(
            'Ipunkt\Simpleauth\SimpleauthServiceProvider'
        );
    }
}
 