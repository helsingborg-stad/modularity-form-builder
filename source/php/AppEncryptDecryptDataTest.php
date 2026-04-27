<?php

declare(strict_types=1);

namespace ModularityFormBuilder;

use PHPUnit\Framework\TestCase;

/**
 * Class AppEncryptDecryptDataTest
 *
 * Unit tests for App::encryptDecryptData.
 */
class AppEncryptDecryptDataTest extends TestCase
{
    /**
     * @var string
     */
    private const EncryptionSecretVi = 'unit-test-vi';

    /**
     * @var string
     */
    private const EncryptionSecretKey = 'unit-test-key';

    /**
     * @var string
     */
    private const EncryptionMethod = 'AES-256-CBC';

    /**
     * Prepare encryption constants required by the subject under test.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        if (!defined('ENCRYPT_SECRET_VI')) {
            define('ENCRYPT_SECRET_VI', self::EncryptionSecretVi);
        }

        if (!defined('ENCRYPT_SECRET_KEY')) {
            define('ENCRYPT_SECRET_KEY', self::EncryptionSecretKey);
        }

        if (!defined('ENCRYPT_METHOD')) {
            define('ENCRYPT_METHOD', self::EncryptionMethod);
        }
    }

    /**
     * It should return original payload when decrypt input is invalid base64.
     *
     * @return void
     */
    public function testDecryptReturnsOriginalPayloadWhenBase64IsInvalid(): void
    {
        // Arrange
        $invalidPayload = 'not-base64-@@@';

        // Act
        $actualResult = App::encryptDecryptData('decrypt', $invalidPayload);

        // Assert
        $this->assertSame($invalidPayload, $actualResult);
    }

    /**
     * It should return original payload when decrypt input is not string.
     *
     * @return void
     */
    public function testDecryptReturnsOriginalPayloadWhenInputIsNotString(): void
    {
        // Arrange
        $invalidPayload = ['invalid' => 'type'];

        // Act
        $actualResult = App::encryptDecryptData('decrypt', $invalidPayload);

        // Assert
        $this->assertSame($invalidPayload, $actualResult);
    }

    /**
     * It should decrypt encrypted serialized data for array payload.
     *
     * @return void
     */
    public function testEncryptDecryptRoundtripForArrayPayload(): void
    {
        // Arrange
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ];

        // Act
        $encryptedPayload = App::encryptDecryptData('encrypt', $payload);
        $decryptedPayload = App::encryptDecryptData('decrypt', $encryptedPayload);

        // Assert
        $this->assertIsString($encryptedPayload);
        $this->assertSame(serialize($payload), $decryptedPayload);
    }
}
