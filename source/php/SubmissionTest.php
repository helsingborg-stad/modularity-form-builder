<?php

declare(strict_types=1);

namespace {
    /**
     * Return metadata from the submission test fixture.
     *
     * @param int $postId Post ID.
     * @param string $key Metadata key.
     * @param bool $single Whether to return a single value.
     * @return mixed
     */
    function get_post_meta($postId, $key, $single)
    {
        return \ModularityFormBuilder\SubmissionTest::$metadata[$key] ?? null;
    }

    /**
     * Return form fields from the submission test fixture.
     *
     * @param int $formId Form ID.
     * @return array
     */
    function get_fields($formId)
    {
        return ['form_fields' => [['acf_fc_layout' => 'text', 'label' => 'Name']]];
    }

    /**
     * Return the encryption setting from the submission test fixture.
     *
     * @param string $name Option name.
     * @return bool
     */
    function get_option($name)
    {
        return \ModularityFormBuilder\SubmissionTest::$encryptionEnabled;
    }

    /**
     * Return an untranslated label.
     *
     * @param string $text Label.
     * @param string $domain Translation domain.
     * @return string
     */
    function __($text, $domain)
    {
        return $text;
    }

    /**
     * Sanitize a test field label.
     *
     * @param string $title Field label.
     * @return string
     */
    function sanitize_title($title)
    {
        return strtolower($title);
    }

    /**
     * Identify serialized arrays in the test fixture.
     *
     * @param mixed $data Stored value.
     * @return bool
     */
    function is_serialized($data)
    {
        return is_string($data) && preg_match('/^a:\d+:\{/', $data) === 1;
    }
}

namespace ModularityFormBuilder {
    use PHPUnit\Framework\TestCase;

    /**
     * Tests for reading stored form submissions.
     */
    class SubmissionTest extends TestCase
    {
        /**
         * @var array<string, mixed>
         */
        public static array $metadata = [];

        /**
         * @var bool
         */
        public static bool $encryptionEnabled = false;

        /**
         * Define encryption settings for an independently runnable test class.
         *
         * @return void
         */
        public static function setUpBeforeClass(): void
        {
            if (!defined('ENCRYPT_SECRET_VI')) {
                define('ENCRYPT_SECRET_VI', 'unit-test-vi');
            }
            if (!defined('ENCRYPT_SECRET_KEY')) {
                define('ENCRYPT_SECRET_KEY', 'unit-test-key');
            }
            if (!defined('ENCRYPT_METHOD')) {
                define('ENCRYPT_METHOD', 'AES-256-CBC');
            }
        }

        /**
         * Reset the fake metadata and option for each test.
         *
         * @return void
         */
        protected function setUp(): void
        {
            self::$metadata = ['modularity-form-id' => 10];
            self::$encryptionEnabled = false;
        }

        /**
         * Read array metadata when encryption is disabled.
         *
         * @return void
         */
        public function testReadsPlainArraySubmission(): void
        {
            // Arrange
            self::$metadata['form-data'] = ['name' => 'Alice'];

            // Act
            $result = Submission::getSubmissionData(42);

            // Assert
            $this->assertSame(['Name' => 'Alice'], $result);
        }

        /**
         * Read existing array metadata even when encryption is enabled.
         *
         * @return void
         */
        public function testReadsPlainArraySubmissionWithEncryptionEnabled(): void
        {
            // Arrange
            self::$encryptionEnabled = true;
            self::$metadata['form-data'] = ['name' => 'Alice'];

            // Act
            $result = Submission::getSubmissionData(42);

            // Assert
            $this->assertSame(['Name' => 'Alice'], $result);
        }

        /**
         * Read an encrypted and serialized submission.
         *
         * @return void
         */
        public function testReadsEncryptedSubmission(): void
        {
            // Arrange
            self::$encryptionEnabled = true;
            self::$metadata['form-data'] = App::encryptDecryptData('encrypt', ['name' => 'Alice']);

            // Act
            $result = Submission::getSubmissionData(42);

            // Assert
            $this->assertSame(['Name' => 'Alice'], $result);
        }

        /**
         * Treat undecodable metadata as empty submission data.
         *
         * @return void
         */
        public function testInvalidEncryptedSubmissionDoesNotThrow(): void
        {
            // Arrange
            self::$encryptionEnabled = true;
            self::$metadata['form-data'] = 'not-base64-@@@';

            // Act
            $result = Submission::getSubmissionData(42);

            // Assert
            $this->assertSame(['Name' => ''], $result);
        }
    }
}
