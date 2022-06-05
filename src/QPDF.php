<?php

  declare(strict_types=1);

	namespace Fawno\QPDF;

	use FFI;
	use FFI\CData;
  use Fawno\QPDF\QPDFException;

	class QPDF {
    public const QPDF_SUCCESS = 0;
    public const QPDF_WARNINGS = 1 << 0;
    public const QPDF_ERRORS = 1 << 1;
    public const QPDF_TRUE = 1;
    public const QPDF_FALSE = 0;

		public const ERROR_CODES = [
			'success',
			'internal',    /* logic/programming error -- indicates bug */
			'system',		   /* I/O error, memory error, etc. */
			'unsupported', /* PDF feature not (yet) supported by qpdf */
			'password',		 /* incorrect password for encrypted file */
			'damaged_pdf', /* syntax errors or other damage in PDF */
			'pages',       /* erroneous or unsupported pages structure */
		];

		protected $qpdf = null;
		protected $data = null;

		protected const HEADER = <<<EOT
      typedef struct _qpdf_data* qpdf_data;
      typedef struct _qpdf_error* qpdf_error;
      typedef int QPDF_ERROR_CODE;
      typedef int QPDF_BOOL;
      void qpdf_silence_errors(qpdf_data qpdf);
      char const* qpdf_get_qpdf_version();
      qpdf_data qpdf_init();
      void qpdf_cleanup(qpdf_data* qpdf);
      QPDF_BOOL qpdf_has_error(qpdf_data qpdf);
      qpdf_error qpdf_get_error(qpdf_data qpdf);
      QPDF_BOOL qpdf_more_warnings(qpdf_data qpdf);
      qpdf_error qpdf_next_warning(qpdf_data qpdf);
      char const* qpdf_get_error_full_text(qpdf_data q, qpdf_error e);
      enum qpdf_error_code_e qpdf_get_error_code(qpdf_data q, qpdf_error e);
      char const* qpdf_get_error_filename(qpdf_data q, qpdf_error e);
      unsigned long long qpdf_get_error_file_position(qpdf_data q, qpdf_error e);
      char const* qpdf_get_error_message_detail(qpdf_data q, qpdf_error e);
      void qpdf_set_suppress_warnings(qpdf_data qpdf, QPDF_BOOL value);
      QPDF_ERROR_CODE qpdf_check_pdf(qpdf_data qpdf);
      void qpdf_set_attempt_recovery(qpdf_data qpdf, QPDF_BOOL value);
      QPDF_ERROR_CODE qpdf_read(qpdf_data qpdf, char const* filename, char const* password);
      QPDF_ERROR_CODE qpdf_init_write(qpdf_data qpdf, char const* filename);
      QPDF_ERROR_CODE qpdf_init_write_memory(qpdf_data qpdf);
      size_t qpdf_get_buffer_length(qpdf_data qpdf);
      unsigned char const* qpdf_get_buffer(qpdf_data qpdf);
      void qpdf_set_preserve_encryption(qpdf_data qpdf, QPDF_BOOL value);
      QPDF_ERROR_CODE qpdf_write(qpdf_data qpdf);
    EOT;

		public function __construct (string $lib_path, string $header = null) {
			if (!is_file($lib_path)) {
				throw new QPDFException(sprintf('% not found', $lib_path));
			}

      $header = $header ?? (is_file(__DIR__ . '/qpdf-c.h') ? file_get_contents(__DIR__ . '/qpdf-c.h') : self::HEADER);

			$this->qpdf = FFI::cdef($header, $lib_path);

			$this->init();
			$this->silenceErrors();
			$this->silenceWarnings(true);
		}

		public function __destruct () {
			$this->cleanup();
		}

    public function init () : void {
      $this->data = $this->qpdf->qpdf_init();
    }

    public function cleanup () : void {
			if (is_object($this->data)) {
				$this->qpdf->qpdf_cleanup(FFI::addr($this->data));
			}
    }

		public function attempRecovery (bool $recovery) : void {
			$this->qpdf->qpdf_set_attempt_recovery($this->data, $recovery);
		}

    public function getVersion () : string {
			return $this->qpdf->qpdf_get_qpdf_version();
		}

		public function silenceWarnings (bool $silence) : void {
			$this->qpdf->qpdf_set_suppress_warnings($this->data, $silence);
		}

		public function silenceErrors () : void {
			$this->qpdf->qpdf_silence_errors($this->data);
		}

		public function readFile (string $filename, string $password = null) : int {
			return $this->qpdf->qpdf_read($this->data, $filename, $password);
		}

		public function initWrite (?string $filename = null) : int {
			if ($filename) {
				return $this->qpdf->qpdf_init_write($this->data, $filename);
			} else {
				return $this->qpdf->qpdf_init_write_memory($this->data);
			}
		}

		public function preserveEncryption (bool $preserve) : void {
			$this->qpdf->qpdf_set_preserve_encryption($this->data, $preserve);
		}

		public function write () : int {
			return $this->qpdf->qpdf_write($this->data);
		}

		public function hasError () : bool {
			return (bool) $this->qpdf->qpdf_has_error($this->data);
		}

		public function getError () : array {
			$error = $this->qpdf->qpdf_get_error($this->data);

			return [
				'code' => $this->getErrorCode($error),
				'filename' => $this->getErrorFilename($error),
				'position' => $this->getErrorFilePosition($error),
				'message' => $this->getErrorMessage($error),
				'detail' => $this->getErrorMessageDetail($error),
			];
		}

		public function hasWarning () : bool {
			return (bool) $this->qpdf->qpdf_more_warnings($this->data);
		}

		public function getWarning () : array {
			$error = $this->qpdf->qpdf_next_warning($this->data);

			return [
				'code' => $this->getErrorCode($error),
				'filename' => $this->getErrorFilename($error),
				'position' => $this->getErrorFilePosition($error),
				'message' => $this->getErrorMessage($error),
				'detail' => $this->getErrorMessageDetail($error),
			];
		}

		protected function getErrorMessage (CData $error) : string {
			return $this->qpdf->qpdf_get_error_full_text($this->data, $error);
		}

		protected function getErrorCode (CData $error) : int {
			return $this->qpdf->qpdf_get_error_code($this->data, $error);
		}

		protected function getErrorFilename (CData $error) : string {
			return $this->qpdf->qpdf_get_error_filename($this->data, $error);
		}

		protected function getErrorFilePosition (CData $error) : int {
			return $this->qpdf->qpdf_get_error_file_position($this->data, $error);
		}

		protected function getErrorMessageDetail (CData $error) : string {
			return $this->qpdf->qpdf_get_error_message_detail($this->data, $error);
		}

		public function getBuffer () {
			$len = $this->qpdf->qpdf_get_buffer_length($this->data);
			$buffer = $this->qpdf->qpdf_get_buffer($this->data);

			return FFI::string($buffer, $len);
		}
	}
