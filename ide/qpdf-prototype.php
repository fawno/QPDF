<?php
	use FFI\CData;

	final class FFI {
    /**
     * void qpdf_silence_errors(qpdf_data qpdf);
     *
     * From qpdf 10.5: call this method to signal to the library that
     * you are explicitly handling errors from functions that don't
     * return error codes. Otherwise, the library will print these
     * error conditions to stderr and issue a warning. Prior to 10.5,
     * the program would have crashed from an unhandled exception.
     *
     * @param CData $qpdf qpdf_data
     * @return void
     */
    public function qpdf_silence_errors (CData $qpdf) : void {}

    /**
     * char const* qpdf_get_qpdf_version();
     *
     * Returns the version of the qpdf software. This is guaranteed to
     * be a static value.
     *
     * @return string
     */
    public function qpdf_get_qpdf_version () : string { return ''; }

    /**
     * qpdf_data qpdf_init();
     *
     * Returns dynamically allocated qpdf_data pointer; must be freed
     * by calling qpdf_cleanup. You must call qpdf_read, one of the
     * other qpdf_read_* functions, or qpdf_empty_pdf before calling
     * any function that would need to operate on the PDF file.
     *
     * @return CData qpdf_data
     */
    public function qpdf_init () : CData { return new CData;  }

    /**
     * void qpdf_cleanup(qpdf_data* qpdf);
     *
     * Pass a pointer to the qpdf_data pointer created by qpdf_init to
     * clean up resources. This does not include buffers initialized
     * by functions that return stream data but it otherwise includes
     * all data associated with the QPDF object or any object handles.
     *
     * @param CData $qpdf qpdf_data
     * @return void
     */
    public function qpdf_cleanup (CData $qpdf) : void {}

    /**
     * QPDF_BOOL qpdf_has_error(qpdf_data qpdf);
     *
     * Returns 1 if there is an error condition.  The error condition
     * can be retrieved by a single call to qpdf_get_error.
     *
     * @param CData $qpdf qpdf_data
     * @return bool QPDF_BOOL
     */
    public function qpdf_has_error (CData $qpdf) : bool { return 0; }

    /**
     * qpdf_error qpdf_get_error(qpdf_data qpdf);
     *
     * Returns the error condition, if any.  The return value is a
     * pointer to data that will become invalid after the next call to
     * this function, qpdf_next_warning, or qpdf_cleanup.  After this
     * function is called, qpdf_has_error will return QPDF_FALSE until
     * the next error condition occurs.  If there is no error
     * condition, this function returns a null pointer.
     *
     * @param CData $qpdf qpdf_data
     * @return CData qpdf_error
     */
    public function qpdf_get_error (CData $qpdf) : CData { return new CData; }

    /**
     * QPDF_BOOL qpdf_more_warnings(qpdf_data qpdf);
     *
     * Returns 1 if there are any unretrieved warnings, and zero
     * otherwise.
     *
     * @param CData $qpdf qpdf_data
     * @return bool QPDF_BOOL
     */
    public function qpdf_more_warnings (CData $qpdf) : bool { return 0; }

    /**
     * qpdf_error qpdf_next_warning(qpdf_data qpdf);
     *
     * If there are any warnings, returns a pointer to the next
     * warning.  Otherwise returns a null pointer.
     *
     * @param CData $qpdf qpdf_data
     * @return CData qpdf_error
     */
    public function qpdf_next_warning (CData $qpdf) : CData { return new CData; }

    /* Extract fields of the error. */

    /**
     * char const* qpdf_get_error_full_text(qpdf_data q, qpdf_error e);
     *
     * Use this function to get a full error message suitable for
     * showing to the user.
     *
     * @param CData $q qpdf_data
     * @param CData $e qpdf_error
     * @return string
     */
    public function qpdf_get_error_full_text (CData $q, CData $e) : string { return ''; }

    /*
     * Use these functions to extract individual fields from the
     * error; see QPDFExc.hh for details.
     */

    /**
     * enum qpdf_error_code_e qpdf_get_error_code(qpdf_data q, qpdf_error e);
     *
     * @param CData $q qpdf_data
     * @param CData $e qpdf_error
     * @return int qpdf_error_code_e
     */
    public function qpdf_get_error_code (CData $q, CData $e) : int { return 0; }


    /**
     * char const* qpdf_get_error_filename(qpdf_data q, qpdf_error e);
     *
     * @param CData $q qpdf_data
     * @param CData $e qpdf_error
     * @return string
     */
    public function qpdf_get_error_filename (CData $q, CData $e) : string { return ''; }


    /**
     * unsigned long long qpdf_get_error_file_position(qpdf_data q, qpdf_error e);
     *
     * @param CData $q qpdf_data
     * @param CData $e qpdf_error
     * @return int unsigned long long
     */
    public function qpdf_get_error_file_position (CData $q, CData $e) : int { return 0; }


    /**
     * char const* qpdf_get_error_message_detail(qpdf_data q, qpdf_error e);
     *
     * @param CData $q qpdf_data
     * @param CData $e qpdf_error
     * @return string
     */
    public function qpdf_get_error_message_detail(CData $q, CData $e) : string { return ''; }


    /**
     * void qpdf_set_suppress_warnings(qpdf_data qpdf, QPDF_BOOL value);
     *
     * By default, warnings are written to stderr.  Passing true to
     * this function will prevent warnings from being written to
     * stderr.  They will still be available by calls to
     * qpdf_next_warning.
     *
     * @param CData $qpdf qpdf_data
     * @param CData $value bool
     * @return void
     */
    public function qpdf_set_suppress_warnings (CData $qpdf, bool $value) : void {}

    /**
     * QPDF_ERROR_CODE qpdf_check_pdf(qpdf_data qpdf);
     *
     * Attempt to read the entire PDF file to see if there are any
     * errors qpdf can detect.
     *
     * @param CData $qpdf qpdf_data
     * @return int QPDF_ERROR_CODE
     */
    public function qpdf_check_pdf (CData $qpdf) : int { return 0; }

    /**
     * void qpdf_set_attempt_recovery(qpdf_data qpdf, QPDF_BOOL value);
     *
     * @param CData $qpdf
     * @param bool $value
     * @return void
     */
    public function qpdf_set_attempt_recovery (CData $qpdf, bool $value) : void {}


    /**
     * QPDF_ERROR_CODE qpdf_read(qpdf_data qpdf, char const* filename, char const* password);
     *
     * Calling qpdf_read causes processFile to be called in the C++
     * API.  Basic parsing is performed, but data from the file is
     * only read as needed.  For files without passwords, pass a null
     * pointer or an empty string as the password.
     *
     * @param CData $qpdf qpdf_data
     * @param string $filename
     * @param string $password
     * @return int QPDF_ERROR_CODE
     */
    public function qpdf_read (CData $qpdf, string $filename, string $password) : int { return 0; }

    /**
     * QPDF_ERROR_CODE qpdf_init_write(qpdf_data qpdf, char const* filename);
     *
     * Supply the name of the file to be written and initialize the
     * qpdf_data object to handle writing operations.  This function
     * also attempts to create the file.  The PDF data is not written
     * until the call to qpdf_write.  qpdf_init_write may be called
     * multiple times for the same qpdf_data object.  When
     * qpdf_init_write is called, all information from previous calls
     * to functions that set write parameters (qpdf_set_linearization,
     * etc.) is lost, so any write parameter functions must be called
     * again.
     *
     * @param CData $qpdf qpdf_data
     * @param string $filename
     * @return int QPDF_ERROR_CODE
     */
    public function qpdf_init_write (CData $qpdf, string $filename) : int { return 0; }

    /**
     * QPDF_ERROR_CODE qpdf_init_write_memory(qpdf_data qpdf);
     *
     * Initialize for writing but indicate that the PDF file should be
     * written to memory.  Call qpdf_get_buffer_length and
     * qpdf_get_buffer to retrieve the resulting buffer.  The memory
     * containing the PDF file will be destroyed when qpdf_cleanup is
     * called.
     *
     * @param CData $qpdf qpdf_data
     * @return int QPDF_ERROR_CODE
     */
    public function qpdf_init_write_memory (CData $qpdf) : int { return 0; }

    /**
     * size_t qpdf_get_buffer_length(qpdf_data qpdf);
     *
     * Retrieve the buffer used if the file was written to memory.
     * qpdf_get_buffer returns a null pointer if data was not written
     * to memory.  The memory is freed when qpdf_cleanup is called or
     * if a subsequent call to qpdf_init_write or
     * qpdf_init_write_memory is called.
     *
     * @param CData $qpdf qpdf_data
     * @return int size_t
     */
    public function qpdf_get_buffer_length (CData $qpdf) : int { return 0; }

    /**
     * unsigned char const* qpdf_get_buffer(qpdf_data qpdf);
     *
     * @param CData $qpdf
     * @return CData unsigned char const*
     */
    public function qpdf_get_buffer (CData $qpdf) : CData { return new CData; }

    /**
     * void qpdf_set_preserve_encryption(qpdf_data qpdf, QPDF_BOOL value);
     *
     * @param CData $qpdf qpdf_data
     * @param bool $value QPDF_BOOL
     * @return void
     */
    public function qpdf_set_preserve_encryption (CData $qpdf, bool $value) : void {}

    /**
     * QPDF_ERROR_CODE qpdf_write(qpdf_data qpdf);
     *
     * Do actual write operation.
     *
     * @param CData $qpdf qpdf_data
     * @return int QPDF_ERROR_CODE
     */
    public function qpdf_write(CData $qpdf) : int { return 0; }

	}
