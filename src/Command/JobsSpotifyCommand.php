<?php

namespace App\Command;

use App\Provider\CacheProviderInterface;
use App\Provider\JobsProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class JobsSpotifyCommand extends Command
{
    protected static $defaultName = 'jobs:spotify';

    private JobsProviderInterface $jobsProvider;

    public function __construct(JobsProviderInterface $jobsProvider)
    {
        parent::__construct();

        $this->jobsProvider = $jobsProvider;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jobs = $this->fetchJobs($input, $output);
        $this->renderData($jobs, $output);

        try {
            $this->showDescription($input, $output, $jobs);
        } catch (\OutOfRangeException $e) {
            return 0;
        }

        return 0;
    }

    private function fetchJobs(InputInterface $input, OutputInterface $output): array
    {
        $section = $output->section();
        $section->writeln('Fetching jobs...');
        if ($this->jobsProvider instanceof CacheProviderInterface && $input->getOption('disable-cache')) {
            $this->jobsProvider->useCache(false);
            $section->writeln('<info>Cache was ommited!</info>');
        }
        $jobs = $this->jobsProvider->getJobs();
        $section->writeln('Fetching jobs details...');

        $progressBar = new ProgressBar($output, count($jobs));
        $progressBar->start();
        foreach ($jobs as $key => $job) {
            $jobs[$key]['description'] = $this->jobsProvider->getJobsDescription($job['url']);
            $progressBar->advance();
        }
        $progressBar->finish();

        $output->write("\n");
        $output->writeln('<info>Fetching is done!</info>');

        return $jobs;
    }

    private function renderData(array $jobs, OutputInterface $output): void
    {
        $table = new Table($output);
        $table->setHeaders(['Id', 'Title', 'Url', 'Experience years', 'Experienced professionals']);

        foreach ($jobs as $key => $job) {
            $table->addRow([$key, $job['title'], $job['url'], $job['experience_years'], $job['experienced'] ? 'Yes' : 'No']);
        }
        $table->render();
    }

    private function showDescription(InputInterface $input, OutputInterface $output, array $jobs): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('Type job id to see it\'s description: ', 0);
        $jobId = $helper->ask($input, $output, $question);

        if (!array_key_exists($jobId, $jobs)) {
            $output->writeln('<error>Provided job id is out of range!</error>');
            throw new \OutOfRangeException();
        }

        $output->write("\n");
        $output->writeln('<info>Job description for: '.$jobs[$jobId]['title'].'</info>');
        $output->write("\n");
        $output->writeln($jobs[$jobId]['description']);
    }

    protected function configure(): void
    {
        $this->setDescription('List latest Spotify jobs')
            ->addOption(
                'disable-cache',
                null,
                InputOption::VALUE_NONE,
                'Don\'t use stored cache'
            );
    }
}
