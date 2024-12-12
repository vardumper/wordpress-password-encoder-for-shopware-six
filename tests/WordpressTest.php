<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vardumper\LegacyWordpressPasswordEncoder\LegacyEncoder\PasswordHash;
use Vardumper\LegacyWordpressPasswordEncoder\LegacyEncoder\Wordpress;

class WordpressTest extends TestCase
{
    private $wordpress;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../../../vendor/autoload.php';
        $this->wordpress = new Wordpress();
    }

    public function testIsPasswordValid(): void
    {
        $password = 'testpassword';
        $passwordHash = new PasswordHash(8, true);
        $hash = $passwordHash->hashPassword($password);

        static::assertTrue($this->wordpress->isPasswordValid($password, $hash));
        static::assertFalse($this->wordpress->isPasswordValid('wrongpassword', $hash));
    }

    public function testIsPasswordValidWithoutPortableHashes(): void
    {
        $password = 'testpassword';
        $passwordHash = new PasswordHash(8, false);
        $hash = $passwordHash->hashPassword($password);

        static::assertTrue($this->wordpress->isPasswordValid($password, $hash));
        static::assertFalse($this->wordpress->isPasswordValid('wrongpassword', $hash));
    }

    public function testGenerateInternal(): void
    {
        $password = 'testpassword';
        $salt = 'randomsalt';
        $iterations = 8;

        // Assuming generateInternal is a public method for testing purposes
        $reflection = new ReflectionClass($this->wordpress);
        $method = $reflection->getMethod('generateInternal');
        if ($method->isPublic() || $method->isProtected()) {
            $method->setAccessible(true);
        }

        $hash = $method->invokeArgs($this->wordpress, [$password, $salt, $iterations]);

        static::assertNotEquals('*', $hash);
        static::assertTrue($this->wordpress->isPasswordValid($password, $hash));
    }
}