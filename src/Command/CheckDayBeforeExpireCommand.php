<?php

namespace App\Command;

use App\Services\TodoListService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckDayBeforeExpireCommand extends Command
{
    protected static $defaultName = 'app:check-day-before-expire';

    private $todoListService;

    public function __construct(TodoListService $todoListService)
    {
        $this->todoListService = $todoListService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Checking all todo lists to one day before expiration date')
            ->setHelp('This command allows you to check expiration date of todo lists')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->todoListService->checkDayBeforeExpire();
    }
}
