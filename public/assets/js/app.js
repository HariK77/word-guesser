/**
 * Word Guesser Application
 * Frontend logic and interactivity
 */

class WordGuesserApp {
    constructor() {
        this.letterInputs = {};
        this.excludedInput = null;
        this.guessBtn = null;
        this.clearBtn = null;
        this.maxLetters = 5;

        this.init();
    }

    init() {
        this.cacheElements();
        this.attachEventListeners();
        this.restoreFormState();
        this.setFocus();
    }

    /**
     * Cache DOM elements
     */
    cacheElements() {
        for (let i = 1; i <= this.maxLetters; i++) {
            this.letterInputs[i] = document.getElementById(`l${i}`);
        }
        this.excludedInput = document.getElementById('excluded');
        this.guessBtn = document.getElementById('guess-btn');
        this.clearBtn = document.getElementById('clear-btn');
    }

    /**
     * Attach event listeners
     */
    attachEventListeners() {
        // Letter inputs
        Object.keys(this.letterInputs).forEach((key) => {
            const input = this.letterInputs[key];
            input.addEventListener('input', (e) => this.handleLetterInput(e));
            input.addEventListener('keypress', (e) => this.handleKeyPress(e));
            input.addEventListener('keydown', (e) => this.handleKeyDown(e));
            input.addEventListener('keyup', (e) => this.handleKeyUp(e));
        });

        // Buttons
        this.guessBtn.addEventListener('click', () => this.handleGuess());
        this.clearBtn.addEventListener('click', () => this.handleClear());

        // Excluded letters input
        this.excludedInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.toUpperCase();
        });
    }

    /**
     * Handle letter input
     */
    handleLetterInput(e) {
        const input = e.target;
        let value = input.value.toUpperCase();

        // Only allow single letter
        if (!/^[A-Z]?$/.test(value)) {
            input.value = value.slice(0, 1);
            return;
        }

        input.value = value;
        this.updateInputState(input);

        // Move to next input if letter entered
        if (value) {
            this.focusNextInput(input);
        }
    }

    /**
     * Handle keypress
     */
    handleKeyPress(e) {
        const char = String.fromCharCode(e.charCode);

        // Only allow letters
        if (!/^[A-Za-z]$/.test(char)) {
            e.preventDefault();
        }
    }

    /**
     * Handle keydown
     */
    handleKeyDown(e) {
        const input = e.target;
        const position = parseInt(input.dataset.position);

        if (e.key === 'Backspace' && !input.value) {
            e.preventDefault();
            this.focusPreviousInput(input);
        }

        if (e.key === 'ArrowRight') {
            e.preventDefault();
            this.focusNextInput(input);
        }

        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            this.focusPreviousInput(input);
        }

        if (e.key === 'Enter') {
            e.preventDefault();
            this.handleGuess();
        }
    }

    /**
     * Handle keyup
     */
    handleKeyUp(e) {
        e.target.value = e.target.value.toUpperCase();
    }

    /**
     * Focus next input
     */
    focusNextInput(currentInput) {
        const position = parseInt(currentInput.dataset.position);
        if (position < this.maxLetters) {
            this.letterInputs[position + 1].focus();
        }
    }

    /**
     * Focus previous input
     */
    focusPreviousInput(currentInput) {
        const position = parseInt(currentInput.dataset.position);
        if (position > 1) {
            this.letterInputs[position - 1].focus();
        }
    }

    /**
     * Update input visual state
     */
    updateInputState(input) {
        if (input.value) {
            input.classList.add('filled');
        } else {
            input.classList.remove('filled');
        }
    }

    /**
     * Handle guess button click
     */
    handleGuess() {
        if (this.guessBtn.disabled) return;

        const urlParams = new URLSearchParams();

        // Add letter parameters
        for (let i = 1; i <= this.maxLetters; i++) {
            const value = this.letterInputs[i].value.trim();
            urlParams.set(`l${i}`, value);
        }

        // Add excluded letters
        const excludedValue = this.excludedInput.value
            .split(',')
            .map(l => l.trim())
            .filter(l => l)
            .join(',');
        urlParams.set('excluded', excludedValue);

        // Show loading state
        const originalText = this.guessBtn.innerHTML;
        this.guessBtn.disabled = true;
        this.guessBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guessing...';

        // Navigate
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        window.location.href = newUrl;
    }

    /**
     * Handle clear button click
     */
    handleClear() {
        // Clear all inputs
        Object.keys(this.letterInputs).forEach((key) => {
            this.letterInputs[key].value = '';
            this.letterInputs[key].classList.remove('filled');
        });

        this.excludedInput.value = '';

        // Navigate to clean URL
        window.location = window.location.pathname;
    }

    /**
     * Restore form state from URL parameters
     */
    restoreFormState() {
        const urlParams = new URLSearchParams(window.location.search);

        // Restore letter inputs
        for (let i = 1; i <= this.maxLetters; i++) {
            const value = (urlParams.get(`l${i}`) || '').toUpperCase();
            this.letterInputs[i].value = value;
            this.updateInputState(this.letterInputs[i]);
        }

        // Restore excluded letters
        const excluded = (urlParams.get('excluded') || '').toUpperCase();
        this.excludedInput.value = excluded;
    }

    /**
     * Set initial focus
     */
    setFocus() {
        // Find first empty input
        for (let i = 1; i <= this.maxLetters; i++) {
            if (!this.letterInputs[i].value) {
                this.letterInputs[i].focus();
                return;
            }
        }

        // If all filled, focus on excluded input
        this.excludedInput.focus();
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new WordGuesserApp();
});
