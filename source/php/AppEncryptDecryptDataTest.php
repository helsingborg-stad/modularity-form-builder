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

    /**
     * It should return array form data unchanged.
     *
     * @return void
     */
    public function testDecodeFormDataReturnsArrayUnchanged(): void
    {
        $payload = ['name' => 'Alice'];

        $this->assertSame($payload, App::decodeFormData($payload));
    }

    /**
     * It should decode encrypted form data to an array.
     *
     * @return void
     */
    public function testDecodeFormDataDecodesEncryptedArray(): void
    {
        $payload = ['name' => 'Alice', 'email' => 'alice@example.com'];

        $this->assertSame($payload, App::decodeFormData(App::encryptDecryptData('encrypt', $payload)));
    }

    /**
     * It should decode form data that was encrypted twice.
     *
     * @return void
     */
    public function testDecodeFormDataDecodesDoubleEncryptedArray(): void
    {
        $payload = ['name' => 'Alice'];
        $encryptedTwice = App::encryptDecryptData('encrypt', App::encryptDecryptData('encrypt', $payload));

        $this->assertSame($payload, App::decodeFormData($encryptedTwice));
    }

    /**
     * It should return an empty array, without warnings, for data that cannot be decoded.
     *
     * @return void
     */
    public function testDecodeFormDataReturnsEmptyArrayForUndecodableData(): void
    {
        $warnings = [];
        set_error_handler(static function (int $errno, string $errstr) use (&$warnings): bool {
            $warnings[] = $errstr;
            return true;
        });

        try {
            $results = [
                App::decodeFormData(base64_encode(random_bytes(64))),
                App::decodeFormData('plain text'),
                App::decodeFormData(''),
                App::decodeFormData(null),
            ];
        } finally {
            restore_error_handler();
        }

        $this->assertSame([[], [], [], []], $results);
        $this->assertSame([], $warnings);
    }
}
