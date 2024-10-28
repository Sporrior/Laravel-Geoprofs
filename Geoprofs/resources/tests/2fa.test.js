/**
 * @jest-environment jsdom
 */
import { fireEvent } from '@testing-library/dom';

const htmlContent = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2FA Verification</title>
</head>
<body>
    <div class="login-container">
        <form action="/2fa/verify" method="POST" class="2fa-form">
            <div class="field">
                <label for="2fa_code">2FA Code</label>
                <input type="text" name="2fa_code" required>
            </div>
            <div class="field">
                <input type="submit" name="submit" value="Verify Code">
            </div>
        </form>
    </div>
    <div id="popup-2fa" class="popup-2fa">
        <div>2FA Code: <span id="code">123456</span></div>
        <div>Enter the code within <span class="timer" id="timer">10</span> seconds</div>
    </div>
</body>
</html>
`;

document.body.innerHTML = htmlContent;

let countdown = 10;
let timer;

// Define the startCountdown function here
function startCountdown() {
    timer = setInterval(() => {
        countdown--;
        document.getElementById('timer').innerText = countdown;
        if (countdown === 0) {
            regenerateCode();
            countdown = 10;
        }
    }, 1000);
}

// Define the regenerateCode function here
function regenerateCode() {
    fetch("/2fa/regenerate", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('code').innerText = data.new_code;
        })
        .catch(error => console.error('Error:', error));
}

describe('2FA Verification Pagina', () => {
    test('renderd 2fa veld', () => {
        const input = document.querySelector("input[name='2fa_code']");
        expect(input).not.toBeNull();
    });

    test('renderd submit knop', () => {
        const submitButton = document.querySelector("input[name='submit']");
        expect(submitButton.value).toBe("Verify Code");
    });

    test('toont de 2fa countdown timer', () => {
        const timerElement = document.getElementById('timer');
        expect(timerElement.innerHTML).toBe('10');
    });

    test('telt af and veranderd als de timer op 0 komt', () => {
        jest.useFakeTimers();
        startCountdown();

        // Fast forward time by 10 seconds
        jest.advanceTimersByTime(10000);

        const timerElement = document.getElementById('timer');
        expect(timerElement.innerHTML).toBe('10');

        jest.clearAllTimers();
    });

    test('roept de regenerate functie aan wanneer de timer 0 is', () => {
        // Mock fetch to simulate server response
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve({ new_code: "654321" })
            })
        );

        jest.useFakeTimers();
        startCountdown();

        // Fast forward time by 10 seconds to trigger regeneration
        jest.advanceTimersByTime(10000);

        // Check that the fetch was called to regenerate code
        expect(global.fetch).toHaveBeenCalled();

        // Check if the new code was set correctly
        const codeElement = document.getElementById('code');
        expect(codeElement.innerText).toBe('654321');

        jest.clearAllTimers();
    });
});
