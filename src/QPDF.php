<?php
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
			$this->data = $this->qpdf->qpdf_init();
			$this->supressWarnings(true);
		}

		public function __destruct () {
			FFI::free($this->data);
		}

		public function getVersion () : string {
			return $this->qpdf->qpdf_get_qpdf_version();
		}

		public function supressWarnings (bool $suppress) : void {
			$this->qpdf->qpdf_set_suppress_warnings($this->data, $suppress);
		}

		public function readFile (string $filename, string $password = null) : int {
			return $this->qpdf->qpdf_read($this->data, $filename, $password);
		}

		public function initWrite (string $filename) : int {
			return $this->qpdf->qpdf_init_write($this->data, $filename);
		}

		public function preserveEncryption (bool $preserve) : void {
			$this->qpdf->qpdf_set_preserve_encryption($this->data, $preserve);
		}

		public function write () : int {
			return $this->qpdf->qpdf_write($this->data);
		}

		public function hasError () : bool {
			return $this->qpdf->qpdf_has_error($this->data);
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
			return $this->qpdf->qpdf_more_warnings($this->data);
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
	}
