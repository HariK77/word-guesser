<?php
/**
 * WordGuesserService - Business Logic for Word Guessing
 * Handles the core logic of guessing words
 */

namespace App\Services;

use App\Models\Word;

class WordGuesserService
{
    /**
     * Excluded letters
     */
    protected array $excludedLetters = [];

    /**
     * Known letters
     */
    protected array $knownLetters = [];

    /**
     * Allowed letters
     */
    protected array $allowedLetters = [];

    /**
     * Guessed words
     */
    protected array $guessedWords = [];

    /**
     * Words storage path
     */
    protected string $wordsPath;

    /**
     * Constructor
     */
    public function __construct(array $excluded = [], array $known = [], string $wordsPath = '')
    {
        $config = config('app');
        $this->excludedLetters = array_map('strtoupper', $excluded);
        $this->knownLetters = array_map('strtoupper', $known);
        $this->wordsPath = $wordsPath ?: $config['words_path'];
        $this->precomputeAllowedLetters();
    }

    /**
     * Precompute allowed letters for better performance
     */
    protected function precomputeAllowedLetters(): void
    {
        $config = config('app');
        $this->allowedLetters = [];

        for ($i = $config['letter_start']; $i <= $config['letter_end']; $i++) {
            $letter = chr($i);
            if (!in_array($letter, $this->excludedLetters)) {
                $this->allowedLetters[] = $letter;
            }
        }
    }

    /**
     * Main entry point for word guessing
     */
    public function guess(): array
    {
        $unknownPositions = $this->getUnknownLettersCount();

        if (empty($unknownPositions['positions'])) {
            return [];
        }

        $this->guessedWords = [];
        $this->recursiveGuess($unknownPositions['positions'], 0);

        return array_unique(array_map('strtolower', $this->guessedWords));
    }

    /**
     * Count unknown letters and their positions
     */
    protected function getUnknownLettersCount(): array
    {
        $positions = [];

        foreach ($this->knownLetters as $key => $value) {
            if (empty($value)) {
                $positions[] = $key;
            }
        }

        return [
            'unknown_letters_count' => count($positions),
            'positions' => $positions,
        ];
    }

    /**
     * Recursively fill unknown positions with allowed letters
     */
    protected function recursiveGuess(array $unknownPositions, int $index): void
    {
        // Base case: all positions filled
        if ($index === count($unknownPositions)) {
            $word = strtolower(implode($this->knownLetters));
            $knownWords = $this->loadWords(substr($word, 0, 1));

            if (in_array($word, $knownWords, true)) {
                $this->guessedWords[] = $word;
            }
            return;
        }

        // Recursive case: try each allowed letter
        $position = $unknownPositions[$index];

        foreach ($this->allowedLetters as $letter) {
            $this->knownLetters[$position] = $letter;
            $this->recursiveGuess($unknownPositions, $index + 1);
        }

        // Backtrack
        $this->knownLetters[$position] = '';
    }

    /**
     * Load words from JSON file
     */
    protected function loadWords(string $letter): array
    {
        $letter = strtolower(trim($letter));

        if (strlen($letter) === 0) {
            return [];
        }

        $path = $this->wordsPath . '/' . $letter . '.json';

        if (!file_exists($path)) {
            logger()->warning("Words file not found: {$path}");
            return [];
        }

        try {
            $content = file_get_contents($path);
            $words = json_decode($content, true);

            return is_array($words) ? $words : [];
        } catch (\Exception $e) {
            logger()->error("Error loading words: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all guessed words
     */
    public function getGuessedWords(): array
    {
        return $this->guessedWords;
    }

    /**
     * Get count of guessed words
     */
    public function getGuessedWordsCount(): int
    {
        return count($this->guessedWords);
    }

    /**
     * Filter words by pattern
     */
    public function filterWordsByPattern(array $words, array $knownLetters, array $excludedLetters): array
    {
        return array_filter($words, function ($wordValue) use ($knownLetters, $excludedLetters) {
            $word = new Word($wordValue);
            return $word->matchesPattern($knownLetters, $excludedLetters);
        });
    }

    /**
     * Set excluded letters
     */
    public function setExcludedLetters(array $letters): self
    {
        $this->excludedLetters = array_map('strtoupper', $letters);
        $this->precomputeAllowedLetters();
        return $this;
    }

    /**
     * Set known letters
     */
    public function setKnownLetters(array $letters): self
    {
        $this->knownLetters = array_map('strtoupper', $letters);
        return $this;
    }

    /**
     * Get excluded letters
     */
    public function getExcludedLetters(): array
    {
        return $this->excludedLetters;
    }

    /**
     * Get known letters
     */
    public function getKnownLetters(): array
    {
        return $this->knownLetters;
    }
}
