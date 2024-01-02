<?php
if ( ! function_exists( 'initialize_autoloader' ) ) {
	/**
	 * This is to include all classes in a directory.
	 *
	 * @param mixed $directory
	 * @param mixed $namespace
	 *
	 * @return void
	 */
	function initialize_autoloader( $directory, $namespace ) {
		$excludedFiles = array(
			'general.php',
			'spl-autoload.php',
			'class-data-list-table.php',
		);

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $directory, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ( $iterator as $file ) {
			if (
				$file->isFile()
				&& $file->getExtension() === 'php'
				&& ! in_array( basename( $file->getFilename() ), $excludedFiles )
			) {
				require_once $file->getPathname();
			}
		}

		$all_classes = get_declared_classes();
		foreach ( $all_classes as $class ) {
			if ( strpos( $class, $namespace . '\\' ) === 0 ) {
				$class_names[] = $class;
			}
		}

		foreach ( $class_names as $class_name ) {
			new $class_name();
		}
	}

	initialize_autoloader( plugin_dir_path( __FILE__ ) , 'Miusase' );
}
