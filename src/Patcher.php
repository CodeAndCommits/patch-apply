<?php
declare(strict_types=1);

namespace PatchApply;

use SebastianBergmann\Diff\Chunk;
use SebastianBergmann\Diff\Diff;
use SebastianBergmann\Diff\Line;
use SebastianBergmann\Diff\Parser;

class Patcher
{
    /**
     * Apply the given patch to the input
     *
     * @param string $input The raw input text
     * @param string $patch The input diff in unified format
     * @return string The patched text
     */
    public function apply(string $input, string $patch): string
    {
        $parser = new Parser();
        $diffs = $parser->parse($patch);
        $output = array_reduce($diffs, [$this, 'applyDiff'], explode("\n", $input));

        return implode("\n", $output);
    }

    /**
     * Apply diff to lines of content
     *
     * Chunks are processed in reverse order so that during processing prior line numbers are preserved
     *
     * @param string[] $input
     * @param Diff $diff
     * @return string[] the patched output lines
     */
    private function applyDiff(array $input, Diff $diff): array
    {
        $reversedChunks = array_reverse($diff->getChunks());
        return array_reduce($reversedChunks, [$this, 'getChunkOutput'], $input);
    }

    /**
     * Get the calculated output for a diff chunk
     *
     * @param string[] $input
     * @param Chunk $chunk
     * @return string[] The patched output lines
     */
    private function getChunkOutput(array $input, Chunk $chunk): array
    {
        $output = [];

        foreach ($chunk->getLines() as $line) {
            if ($line->getType() === Line::UNCHANGED) {
                $output[] = $line->getContent();
            }

            if ($line->getType() === Line::REMOVED) {
                continue;
            }

            if ($line->getType() === Line::ADDED) {
                $output[] = $line->getContent();
            }
        }

        array_splice($input, $chunk->getStart() - 1, $chunk->getStartRange(), $output);

        return $input;
    }
}
