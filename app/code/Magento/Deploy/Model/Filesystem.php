<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Deploy\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\Locale;
use Magento\User\Model\ResourceModel\User\Collection as UserCollection;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate static files, compile
 *
 * Clear generated/code, generated/metadata/, var/view_preprocessed and pub/static directories
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Filesystem
{
    /**
     * File access permissions
     *
     * @deprecated As magento2 doesn't control indirectly the access permissions to the files anymore.
     * Access permissions to the files are set during deploy Magento 2, directly after
     * uploading code of Magento. Also it is possible to specify the value
     * of inverse mask for setting access permissions to files generated by Magento.
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/install/post-install-umask.html
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/prereq/file-system-perms.html
     */
    const PERMISSIONS_FILE = 0640;

    /**
     * Directory access permissions
     *
     * @deprecated As magento2 doesn't control indirectly the access permissions to the directories anymore.
     * Access permissions to the directories are set during deploy Magento 2, directly after
     * uploading code of Magento. Also it is possible to specify the value
     * of inverse mask for setting access permissions to directories generated by Magento.
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/install/post-install-umask.html
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/prereq/file-system-perms.html
     */
    const PERMISSIONS_DIR = 0750;

    /**
     * Default theme when no theme is stored in configuration
     */
    const DEFAULT_THEME = 'Magento/blank';

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $driverFile;

    /**
     * @var \Magento\Store\Model\Config\StoreView
     */
    private $storeView;

    /**
     * @var \Magento\Framework\ShellInterface
     */
    private $shell;

    /**
     * @var string
     */
    private $functionCallPath;

    /**
     * @var UserCollection
     */
    private $userCollection;

    /**
     * @var Locale
     */
    private $locale;

    /**
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File $driverFile
     * @param \Magento\Store\Model\Config\StoreView $storeView
     * @param \Magento\Framework\ShellInterface $shell
     * @param UserCollection $userCollection
     * @param Locale $locale
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Store\Model\Config\StoreView $storeView,
        \Magento\Framework\ShellInterface $shell,
        UserCollection $userCollection,
        Locale $locale
    ) {
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        $this->storeView = $storeView;
        $this->shell = $shell;
        $this->userCollection = $userCollection;
        $this->locale = $locale;
        $this->functionCallPath =
            PHP_BINARY . ' -f ' . BP . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'magento ';
    }

    /**
     * Regenerate static
     *
     * @param OutputInterface $output
     * @return void
     */
    public function regenerateStatic(
        OutputInterface $output
    ) {
        // Clear generated/code, generated/metadata/, var/view_preprocessed and pub/static directories
        $this->cleanupFilesystem(
            [
                DirectoryList::CACHE,
                DirectoryList::GENERATED_CODE,
                DirectoryList::GENERATED_METADATA,
                DirectoryList::TMP_MATERIALIZATION_DIR,
                DirectoryList::STATIC_VIEW
            ]
        );
        
        // Trigger code generation
        $this->compile($output);
        // Trigger static assets compilation and deployment
        $this->deployStaticContent($output);
    }

    /**
     * Deploy static content
     *
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function deployStaticContent(
        OutputInterface $output
    ) {
        $output->writeln('Starting deployment of static content');
        $cmd = $this->functionCallPath . 'setup:static-content:deploy -f '
            . implode(' ', $this->getUsedLocales());

        /**
         * @todo eliminate exec
         */
        try {
            $execOutput = $this->shell->execute($cmd);
        } catch (LocalizedException $e) {
            $output->writeln('Something went wrong while deploying static content. See the error log for details.');
            throw $e;
        }
        $output->writeln($execOutput);
        $output->writeln('Deployment of static content complete');
    }

    /**
     * Get admin user locales
     *
     * @return array
     */
    private function getAdminUserInterfaceLocales()
    {
        $locales = [];
        foreach ($this->userCollection as $user) {
            $locales[] = $user->getInterfaceLocale();
        }
        return $locales;
    }

    /**
     * Get used store and admin user locales
     *
     * @return array
     * @throws \InvalidArgumentException if unknown locale is provided by the store configuration
     */
    private function getUsedLocales()
    {
        $usedLocales = array_merge(
            $this->storeView->retrieveLocales(),
            $this->getAdminUserInterfaceLocales()
        );
        return array_map(
            function ($locale) {
                if (!$this->locale->isValid($locale)) {
                    throw new \InvalidArgumentException(
                        $locale .
                        ' argument has invalid value, run info:language:list for list of available locales'
                    );
                }
                return $locale;
            },
            array_unique($usedLocales)
        );
    }

    /**
     * Runs compiler
     *
     * @param OutputInterface $output
     * @return void
     * @throws LocalizedException
     */
    protected function compile(OutputInterface $output)
    {
        $output->writeln('Starting compilation');
        $this->cleanupFilesystem(
            [
                DirectoryList::CACHE,
                DirectoryList::GENERATED_CODE,
                DirectoryList::GENERATED_METADATA,
            ]
        );
        $cmd = $this->functionCallPath . 'setup:di:compile';

        /**
         * exec command is necessary for now to isolate the autoloaders in the compiler from the memory state
         * of this process, which would prevent some classes from being generated
         *
         * @todo eliminate exec
         */
        try {
            $execOutput = $this->shell->execute($cmd);
        } catch (LocalizedException $e) {
            $output->writeln('Something went wrong while compiling generated code. See the error log for details.');
            throw $e;
        }
        $output->writeln($execOutput);
        $output->writeln('Compilation complete');
    }

    /**
     * Deletes specified directories by code
     *
     * @param array $directoryCodeList
     * @return void
     */
    public function cleanupFilesystem($directoryCodeList)
    {
        $excludePatterns = ['#.htaccess#', '#deployed_version.txt#'];
        foreach ($directoryCodeList as $code) {
            if ($code == DirectoryList::STATIC_VIEW) {
                $directoryPath = $this->directoryList->getPath(DirectoryList::STATIC_VIEW);
                if ($this->driverFile->isExists($directoryPath)) {
                    $files = $this->driverFile->readDirectory($directoryPath);
                    foreach ($files as $file) {
                        foreach ($excludePatterns as $pattern) {
                            if (preg_match($pattern, $file)) {
                                continue 2;
                            }
                        }
                        if ($this->driverFile->isFile($file)) {
                            $this->driverFile->deleteFile($file);
                        } else {
                            $this->driverFile->deleteDirectory($file);
                        }
                    }
                }
            } else {
                $this->filesystem->getDirectoryWrite($code)
                    ->delete();
            }
        }
    }

    /**
     * Changes permissions for directories by their code.
     *
     * @param array $directoryCodeList
     * @param int $dirPermissions
     * @param int $filePermissions
     * @return void
     * @deprecated 100.0.6 As magento2 doesn't control indirectly
     * the access permissions to the files and directories anymore.
     * Access permissions to the files and directories are set during deploy Magento 2, directly after
     * uploading code of Magento. Also it is possible to specify the value
     * of inverse mask for setting access permissions to files and directories generated by Magento.
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/install/post-install-umask.html
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/prereq/file-system-perms.html
     */
    protected function changePermissions($directoryCodeList, $dirPermissions, $filePermissions)
    {
        foreach ($directoryCodeList as $code) {
            $directoryPath = $this->directoryList->getPath($code);
            if ($this->driverFile->isExists($directoryPath)) {
                $this->filesystem->getDirectoryWrite($code)
                    ->changePermissionsRecursively('', $dirPermissions, $filePermissions);
            } else {
                $this->driverFile->createDirectory($directoryPath, $dirPermissions);
            }
        }
    }

    /**
     * Change permissions on static resources
     *
     * @return void
     * @deprecated 100.0.6 As magento2 doesn't control indirectly the access permissions to the files
     * and directories anymore.
     * Access permissions to the files and directories are set during deploy Magento 2, directly after
     * uploading code of Magento. Also it is possible to specify the value
     * of inverse mask for setting access permissions to files and directories generated by Magento.
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/install/post-install-umask.html
     * @link http://devdocs.magento.com/guides/v2.0/install-gde/prereq/file-system-perms.html
     */
    public function lockStaticResources()
    {
        // Lock /generated/code, /generated/metadata/ and /var/view_preprocessed directories
        $this->changePermissions(
            [
                DirectoryList::GENERATED_CODE,
                DirectoryList::GENERATED_METADATA,
                DirectoryList::TMP_MATERIALIZATION_DIR,
            ],
            self::PERMISSIONS_DIR,
            self::PERMISSIONS_FILE
        );
    }
}
