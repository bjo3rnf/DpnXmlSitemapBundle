<?php

namespace Dpn\XmlSitemapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DumpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dpn:xml-sitemap:dump')
            ->setDescription('Dumps your sitemap(s) and sitemap.xml to the filesystem (defaults to web/)')
            ->addArgument('host', InputArgument::REQUIRED, 'The full hostname for your website (ie http://www.google.com).')
            ->addOption('target', null, InputOption::VALUE_OPTIONAL, 'Override the target directory to dump sitemap(s) in.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getArgument('host');
        $target = rtrim($input->getOption('target') ?: sprintf('%s/../web', $this->getContainer()->getParameter('kernel.root_dir')), '/');

        if (!is_dir($target)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $target));
        }

        if (1 !== preg_match('#^(https?)://([\w\.-]+)#', $host, $matches)) {
            throw new \InvalidArgumentException(sprintf('The host "%s" is invalid.', $host));
        }

        $scheme = $matches[1];
        $host = $matches[2];
        $context = $this->getContainer()->get('router')->getContext();
        $context->setScheme($scheme);
        $context->setHost($matches[2]);
        $context->setBaseUrl('/');
        $manager = $this->getContainer()->get('dpn_xml_sitemap.manager');
        $totalSitemaps = $manager->getNumberOfSitemaps();

        $this->outputFile(
            $output,
            sprintf('%s/sitemap_index.xml', $target),
            $manager->renderSitemapIndex(sprintf('%s://%s', $scheme, $host))
        );

        if (1 === $totalSitemaps) {
            $this->outputFile(
                $output,
                sprintf('%s/sitemap.xml', $target),
                $manager->renderSitemap()
            );

            return;
        }

        for ($i = 1; $i <= $totalSitemaps; $i++) {
            $this->outputFile(
                $output,
                sprintf('%s/sitemap%d.xml', $target, $i),
                $manager->renderSitemap($i)
            );
        }
    }

    private function outputFile(OutputInterface $output, $filename, $content)
    {
        if (false === @file_put_contents($filename, $content)) {
            throw new \RuntimeException(sprintf('Unable to write file "%s"', $filename));
        }

        $output->writeln('<info>[file+]</info> '.$filename);
    }
}
