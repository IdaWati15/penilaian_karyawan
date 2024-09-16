<?php

namespace App\Helpers;






namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FuzzyController extends Controller
{
    public function parseFisFile($filePath)
    {
        $data = [
            'system' => [],
            'inputs' => [],
            'outputs' => [],
            'rules' => [],
        ];

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $section = '';

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line) || $line[0] === ';') {
                // Skip empty lines and comments
                continue;
            }

            if (strpos($line, '[System]') === 0) {
                $section = 'system';
                $data['system'] = $this->parseSystemSection($lines);
            } elseif (strpos($line, '[Input') === 0) {
                $section = 'input';
                $input = $this->parseInputSection($lines);
                $data['inputs'][$input['Name']] = $input;
            } elseif (strpos($line, '[Output') === 0) {
                $section = 'output';
                $output = $this->parseOutputSection($lines);
                $data['outputs'][$output['Name']] = $output;
            } elseif (strpos($line, '[Rules]') === 0) {
                $section = 'rules';
                $data['rules'] = $this->parseRulesSection($lines);
            } else {
                throw new \Exception("Unexpected section header: $line");
            }
        }

        return $data;
    }

    private function parseSystemSection(array &$lines)
    {
        $system = [];
        $line = array_shift($lines);

        while ($line = array_shift($lines)) {
            $line = trim($line);

            if (empty($line) || strpos($line, '[') === 0) {
                array_unshift($lines, $line);
                break;
            }

            if (preg_match('/(\w+)=(.*)/', $line, $matches)) {
                $system[$matches[1]] = trim($matches[2], "'\"");
            } else {
                throw new \Exception("System section line incorrectly formatted: $line");
            }
        }

        return $system;
    }

    private function parseInputSection(array &$lines)
    {
        $input = [];
        $line = array_shift($lines);

        if (strpos($line, '[Input') !== 0) {
            throw new \Exception("Expected '[Input' section header but found: $line");
        }

        // Extract the Name from the section
        $nameLine = array_shift($lines);
        if (preg_match('/Name=\'([^\']+)\'/', $nameLine, $matches)) {
            $input['Name'] = $matches[1];
        } else {
            throw new \Exception("Input section header missing 'Name'. Found line: $nameLine");
        }

        // Extract the Range
        $rangeLine = array_shift($lines);
        if (preg_match('/Range=\[(\d+) (\d+)\]/', $rangeLine, $matches)) {
            $input['Range'] = [(int) $matches[1], (int) $matches[2]];
        } else {
            throw new \Exception("Input section missing or incorrectly formatted 'Range'. Found line: $rangeLine");
        }

        // Extract Membership Functions
        while ($line = array_shift($lines)) {
            $line = trim($line);

            if (empty($line) || strpos($line, '[Input') === 0 || strpos($line, '[Output') === 0 || strpos($line, '[Rules]') === 0) {
                array_unshift($lines, $line);
                break;
            }

            if (preg_match('/MF(\d+)=\'([^\']+)\'\s*:\s*([^\s]+)\s*\[([^\]]+)\]/', $line, $matches)) {
                $input['MF' . $matches[1]] = [
                    'Name' => $matches[2],
                    'Type' => $matches[3],
                    'Params' => array_map('floatval', explode(' ', $matches[4])),
                ];
            } else {
                throw new \Exception("Membership function line incorrectly formatted: $line");
            }
        }

        return $input;
    }

    private function parseOutputSection(array &$lines)
    {
        $output = [];
        $line = array_shift($lines);

        if (strpos($line, '[Output') !== 0) {
            throw new \Exception("Expected '[Output' section header but found: $line");
        }

        // Extract the Name from the section
        $nameLine = array_shift($lines);
        if (preg_match('/Name=\'([^\']+)\'/', $nameLine, $matches)) {
            $output['Name'] = $matches[1];
        } else {
            throw new \Exception("Output section header missing 'Name'. Found line: $nameLine");
        }

        // Extract the Range
        $rangeLine = array_shift($lines);
        if (preg_match('/Range=\[(\d+) (\d+)\]/', $rangeLine, $matches)) {
            $output['Range'] = [(int) $matches[1], (int) $matches[2]];
        } else {
            throw new \Exception("Output section missing or incorrectly formatted 'Range'. Found line: $rangeLine");
        }

        // Extract Membership Functions
        while ($line = array_shift($lines)) {
            $line = trim($line);

            if (empty($line) || strpos($line, '[Input') === 0 || strpos($line, '[Output') === 0 || strpos($line, '[Rules]') === 0) {
                array_unshift($lines, $line);
                break;
            }

            if (preg_match('/MF(\d+)=\'([^\']+)\'\s*:\s*([^\s]+)\s*\[([^\]]+)\]/', $line, $matches)) {
                $output['MF' . $matches[1]] = [
                    'Name' => $matches[2],
                    'Type' => $matches[3],
                    'Params' => array_map('floatval', explode(' ', $matches[4])),
                ];
            } else {
                throw new \Exception("Membership function line incorrectly formatted: $line");
            }
        }

        return $output;
    }

    private function parseRulesSection(array &$lines)
    {
        $rules = [];

        while ($line = array_shift($lines)) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if (strpos($line, '[Input') === 0 || strpos($line, '[Output') === 0 || strpos($line, '[Rules]') === 0) {
                array_unshift($lines, $line);
                break;
            }

            $rules[] = $line;
        }

        return $rules;
    }
}
