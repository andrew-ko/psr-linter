<?php

namespace Psrlint;

use function Psrlint\Linter\inspect;

class Engine
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function executeOnText($text)
    {
        $results[] = $this->processText($text);

        $stats = $this->calculateAllStats($results);

        return [
            'results' => $results,
            'errorCount' => $stats['errorCount'],
            'warningCount' => $stats['warningCount']
        ];
    }

    public function executeOnFiles($files)
    {
        $results = [];

        foreach ($files as $file) {
            $results[] = $this->processFile($file);
        }

        $stats = $this->calculateAllStats($results);

        return [
            'results' => $results,
            'errorCount' => $stats['errorCount'],
            'warningCount' => $stats['warningCount'],
        ];
    }

    protected function processFile($file)
    {
        $text = file_get_contents($file);
        return $this->processText($text, $file);
    }

    protected function processText($text, $filename = '<text>')
    {
        list($messages, $fixedCode) = inspect($text, $this->options['--fix']);

        $stats = $this->calculateFileStats($messages);

        $result = [
            'filepath' => $filename,
            'messages' => $messages,
            'errorCount' => $stats['errorCount'],
            'warningCount' => $stats['warningCount'],
        ];

        if ($fixedCode) {
            $result['output'] = $fixedCode;
        }

        return $result;
    }

    protected function calculateFileStats($messages)
    {
        return array_reduce($messages, function ($stat, $message) {
            if ($message['type'] === 'error') {
                $stat['errorCount']++;
            } elseif ($message['type'] === 'warning') {
                $stat['warningCount']++;
            }
            return $stat;
        }, ['errorCount' => 0, 'warningCount' => 0]);
    }

    protected function calculateAllStats($results)
    {
        return array_reduce($results, function ($stat, $result) {
            $stat['errorCount'] += $result['errorCount'];
            $stat['warningCount'] += $result['warningCount'];
            return $stat;
        }, ['errorCount' => 0, 'warningCount' => 0]);
    }
}
