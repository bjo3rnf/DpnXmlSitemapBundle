<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Command;

use Dpn\XmlSitemapBundle\Sitemap\SitemapManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DumpCommand extends Command
{
    private $manager;
    private $router;
    private $kernelRootDir;

    public function __construct(SitemapManager $manager, RouterInterface $router, $kernelRootDir)
    {
        $this->manager = $manager;
        $this->router = $router;
        $this->kernelRootDir = $kernelRootDir;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dpn:xml-sitemap:dump')
            ->setDescription('Dumps your sitemap(s) to the filesystem (defaults to web/)')
            ->addArgument('host', InputArgument::REQUIRED, 'The full hostname for your website (ie http://www.google.com).')
            ->addOption('target', null, InputOption::VALUE_REQUIRED, 'Override the target directory to dump sitemap(s) in.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getArgument('host');
        $target = rtrim($input->getOption('target') ?: sprintf('%s/../web', $this->kernelRootDir), '/');

        if (!is_dir($target)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $target));
        }

        if (1 !== preg_match('#^(https?)://([\w\.-]+)#', $host, $matches)) {
            throw new \InvalidArgumentException(sprintf('The host "%s" is invalid.', $host));
        }

        $scheme = $matches[1];
        $host = $matches[2];
        $context = $this->router->getContext();
        $context->setScheme($scheme);
        $context->setHost($matches[2]);
        $totalSitemaps = $this->manager->getNumberOfSitemaps();

        $this->outputFile(
            $output,
            sprintf('%s/sitemap_index.xml', $target),
            $this->manager->renderSitemapIndex(sprintf('%s://%s', $scheme, $host))
        );

        if (1 === $totalSitemaps) {
            $this->outputFile(
                $output,
                sprintf('%s/sitemap.xml', $target),
                $this->manager->renderSitemap()
            );

            return;
        }

        for ($i = 1; $i <= $totalSitemaps; $i++) {
            $this->outputFile(
                $output,
                sprintf('%s/sitemap%d.xml', $target, $i),
                $this->manager->renderSitemap($i)
            );
        }
    }

    /**
     * @param OutputInterface $output
     * @param string          $filename
     * @param string          $content
     */
    private function outputFile(OutputInterface $output, $filename, $content)
    {
        if (false === @file_put_contents($filename, $content)) {
            throw new \RuntimeException(sprintf('Unable to write file "%s"', $filename));
        }

        $output->writeln('<info>[file+]</info> '.$filename);
    }
}
