use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

public function runCommand($command, array $arguments = [], OutputInterface $output = null)
{
    $arguments = array_merge(['command' => $command], $arguments);
    $input = new ArrayInput($arguments);
    $command = $this->getApplication()->find($command);
    return $command->run($input, $output ?? $this->output);
}
