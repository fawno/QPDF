<?php
  declare(strict_types=1);

	namespace Fawno\QPDF;

	use FFI;
	use FFI\CData;

	class QPDF {
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

		public function __construct (string $lib_path, string $header = null) {
			$header = $header ?? file_get_contents(__DIR__ . '/qpdf-c.h');

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
